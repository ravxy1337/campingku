@extends('layouts.dashboard')

@section('judul', 'Kelola Katalog Alat')

@section('konten')
{{-- Kepala halaman katalog admin --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Kelola Katalog Alat</h1>
        <p class="mt-1 text-sm text-zinc-500">Tambah, ubah, dan hapus data alat camping yang tampil di katalog.</p>
    </div>
    <a href="{{ route('admin.alat.create') }}" class="tombol-utama">
        <x-ikon nama="tambah" kelas="h-4 w-4" />
        Tambah Alat
    </a>
</div>

{{-- Filter kategori --}}
<div class="mt-6 flex flex-wrap gap-2">
    <a href="{{ route('admin.alat.index') }}" @class(['rounded-full px-4 py-1.5 text-sm font-medium transition', 'bg-zinc-900 text-white' => ! $kategoriId, 'bg-white text-zinc-600 ring-1 ring-stone-200 hover:ring-zinc-400' => $kategoriId])>Semua</a>
    @foreach ($daftarKategori as $kategori)
        <a href="{{ route('admin.alat.index', ['kategori_id' => $kategori->id]) }}" @class(['rounded-full px-4 py-1.5 text-sm font-medium transition', 'bg-zinc-900 text-white' => (string) $kategoriId === (string) $kategori->id, 'bg-white text-zinc-600 ring-1 ring-stone-200 hover:ring-zinc-400' => (string) $kategoriId !== (string) $kategori->id])>{{ $kategori->nama_kategori }}</a>
    @endforeach
</div>

{{-- Tabel daftar katalog alat --}}
<div class="kartu mt-6 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Alat Camping</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3">Harga / Hari</th>
                    <th class="px-6 py-3">Stok Total</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($daftarAlat as $alat)
                    <tr class="transition hover:bg-stone-50/60">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <x-gambar-alat :alat="$alat" kelas="h-12 w-16 shrink-0 rounded-xl" />
                                <div class="min-w-0">
                                    <p class="font-semibold text-zinc-900">{{ $alat->nama_alat }}</p>
                                    <p class="max-w-xs truncate text-xs text-zinc-500">{{ $alat->deskripsi }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-600">{{ $alat->kategori->nama_kategori }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-semibold text-zinc-900">@rupiah($alat->harga_per_hari)</td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-700">{{ $alat->stok_total }} unit</td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.alat.edit', $alat) }}" class="tombol-garis px-3 py-1.5 text-xs">
                                    <x-ikon nama="edit" kelas="h-3.5 w-3.5" />
                                    Edit
                                </a>
                                <form action="{{ route('admin.alat.destroy', $alat) }}" method="POST" onsubmit="return confirm('Hapus alat ini dari katalog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tombol-bahaya px-3 py-1.5 text-xs">
                                        <x-ikon nama="hapus" kelas="h-3.5 w-3.5" />
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center text-sm text-zinc-500">Belum ada data alat camping.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
