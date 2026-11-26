<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Services\LayananCekNik;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VerifikasiPetugasController extends Controller
{
    // Tampilkan daftar antrean pesanan untuk petugas
    public function daftarAntrean(Request $request): View
    {
        $filterStatus = $request->query('status');

        $daftarAntrean = Penyewaan::with(['pelanggan', 'detailPenyewaan.alat'])
            ->when(
                $filterStatus,
                fn ($q) => $q->where('status_sewa', $filterStatus),
                fn ($q) => $q->whereIn('status_sewa', ['menunggu_acc', 'siap_diambil', 'sedang_disewa'])
            )
            ->latest()
            ->get();

        $ringkasanAntrean = [
            'menunggu_acc' => Penyewaan::where('status_sewa', 'menunggu_acc')->count(),
            'siap_diambil' => Penyewaan::where('status_sewa', 'siap_diambil')->count(),
            'sedang_disewa' => Penyewaan::where('status_sewa', 'sedang_disewa')->count(),
            'selesai' => Penyewaan::where('status_sewa', 'selesai')->count(),
        ];

        return view('petugas.antrean', compact('daftarAntrean', 'filterStatus', 'ringkasanAntrean'));
    }

    // Tampilkan detail verifikasi berdampingan antara foto KTP, bukti bayar, dan data API NIK
    public function detailVerifikasi(Penyewaan $penyewaan, LayananCekNik $layananCekNik): View
    {
        $penyewaan->load(['pelanggan', 'petugas', 'detailPenyewaan.alat']);

        $dataApiNik = null;
        if (! empty($penyewaan->pelanggan->nik)) {
            $dataApiNik = $layananCekNik->periksaNik($penyewaan->pelanggan->nik);
        }

        return view('petugas.detail-verifikasi', compact('penyewaan', 'dataApiNik'));
    }

    // Setujui pesanan (ACC) dan ubah status menjadi siap diambil
    public function setujuiPesanan(Penyewaan $penyewaan): RedirectResponse
    {
        $penyewaan->update([
            'status_sewa' => 'siap_diambil',
            'petugas_id' => Auth::id(),
            'catatan_penolakan' => null,
        ]);

        return redirect()->route('petugas.verifikasi.detail', $penyewaan->id)
            ->with('sukses', 'Pesanan berhasil di-ACC dan berstatus Siap Diambil.');
    }

    // Tolak pesanan dengan catatan penolakan wajib
    public function tolakPesanan(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $dataValid = $request->validate([
            'catatan_penolakan' => ['required', 'string', 'min:5'],
        ], [
            'catatan_penolakan.required' => 'Catatan alasan penolakan wajib diisi.',
        ]);

        $penyewaan->update([
            'status_sewa' => 'ditolak',
            'petugas_id' => Auth::id(),
            'catatan_penolakan' => $dataValid['catatan_penolakan'],
        ]);

        return redirect()->route('petugas.verifikasi.detail', $penyewaan->id)
            ->with('sukses', 'Pesanan telah ditolak beserta catatan penolakan.');
    }

    // Proses serah terima alat di toko, tahan KTP fisik, dan lunasi pembayaran
    public function serahTerimaToko(Penyewaan $penyewaan): RedirectResponse
    {
        $penyewaan->update([
            'status_sewa' => 'sedang_disewa',
            'status_jaminan_ktp' => 'ditahan_di_toko',
            'jumlah_dibayar' => $penyewaan->total_harga,
            'petugas_id' => Auth::id(),
        ]);

        return redirect()->route('petugas.verifikasi.detail', $penyewaan->id)
            ->with('sukses', 'Serah terima berhasil. Alat sedang disewa, KTP ditahan di toko, dan pembayaran telah lunas.');
    }

    // Proses pengembalian alat di toko, kembalikan KTP fisik, dan catat tanggal aktual
    public function pengembalianToko(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $penyewaan->update([
            'status_sewa' => 'selesai',
            'status_jaminan_ktp' => 'dikembalikan',
            'tanggal_dikembalikan_aktual' => Carbon::today()->toDateString(),
            'catatan_kondisi' => $request->input('catatan_kondisi'),
            'petugas_id' => Auth::id(),
        ]);

        return redirect()->route('petugas.verifikasi.detail', $penyewaan->id)
            ->with('sukses', 'Pengembalian selesai. Alat telah diterima kembali dan KTP fisik telah dikembalikan.');
    }
}

