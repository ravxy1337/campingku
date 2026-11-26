@extends('layouts.dashboard')

@section('judul', 'Kelola Katalog Alat')

@section('konten')
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-zinc-600">Tambah, perbarui, dan atur stok alat camping yang tampil di katalog publik.</p>
    <a href="{{ route('admin.alat.create') }}" class="tombol-utama self-start sm:self-auto">
        <x-ikon nama="tambah" kelas="h-4 w-4" />
        Tambah Alat
    </a>
</div>

{{-- Filter kategori terukur --}}
<div class="mt-6 flex flex-wrap gap-2">
    <a
        href="{{ route('admin.alat.index') }}"
        @class([
            'rounded-lg border px-3.5 py-1.5 text-xs font-medium transition',
            'border-zinc-900 bg-zinc-900 text-white' => ! $kategoriId,
            'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:text-zinc-900' => $kategoriId,
        ])
    >
        Semua
    </a>
    @foreach ($daftarKategori as $kategori)
        @php $aktif = (string) $kategoriId === (string) $kategori->id; @endphp
        <a
            href="{{ route('admin.alat.index', ['kategori_id' => $kategori->id]) }}"
            @class([
                'rounded-lg border px-3.5 py-1.5 text-xs font-medium transition',
                'border-zinc-900 bg-zinc-900 text-white' => $aktif,
                'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:text-zinc-900' => ! $aktif,
            ])
        >
            {{ $kategori->nama_kategori }}
        </a>
    @endforeach
</div>

{{-- Tabel daftar katalog alat --}}
<div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Alat Camping</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3">Harga / Hari</th>
                    <th class="px-6 py-3">Stok Total</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($daftarAlat as $alat)
                    <tr class="transition hover:bg-zinc-50/70">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <x-gambar-alat :alat="$alat" kelas="h-11 w-16 shrink-0 rounded-lg border border-zinc-200/80" />
                                <div class="min-w-0">
                                    <p class="font-medium text-zinc-900">{{ $alat->nama_alat }}</p>
                                    <p class="max-w-xs truncate text-xs text-zinc-500">{{ $alat->deskripsi }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-600">{{ $alat->kategori->nama_kategori }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-zinc-900 tabular-nums">@rupiah($alat->harga_per_hari)</td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-700 tabular-nums">{{ $alat->stok_total }} unit</td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.alat.edit', $alat) }}" class="tombol-garis px-3 py-1.5 text-xs">
                                    <x-ikon nama="edit" kelas="h-3.5 w-3.5" />
                                    Edit
                                </a>
                                <form action="{{ route('admin.alat.destroy', $alat) }}" method="POST" onsubmit="return confirm('Hapus alat ini dari katalog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50">
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
