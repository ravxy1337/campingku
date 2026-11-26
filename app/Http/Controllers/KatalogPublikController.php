<?php

namespace App\Http\Controllers;

use App\Models\AlatCamping;
use App\Models\KategoriAlat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KatalogPublikController extends Controller
{
    // Tampilkan halaman beranda daftar alat beserta status ketersediaan hari ini
    public function beranda(Request $request): View
    {
        $hariIni = Carbon::today()->toDateString();
        $kategoriId = $request->query('kategori_id');

        $daftarKategori = KategoriAlat::orderBy('nama_kategori')->get();
        $daftarAlat = AlatCamping::with('kategori')
            ->when($kategoriId, fn ($q) => $q->where('kategori_id', $kategoriId))
            ->orderBy('nama_alat')
            ->get()
            ->map(function (AlatCamping $alat) use ($hariIni) {
                $alat->sisa_hari_ini = $alat->hitungStokTersedia($hariIni, $hariIni);

                return $alat;
            });

        return view('publik.beranda', compact('daftarAlat', 'daftarKategori', 'kategoriId'));
    }

    // Tampilkan detail alat camping dan hasil simulasi ketersediaan rentang tanggal
    public function detailAlat(Request $request, AlatCamping $alatCamping): View
    {
        $alatCamping->load('kategori');
        $hariIni = Carbon::today()->toDateString();
        $sisaHariIni = $alatCamping->hitungStokTersedia($hariIni, $hariIni);
        $hasilSimulasi = null;

        if ($request->filled(['tanggal_sewa', 'tanggal_kembali', 'jumlah'])) {
            $dataValid = $request->validate([
                'tanggal_sewa' => ['required', 'date', 'after_or_equal:today'],
                'tanggal_kembali' => ['required', 'date', 'after:tanggal_sewa'],
                'jumlah' => ['required', 'integer', 'min:1'],
            ], [
                'tanggal_sewa.after_or_equal' => 'Tanggal mulai sewa minimal adalah hari ini.',
                'tanggal_kembali.after' => 'Tanggal kembali harus setelah tanggal sewa.',
            ]);

            $stokTersedia = $alatCamping->hitungStokTersedia($dataValid['tanggal_sewa'], $dataValid['tanggal_kembali']);
            $durasiHari = max(1, Carbon::parse($dataValid['tanggal_sewa'])->diffInDays(Carbon::parse($dataValid['tanggal_kembali'])));
            $jumlahDiminta = (int) $dataValid['jumlah'];
            $totalBiaya = $durasiHari * $jumlahDiminta * (float) $alatCamping->harga_per_hari;

            $hasilSimulasi = [
                'tanggal_sewa' => $dataValid['tanggal_sewa'],
                'tanggal_kembali' => $dataValid['tanggal_kembali'],
                'durasi_hari' => $durasiHari,
                'jumlah_diminta' => $jumlahDiminta,
                'stok_tersedia' => $stokTersedia,
                'cukup' => $stokTersedia >= $jumlahDiminta,
                'total_biaya' => $totalBiaya,
            ];
        }

        return view('publik.detail-alat', compact('alatCamping', 'sisaHariIni', 'hasilSimulasi'));
    }
}

