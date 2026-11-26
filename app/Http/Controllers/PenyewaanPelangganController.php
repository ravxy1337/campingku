<?php

namespace App\Http\Controllers;

use App\Models\AlatCamping;
use App\Models\DetailPenyewaan;
use App\Models\Penyewaan;
use App\Services\LayananCekNik;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenyewaanPelangganController extends Controller
{
    // Tampilkan halaman checkout penyewaan alat
    public function formCheckout(Request $request, AlatCamping $alatCamping): View|RedirectResponse
    {
        $tanggalSewa = $request->query('tanggal_sewa', Carbon::today()->toDateString());
        $tanggalKembali = $request->query('tanggal_kembali', Carbon::tomorrow()->toDateString());
        $jumlah = max(1, (int) $request->query('jumlah', 1));

        if (Carbon::parse($tanggalKembali)->lte(Carbon::parse($tanggalSewa))) {
            $tanggalKembali = Carbon::parse($tanggalSewa)->addDay()->toDateString();
        }

        $stokTersedia = $alatCamping->hitungStokTersedia($tanggalSewa, $tanggalKembali);
        if ($stokTersedia < $jumlah) {
            return redirect()->route('alat.detail', $alatCamping->id)
                ->with('galat', 'Stok alat tidak mencukupi pada rentang tanggal yang dipilih.');
        }

        $durasiHari = max(1, Carbon::parse($tanggalSewa)->diffInDays(Carbon::parse($tanggalKembali)));
        $totalHarga = $durasiHari * $jumlah * (float) $alatCamping->harga_per_hari;

        return view('pelanggan.checkout', compact(
            'alatCamping',
            'tanggalSewa',
            'tanggalKembali',
            'jumlah',
            'durasiHari',
            'totalHarga',
            'stokTersedia'
        ));
    }

    // Endpoint AJAX untuk pratinjau data wilayah NIK sebelum submit
    public function cekNikAjax(Request $request, LayananCekNik $layananCekNik): JsonResponse
    {
        $request->validate([
            'nik' => ['required', 'digits:16'],
        ], [
            'nik.digits' => 'NIK wajib terdiri dari 16 digit angka.',
        ]);

        $hasil = $layananCekNik->periksaNik($request->input('nik'));

        return response()->json($hasil, $hasil['berhasil'] ? 200 : 422);
    }

    // Simpan transaksi penyewaan beserta validasi ulang API NIK
    public function simpanCheckout(Request $request, AlatCamping $alatCamping, LayananCekNik $layananCekNik): RedirectResponse
    {
        $pengguna = Auth::user();

        $dataValid = $request->validate([
            'tanggal_sewa' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_kembali' => ['required', 'date', 'after:tanggal_sewa'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tipe_pembayaran' => ['required', Rule::in(['dp', 'lunas'])],
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('users', 'nik')->ignore($pengguna->id),
            ],
            'bukti_bayar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_ktp_jaminan' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'setuju_ambil_di_toko' => ['accepted'],
            'setuju_jaminan_ktp' => ['accepted'],
        ], [
            'nik.digits' => 'NIK wajib berjumlah tepat 16 digit angka.',
            'nik.unique' => 'NIK tersebut sudah terdaftar pada akun pengguna lain.',
            'setuju_ambil_di_toko.accepted' => 'Anda wajib menyetujui pengambilan alat langsung di toko (Self-Pickup).',
            'setuju_jaminan_ktp.accepted' => 'Anda wajib menyetujui penyerahan KTP fisik asli saat mengambil alat.',
        ]);

        // Validasi ulang stok ketersediaan pada rentang tanggal
        $stokTersedia = $alatCamping->hitungStokTersedia($dataValid['tanggal_sewa'], $dataValid['tanggal_kembali']);
        if ($stokTersedia < (int) $dataValid['jumlah']) {
            return back()->withInput()->with('galat', 'Stok tidak mencukupi pada tanggal yang dipilih.');
        }

        // Validasi ulang NIK ke layanan API parse1337
        $hasilNik = $layananCekNik->periksaNik($dataValid['nik']);
        if (! $hasilNik['berhasil']) {
            return back()->withInput()->withErrors(['nik' => $hasilNik['pesan']]);
        }

        $durasiHari = max(1, Carbon::parse($dataValid['tanggal_sewa'])->diffInDays(Carbon::parse($dataValid['tanggal_kembali'])));
        $subtotal = $durasiHari * (int) $dataValid['jumlah'] * (float) $alatCamping->harga_per_hari;
        $jumlahDibayar = $dataValid['tipe_pembayaran'] === 'dp' ? ($subtotal * 0.5) : $subtotal;

        $pathBuktiBayar = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        $pathFotoKtp = $request->file('foto_ktp_jaminan')->store('ktp_jaminan', 'public');

        DB::transaction(function () use ($pengguna, $alatCamping, $dataValid, $hasilNik, $subtotal, $jumlahDibayar, $pathBuktiBayar, $pathFotoKtp) {
            // Perbarui profil pelanggan dengan data hasil API NIK
            $pengguna->update($hasilNik['data']);

            // Buat data transaksi penyewaan
            $penyewaan = Penyewaan::create([
                'kode_booking' => 'CMP-'.now()->format('Ymd').'-'.strtoupper(Str::random(5)),
                'user_id' => $pengguna->id,
                'tanggal_sewa' => $dataValid['tanggal_sewa'],
                'tanggal_kembali' => $dataValid['tanggal_kembali'],
                'tipe_pembayaran' => $dataValid['tipe_pembayaran'],
                'total_harga' => $subtotal,
                'jumlah_dibayar' => $jumlahDibayar,
                'bukti_bayar' => $pathBuktiBayar,
                'foto_ktp_jaminan' => $pathFotoKtp,
                'status_sewa' => 'menunggu_acc',
                'status_jaminan_ktp' => 'belum_diserahkan',
            ]);

            // Buat rincian item yang disewa
            DetailPenyewaan::create([
                'penyewaan_id' => $penyewaan->id,
                'alat_id' => $alatCamping->id,
                'jumlah' => (int) $dataValid['jumlah'],
                'harga_satuan' => $alatCamping->harga_per_hari,
                'subtotal' => $subtotal,
            ]);
        });

        return redirect()->route('pelanggan.sewa-saya')
            ->with('sukses', 'Pengajuan sewa berhasil dikirim dan sedang menunggu verifikasi Petugas.');
    }

    // Tampilkan halaman riwayat dan status sewa pelanggan
    public function daftarSewaSaya(): View
    {
        $daftarPenyewaan = Penyewaan::with(['detailPenyewaan.alat', 'petugas'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pelanggan.sewa-saya', compact('daftarPenyewaan'));
    }
}

