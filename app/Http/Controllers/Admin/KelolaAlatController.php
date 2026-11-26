<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatCamping;
use App\Models\KategoriAlat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KelolaAlatController extends Controller
{
    // Tampilkan daftar katalog alat camping
    public function indeks(Request $request): View
    {
        $kategoriId = $request->query('kategori_id');
        $daftarKategori = KategoriAlat::orderBy('nama_kategori')->get();
        $daftarAlat = AlatCamping::with('kategori')
            ->when($kategoriId, fn ($q) => $q->where('kategori_id', $kategoriId))
            ->latest()
            ->get();

        return view('admin.alat.index', compact('daftarAlat', 'daftarKategori', 'kategoriId'));
    }

    // Tampilkan form tambah alat camping baru
    public function tambah(): View
    {
        $daftarKategori = KategoriAlat::orderBy('nama_kategori')->get();

        return view('admin.alat.create', compact('daftarKategori'));
    }

    // Simpan data alat camping baru beserta gambar
    public function simpan(Request $request): RedirectResponse
    {
        $dataValid = $request->validate([
            'kategori_id' => ['required', 'exists:kategori_alat,id'],
            'nama_alat' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'kelengkapan_standar' => ['required', 'string'],
            'harga_per_hari' => ['required', 'numeric', 'min:0'],
            'stok_total' => ['required', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            $dataValid['gambar'] = $request->file('gambar')->store('alat_camping', 'public');
        }

        AlatCamping::create($dataValid);

        return redirect()->route('admin.alat.index')->with('sukses', 'Alat camping baru berhasil ditambahkan ke katalog.');
    }

    // Tampilkan form ubah data alat camping
    public function ubah(AlatCamping $alatCamping): View
    {
        $daftarKategori = KategoriAlat::orderBy('nama_kategori')->get();

        return view('admin.alat.edit', compact('alatCamping', 'daftarKategori'));
    }

    // Perbarui data alat camping dan ganti gambar bila diunggah
    public function perbarui(Request $request, AlatCamping $alatCamping): RedirectResponse
    {
        $dataValid = $request->validate([
            'kategori_id' => ['required', 'exists:kategori_alat,id'],
            'nama_alat' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'kelengkapan_standar' => ['required', 'string'],
            'harga_per_hari' => ['required', 'numeric', 'min:0'],
            'stok_total' => ['required', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($alatCamping->gambar) {
                Storage::disk('public')->delete($alatCamping->gambar);
            }
            $dataValid['gambar'] = $request->file('gambar')->store('alat_camping', 'public');
        }

        $alatCamping->update($dataValid);

        return redirect()->route('admin.alat.index')->with('sukses', 'Data alat camping berhasil diperbarui.');
    }

    // Hapus data alat camping beserta gambarnya
    public function hapus(AlatCamping $alatCamping): RedirectResponse
    {
        if ($alatCamping->gambar) {
            Storage::disk('public')->delete($alatCamping->gambar);
        }

        $alatCamping->delete();

        return redirect()->route('admin.alat.index')->with('sukses', 'Alat camping berhasil dihapus dari katalog.');
    }
}

