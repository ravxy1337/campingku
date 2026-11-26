<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\KelolaAlatController;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\KatalogPublikController;
use App\Http\Controllers\PenyewaanPelangganController;
use App\Http\Controllers\VerifikasiPetugasController;
use Illuminate\Support\Facades\Route;

// Route Halaman Publik & Simulasi Ketersediaan
Route::get('/', [KatalogPublikController::class, 'beranda'])->name('beranda');
Route::get('/alat/{alatCamping}', [KatalogPublikController::class, 'detailAlat'])->name('alat.detail');

// Route Autentikasi Tamu
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AutentikasiController::class, 'formMasuk'])->name('login');
    Route::post('/masuk', [AutentikasiController::class, 'prosesMasuk'])->name('login.proses');
    Route::get('/daftar', [AutentikasiController::class, 'formDaftar'])->name('register');
    Route::post('/daftar', [AutentikasiController::class, 'prosesDaftar'])->name('register.proses');
});

// Route Keluar Sesi
Route::post('/keluar', [AutentikasiController::class, 'prosesKeluar'])->middleware('auth')->name('logout');

// Route Area Pelanggan
Route::middleware(['auth', 'peran:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/checkout/{alatCamping}', [PenyewaanPelangganController::class, 'formCheckout'])->name('checkout');
    Route::post('/checkout/{alatCamping}', [PenyewaanPelangganController::class, 'simpanCheckout'])->name('checkout.simpan');
    Route::post('/cek-nik', [PenyewaanPelangganController::class, 'cekNikAjax'])->name('cek-nik');
    Route::get('/sewa-saya', [PenyewaanPelangganController::class, 'daftarSewaSaya'])->name('sewa-saya');
});

// Route Area Admin
Route::middleware(['auth', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'indeks'])->name('dashboard');
    Route::get('/alat', [KelolaAlatController::class, 'indeks'])->name('alat.index');
    Route::get('/alat/tambah', [KelolaAlatController::class, 'tambah'])->name('alat.create');
    Route::post('/alat', [KelolaAlatController::class, 'simpan'])->name('alat.store');
    Route::get('/alat/{alatCamping}/ubah', [KelolaAlatController::class, 'ubah'])->name('alat.edit');
    Route::put('/alat/{alatCamping}', [KelolaAlatController::class, 'perbarui'])->name('alat.update');
    Route::delete('/alat/{alatCamping}', [KelolaAlatController::class, 'hapus'])->name('alat.destroy');
    Route::view('/kategori', 'admin.kategori.index')->name('kategori.index');
    Route::view('/paket', 'admin.paket.index')->name('paket.index');
    Route::view('/denda', 'admin.denda.index')->name('denda.index');
    Route::view('/pengguna', 'admin.pengguna.index')->name('pengguna.index');
    Route::view('/laporan', 'admin.laporan.index')->name('laporan.index');
});

// Route Area Petugas & Admin
Route::middleware(['auth', 'peran:petugas,admin'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/antrean', [VerifikasiPetugasController::class, 'daftarAntrean'])->name('antrean');
    Route::get('/verifikasi/{penyewaan}', [VerifikasiPetugasController::class, 'detailVerifikasi'])->name('verifikasi.detail');
    Route::patch('/verifikasi/{penyewaan}/setujui', [VerifikasiPetugasController::class, 'setujuiPesanan'])->name('verifikasi.setujui');
    Route::patch('/verifikasi/{penyewaan}/tolak', [VerifikasiPetugasController::class, 'tolakPesanan'])->name('verifikasi.tolak');
    Route::patch('/verifikasi/{penyewaan}/serah-terima', [VerifikasiPetugasController::class, 'serahTerimaToko'])->name('verifikasi.serah-terima');
    Route::patch('/verifikasi/{penyewaan}/pengembalian', [VerifikasiPetugasController::class, 'pengembalianToko'])->name('verifikasi.pengembalian');
});
