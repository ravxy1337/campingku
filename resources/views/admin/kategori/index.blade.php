@extends('layouts.dashboard')

@section('judul', 'Kategori Alat')

@section('konten')
@php
    $daftarKategori = \App\Models\KategoriAlat::withCount('alatCamping')
        ->orderBy('nama_kategori')
        ->get();
@endphp

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-zinc-600">Daftar kategori pengelompokan alat camping di katalog.</p>
    <a href="{{ route('admin.alat.index') }}" class="tombol-garis self-start sm:self-auto">
        Kelola Alat Katalog
    </a>
</div>

<div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Nama Kategori</th>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3">Jumlah Alat</th>
                    <th class="px-6 py-3">Ditambahkan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($daftarKategori as $kategori)
                    <tr class="transition hover:bg-zinc-50/70">
                        <td class="px-6 py-4 font-medium text-zinc-900">{{ $kategori->nama_kategori }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-zinc-500">{{ $kategori->slug }}</td>
                        <td class="px-6 py-4 text-zinc-700 tabular-nums">{{ $kategori->alat_camping_count }} item</td>
                        <td class="px-6 py-4 text-xs text-zinc-500 tabular-nums">{{ $kategori->created_at?->locale('id')->translatedFormat('d M Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-14 text-center text-sm text-zinc-500">Belum ada kategori alat yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
