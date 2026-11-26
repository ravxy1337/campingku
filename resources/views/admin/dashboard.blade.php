@extends('layouts.dashboard')

@section('judul', 'Dashboard Admin')

@section('konten')
{{-- Kepala halaman --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-zinc-500">Ringkasan data katalog dan transaksi penyewaan.</p>
    </div>
    <a href="{{ route('admin.alat.create') }}" class="tombol-utama">
        <x-ikon nama="tambah" kelas="h-4 w-4" />
        Tambah Alat Katalog
    </a>
</div>

{{-- Kartu statistik aktual --}}
<div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="kartu p-5">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-900"><x-ikon nama="tenda" /></span>
        <p class="mt-4 text-3xl font-bold text-zinc-900">{{ $statistik['total_alat'] }}</p>
        <p class="text-sm text-zinc-500">Total Alat Camping</p>
    </div>
    <div class="kartu p-5">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-stone-100 text-zinc-700"><x-ikon nama="tag" /></span>
        <p class="mt-4 text-3xl font-bold text-zinc-900">{{ $statistik['total_kategori'] }}</p>
        <p class="text-sm text-zinc-500">Kategori Alat</p>
    </div>
    <div class="kartu p-5">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700"><x-ikon nama="jam" /></span>
        <p class="mt-4 text-3xl font-bold text-zinc-900">{{ $statistik['penyewaan_aktif'] }}</p>
        <p class="text-sm text-zinc-500">Penyewaan Aktif</p>
    </div>
    <div class="kartu p-5">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-zinc-900 text-white"><x-ikon nama="uang" /></span>
        <p class="mt-4 text-2xl font-bold text-zinc-900">@rupiah($statistik['total_pendapatan'])</p>
        <p class="text-sm text-zinc-500">Total Pembayaran Masuk</p>
    </div>
</div>
@endsection
