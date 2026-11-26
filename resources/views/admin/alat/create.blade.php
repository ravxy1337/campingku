@extends('layouts.dashboard')

@section('judul', 'Tambah Alat Camping')

@section('konten')
<a href="{{ route('admin.alat.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 transition hover:text-zinc-900">
    <x-ikon nama="panah-kiri" kelas="h-3.5 w-3.5" />
    Kembali ke Katalog Alat
</a>

<div class="mt-4 max-w-3xl">
    <p class="text-sm text-zinc-600">Lengkapi informasi perlengkapan camping baru untuk ditampilkan di katalog publik.</p>

    <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data" class="kartu mt-5 space-y-5 p-6 sm:p-8">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="nama_alat" class="label-input">Nama Alat</label>
                <input type="text" id="nama_alat" name="nama_alat" value="{{ old('nama_alat') }}" required class="kolom-input" placeholder="Contoh: Tenda Dome 4 Orang">
            </div>
            <div>
                <label for="kategori_id" class="label-input">Kategori</label>
                <select id="kategori_id" name="kategori_id" required class="kolom-input">
                    <option value="">Pilih kategori</option>
                    @foreach ($daftarKategori as $kategori)
                        <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="harga_per_hari" class="label-input">Harga Sewa / Hari (Rp)</label>
                <input type="number" id="harga_per_hari" name="harga_per_hari" value="{{ old('harga_per_hari') }}" min="0" required class="kolom-input tabular-nums" placeholder="55000">
            </div>
            <div>
                <label for="stok_total" class="label-input">Stok Total (Unit)</label>
                <input type="number" id="stok_total" name="stok_total" value="{{ old('stok_total', 1) }}" min="0" required class="kolom-input tabular-nums">
            </div>
        </div>

        <div>
            <label for="deskripsi" class="label-input">Deskripsi Alat</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" required class="kolom-input" placeholder="Jelaskan spesifikasi dan keunggulan alat...">{{ old('deskripsi') }}</textarea>
        </div>

        <div>
            <label for="kelengkapan_standar" class="label-input">Kelengkapan Standar (pisahkan dengan koma)</label>
            <textarea id="kelengkapan_standar" name="kelengkapan_standar" rows="2" required class="kolom-input" placeholder="1x Inner Tenda, 1x Flysheet, 2x Frame, 12x Pasak">{{ old('kelengkapan_standar') }}</textarea>
        </div>

        <x-unggah-berkas nama="gambar" label="Foto Alat (Opsional)" :wajib="false" />

        <div class="flex items-center justify-end gap-3 border-t border-zinc-200/80 pt-5">
            <a href="{{ route('admin.alat.index') }}" class="tombol-garis">Batal</a>
            <button type="submit" class="tombol-utama">Simpan Alat</button>
        </div>
    </form>
</div>
@endsection
