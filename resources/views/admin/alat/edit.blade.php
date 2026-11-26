@extends('layouts.dashboard')

@section('judul', 'Edit Alat Camping')

@section('konten')
{{-- Navigasi kembali --}}
<a href="{{ route('admin.alat.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-zinc-500 hover:text-zinc-900">
    <x-ikon nama="panah-kiri" kelas="h-4 w-4" />
    Kembali ke Katalog Alat
</a>

<div class="mt-4 max-w-3xl">
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Edit Alat Camping</h1>
    <p class="mt-1 text-sm text-zinc-500">Perbarui detail, harga, stok, atau foto untuk {{ $alatCamping->nama_alat }}.</p>

    {{-- Form ubah alat --}}
    <form action="{{ route('admin.alat.update', $alatCamping) }}" method="POST" enctype="multipart/form-data" class="kartu mt-6 space-y-5 p-6">
        @csrf
        @method('PUT')
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="nama_alat" class="label-input">Nama Alat</label>
                <input type="text" id="nama_alat" name="nama_alat" value="{{ old('nama_alat', $alatCamping->nama_alat) }}" required class="kolom-input">
            </div>
            <div>
                <label for="kategori_id" class="label-input">Kategori</label>
                <select id="kategori_id" name="kategori_id" required class="kolom-input">
                    @foreach ($daftarKategori as $kategori)
                        <option value="{{ $kategori->id }}" @selected(old('kategori_id', $alatCamping->kategori_id) == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="harga_per_hari" class="label-input">Harga Sewa / Hari (Rp)</label>
                <input type="number" id="harga_per_hari" name="harga_per_hari" value="{{ old('harga_per_hari', (int) $alatCamping->harga_per_hari) }}" min="0" required class="kolom-input">
            </div>
            <div>
                <label for="stok_total" class="label-input">Stok Total (Unit)</label>
                <input type="number" id="stok_total" name="stok_total" value="{{ old('stok_total', $alatCamping->stok_total) }}" min="0" required class="kolom-input">
            </div>
        </div>

        <div>
            <label for="deskripsi" class="label-input">Deskripsi Alat</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" required class="kolom-input">{{ old('deskripsi', $alatCamping->deskripsi) }}</textarea>
        </div>

        <div>
            <label for="kelengkapan_standar" class="label-input">Kelengkapan Standar (pisahkan dengan koma)</label>
            <textarea id="kelengkapan_standar" name="kelengkapan_standar" rows="2" required class="kolom-input">{{ old('kelengkapan_standar', $alatCamping->kelengkapan_standar) }}</textarea>
        </div>

        @if ($alatCamping->gambar)
            <div>
                <span class="label-input">Foto Saat Ini</span>
                <img src="{{ asset('storage/'.$alatCamping->gambar) }}" alt="{{ $alatCamping->nama_alat }}" class="h-36 rounded-xl object-cover ring-1 ring-stone-200">
            </div>
        @endif

        <x-unggah-berkas nama="gambar" label="Ganti Foto Alat (Opsional)" :wajib="false" />

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.alat.index') }}" class="tombol-garis">Batal</a>
            <button type="submit" class="tombol-utama">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

