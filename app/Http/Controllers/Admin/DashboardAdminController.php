<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatCamping;
use App\Models\KategoriAlat;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\View\View;

class DashboardAdminController extends Controller
{
    // Tampilkan ringkasan statistik untuk halaman dashboard admin
    public function indeks(): View
    {
        $statistik = [
            'total_alat' => AlatCamping::count(),
            'total_kategori' => KategoriAlat::count(),
            'penyewaan_aktif' => Penyewaan::whereIn('status_sewa', ['menunggu_acc', 'siap_diambil', 'sedang_disewa'])->count(),
            'total_pelanggan' => User::where('peran', 'pelanggan')->count(),
            'total_pendapatan' => Penyewaan::whereIn('status_sewa', ['siap_diambil', 'sedang_disewa', 'selesai'])->sum('jumlah_dibayar'),
        ];

        return view('admin.dashboard', compact('statistik'));
    }
}

