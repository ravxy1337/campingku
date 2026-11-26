@extends('layouts.publik')

@section('judul', $alatCamping->nama_alat)

@section('konten')
{{-- Breadcrumb --}}
<nav class="mb-6 flex items-center gap-2 text-sm text-zinc-500">
    <a href="{{ route('beranda') }}" class="hover:text-zinc-900">Katalog</a>
    <span>/</span>
    <a href="{{ route('beranda', ['kategori_id' => $alatCamping->kategori_id]) }}" class="hover:text-zinc-900">{{ $alatCamping->kategori->nama_kategori }}</a>
    <span>/</span>
    <span class="truncate font-medium text-zinc-900">{{ $alatCamping->nama_alat }}</span>
</nav>

<div class="grid gap-8 lg:grid-cols-12">
    {{-- Informasi alat --}}
    <div class="space-y-6 lg:col-span-7">
        <x-gambar-alat :alat="$alatCamping" kelas="aspect-[16/10]" class="rounded-3xl">
            <div class="absolute left-5 top-5"><x-ketersediaan :sisa="$sisaHariIni" /></div>
        </x-gambar-alat>

        <div class="kartu p-6 sm:p-8">
            <p class="text-xs font-medium uppercase tracking-wider text-emerald-900">{{ $alatCamping->kategori->nama_kategori }}</p>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">{{ $alatCamping->nama_alat }}</h1>
            <p class="mt-4 leading-relaxed text-zinc-600">{{ $alatCamping->deskripsi }}</p>

            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div class="rounded-2xl bg-stone-50 p-4 ring-1 ring-stone-200">
                    <p class="text-xs text-zinc-500">Tarif harian</p>
                    <p class="mt-1 font-bold text-zinc-900">@rupiah($alatCamping->harga_per_hari)</p>
                </div>
                <div class="rounded-2xl bg-stone-50 p-4 ring-1 ring-stone-200">
                    <p class="text-xs text-zinc-500">Total unit</p>
                    <p class="mt-1 font-bold text-zinc-900">{{ $alatCamping->stok_total }} unit</p>
                </div>
                <div class="col-span-2 rounded-2xl bg-stone-50 p-4 ring-1 ring-stone-200 sm:col-span-1">
                    <p class="text-xs text-zinc-500">Sisa hari ini</p>
                    <p class="mt-1 font-bold text-zinc-900">{{ $sisaHariIni }} unit</p>
                </div>
            </div>

            <h2 class="mt-8 text-sm font-semibold text-zinc-900">Kelengkapan standar</h2>
            <p class="mt-1 text-xs text-zinc-500">Wajib dikembalikan lengkap dan dalam kondisi baik.</p>
            <ul class="mt-4 grid gap-2.5 sm:grid-cols-2">
                @foreach (explode(',', $alatCamping->kelengkapan_standar) as $kelengkapan)
                    <li class="flex items-start gap-2.5 text-sm text-zinc-700">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-900"><x-ikon nama="cek" kelas="h-3 w-3" /></span>
                        {{ trim($kelengkapan) }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Simulasi ketersediaan --}}
    <div class="lg:col-span-5">
        <div class="kartu p-6 lg:sticky lg:top-24">
            <h2 class="text-lg font-bold text-zinc-900">Cek ketersediaan</h2>
            <p class="mt-1 text-sm text-zinc-500">Pilih tanggal dan jumlah unit untuk melihat stok dan estimasi biaya.</p>

            <form action="{{ route('alat.detail', $alatCamping) }}" method="GET" class="mt-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="tanggal_sewa" class="label-input">Ambil</label>
                        <input type="date" id="tanggal_sewa" name="tanggal_sewa" value="{{ request('tanggal_sewa', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required class="kolom-input">
                    </div>
                    <div>
                        <label for="tanggal_kembali" class="label-input">Kembali</label>
                        <input type="date" id="tanggal_kembali" name="tanggal_kembali" value="{{ request('tanggal_kembali', now()->addDay()->toDateString()) }}" min="{{ now()->addDay()->toDateString() }}" required class="kolom-input">
                    </div>
                </div>
                <div>
                    <label for="jumlah" class="label-input">Jumlah unit</label>
                    <input type="number" id="jumlah" name="jumlah" value="{{ request('jumlah', 1) }}" min="1" max="{{ $alatCamping->stok_total }}" required class="kolom-input">
                </div>
                <button type="submit" class="tombol-gelap w-full py-3">
                    <x-ikon nama="kalender" kelas="h-4 w-4" />
                    Cek Ketersediaan
                </button>
            </form>

            {{-- Hasil simulasi --}}
            @if ($hasilSimulasi)
                <div class="mt-6 border-t border-dashed border-stone-200 pt-6">
                    <div @class([
                        'flex items-center gap-3 rounded-xl p-3.5 text-sm font-medium ring-1 ring-inset',
                        'bg-emerald-50 text-emerald-900 ring-emerald-900/10' => $hasilSimulasi['cukup'],
                        'bg-red-50 text-red-700 ring-red-600/10' => ! $hasilSimulasi['cukup'],
                    ])>
                        <x-ikon :nama="$hasilSimulasi['cukup'] ? 'cek-lingkaran' : 'peringatan'" />
                        {{ $hasilSimulasi['cukup'] ? 'Stok tersedia untuk tanggal ini' : 'Stok tidak mencukupi untuk tanggal ini' }}
                    </div>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between"><dt class="text-zinc-500">Durasi</dt><dd class="font-medium text-zinc-900">{{ $hasilSimulasi['durasi_hari'] }} hari</dd></div>
                        <div class="flex justify-between"><dt class="text-zinc-500">Stok tersedia</dt><dd class="font-medium text-zinc-900">{{ $hasilSimulasi['stok_tersedia'] }} unit</dd></div>
                        <div class="flex justify-between"><dt class="text-zinc-500">Diminta</dt><dd class="font-medium text-zinc-900">{{ $hasilSimulasi['jumlah_diminta'] }} unit</dd></div>
                        <div class="flex items-center justify-between border-t border-stone-200 pt-3"><dt class="font-semibold text-zinc-900">Total biaya</dt><dd class="text-xl font-bold text-emerald-900">@rupiah($hasilSimulasi['total_biaya'])</dd></div>
                    </dl>

                    @if ($hasilSimulasi['cukup'])
                        <a href="{{ route('pelanggan.checkout', ['alatCamping' => $alatCamping, 'tanggal_sewa' => $hasilSimulasi['tanggal_sewa'], 'tanggal_kembali' => $hasilSimulasi['tanggal_kembali'], 'jumlah' => $hasilSimulasi['jumlah_diminta']]) }}" class="tombol-utama mt-6 w-full py-3">
                            Sewa Sekarang
                            <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
                        </a>
                        @guest
                            <p class="mt-3 text-center text-xs text-zinc-500">Anda akan diminta masuk terlebih dahulu.</p>
                        @endguest
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
