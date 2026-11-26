@extends('layouts.dashboard')

@section('judul', 'Edit Alat Camping')

@section('konten')
<a href="{{ route('admin.alat.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 transition hover:text-zinc-900">
    <x-ikon nama="panah-kiri" kelas="h-3.5 w-3.5" />
    Kembali ke Katalog Alat
</a>

<div class="mt-4 max-w-3xl">
    <p class="text-sm text-zinc-600">Perbarui detail, tarif sewa, jumlah stok, atau foto untuk <span class="font-medium text-zinc-900">{{ $alatCamping->nama_alat }}</span>.</p>

    <form action="{{ route('admin.alat.update', $alatCamping) }}" method="POST" enctype="multipart/form-data" class="kartu mt-5 space-y-5 p-6 sm:p-8">
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
                <input type="number" id="harga_per_hari" name="harga_per_hari" value="{{ old('harga_per_hari', (int) $alatCamping->harga_per_hari) }}" min="0" required class="kolom-input tabular-nums">
            </div>
            <div>
                <label for="stok_total" class="label-input">Stok Total (Unit)</label>
                <input type="number" id="stok_total" name="stok_total" value="{{ old('stok_total', $alatCamping->stok_total) }}" min="0" required class="kolom-input tabular-nums">
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
                <img src="{{ asset('storage/'.$alatCamping->gambar) }}" alt="{{ $alatCamping->nama_alat }}" class="h-36 rounded-lg border border-zinc-200 object-cover">
            </div>
        @endif

        <x-unggah-berkas nama="gambar" label="Ganti Foto Alat (Opsional)" :wajib="false" />

        <div class="flex items-center justify-end gap-3 border-t border-zinc-200/80 pt-5">
            <a href="{{ route('admin.alat.index') }}" class="tombol-garis">Batal</a>
            <button type="submit" class="tombol-utama">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
