@extends('layouts.publik')

@section('judul', $alatCamping->nama_alat)

@section('konten')
{{-- Breadcrumb --}}
<nav class="mb-6 flex items-center gap-2 text-xs font-medium text-zinc-500">
    <a href="{{ route('beranda') }}" class="transition hover:text-zinc-900">Katalog</a>
    <span>/</span>
    <a href="{{ route('beranda', ['kategori_id' => $alatCamping->kategori_id]) }}#katalog" class="transition hover:text-zinc-900">{{ $alatCamping->kategori->nama_kategori }}</a>
    <span>/</span>
    <span class="truncate text-zinc-900">{{ $alatCamping->nama_alat }}</span>
</nav>

<div class="grid gap-8 lg:grid-cols-12">
    {{-- Informasi alat --}}
    <div class="space-y-6 lg:col-span-7">
        <x-gambar-alat :alat="$alatCamping" kelas="aspect-[16/10]" class="rounded-xl border border-zinc-200/80">
            <div class="absolute left-4 top-4">
                <x-ketersediaan :sisa="$sisaHariIni" />
            </div>
        </x-gambar-alat>

        <div class="kartu p-6 sm:p-8">
            <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-500">{{ $alatCamping->kategori->nama_kategori }}</p>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900 sm:text-3xl">{{ $alatCamping->nama_alat }}</h1>
            <p class="mt-4 text-sm leading-relaxed text-zinc-600 sm:text-base">{{ $alatCamping->deskripsi }}</p>

            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div class="rounded-lg border border-zinc-200/80 bg-[#F7F8F7] p-4">
                    <p class="text-xs text-zinc-500">Tarif harian</p>
                    <p class="mt-1 text-base font-semibold text-zinc-900 tabular-nums">@rupiah($alatCamping->harga_per_hari)</p>
                </div>
                <div class="rounded-lg border border-zinc-200/80 bg-[#F7F8F7] p-4">
                    <p class="text-xs text-zinc-500">Total unit</p>
                    <p class="mt-1 text-base font-semibold text-zinc-900 tabular-nums">{{ $alatCamping->stok_total }} unit</p>
                </div>
                <div class="col-span-2 rounded-lg border border-zinc-200/80 bg-[#F7F8F7] p-4 sm:col-span-1">
                    <p class="text-xs text-zinc-500">Sisa hari ini</p>
                    <p class="mt-1 text-base font-semibold text-zinc-900 tabular-nums">{{ $sisaHariIni }} unit</p>
                </div>
            </div>

            <div class="mt-8 border-t border-zinc-200/80 pt-6">
                <h2 class="text-sm font-semibold text-zinc-900">Kelengkapan standar</h2>
                <p class="mt-1 text-xs text-zinc-500">Wajib dikembalikan lengkap dan dalam kondisi baik.</p>
                <ul class="mt-4 grid gap-2.5 sm:grid-cols-2">
                    @foreach (explode(',', $alatCamping->kelengkapan_standar) as $kelengkapan)
                        <li class="flex items-start gap-2.5 text-sm text-zinc-700">
                            <x-ikon nama="cek" kelas="mt-0.5 h-4 w-4 shrink-0 text-[#14532D]" />
                            <span>{{ trim($kelengkapan) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Simulasi ketersediaan --}}
    <div class="lg:col-span-5">
        <div class="kartu p-6 lg:sticky lg:top-24">
            <h2 class="text-lg font-semibold tracking-tight text-zinc-900">Cek Ketersediaan</h2>
            <p class="mt-1 text-sm text-zinc-500">Pilih tanggal dan jumlah unit untuk melihat stok dan estimasi biaya.</p>

            <form action="{{ route('alat.detail', $alatCamping) }}" method="GET" class="mt-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="tanggal_sewa" class="label-input">Tgl Ambil</label>
                        <input type="date" id="tanggal_sewa" name="tanggal_sewa" value="{{ request('tanggal_sewa', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required class="kolom-input tabular-nums">
                    </div>
                    <div>
                        <label for="tanggal_kembali" class="label-input">Tgl Kembali</label>
                        <input type="date" id="tanggal_kembali" name="tanggal_kembali" value="{{ request('tanggal_kembali', now()->addDay()->toDateString()) }}" min="{{ now()->addDay()->toDateString() }}" required class="kolom-input tabular-nums">
                    </div>
                </div>
                <div>
                    <label for="jumlah" class="label-input">Jumlah unit</label>
                    <input type="number" id="jumlah" name="jumlah" value="{{ request('jumlah', 1) }}" min="1" max="{{ max(1, $alatCamping->stok_total) }}" required class="kolom-input tabular-nums">
                </div>
                <button type="submit" class="tombol-gelap w-full py-2.5">
                    <x-ikon nama="kalender" kelas="h-4 w-4" />
                    Cek Ketersediaan
                </button>
            </form>

            {{-- Hasil simulasi --}}
            @if ($hasilSimulasi)
                <div class="mt-6 border-t border-zinc-200/80 pt-6">
                    <div @class([
                        'flex items-center gap-2.5 rounded-lg border px-3.5 py-2.5 text-sm font-medium',
                        'border-emerald-200/80 bg-emerald-50/60 text-[#14532D]' => $hasilSimulasi['cukup'],
                        'border-red-200/80 bg-red-50/70 text-red-700' => ! $hasilSimulasi['cukup'],
                    ])>
                        <x-ikon :nama="$hasilSimulasi['cukup'] ? 'cek-lingkaran' : 'peringatan'" kelas="h-4 w-4 shrink-0" />
                        {{ $hasilSimulasi['cukup'] ? 'Stok tersedia untuk tanggal ini' : 'Stok tidak mencukupi untuk tanggal ini' }}
                    </div>

                    <dl class="mt-5 space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">Durasi sewa</dt>
                            <dd class="font-medium text-zinc-900 tabular-nums">{{ $hasilSimulasi['durasi_hari'] }} hari</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">Stok tersedia</dt>
                            <dd class="font-medium text-zinc-900 tabular-nums">{{ $hasilSimulasi['stok_tersedia'] }} unit</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">Unit diminta</dt>
                            <dd class="font-medium text-zinc-900 tabular-nums">{{ $hasilSimulasi['jumlah_diminta'] }} unit</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-zinc-200/80 pt-3">
                            <dt class="font-semibold text-zinc-900">Estimasi total</dt>
                            <dd class="text-xl font-semibold text-[#14532D] tabular-nums">@rupiah($hasilSimulasi['total_biaya'])</dd>
                        </div>
                    </dl>

                    @if ($hasilSimulasi['cukup'])
                        <a href="{{ route('pelanggan.checkout', ['alatCamping' => $alatCamping, 'tanggal_sewa' => $hasilSimulasi['tanggal_sewa'], 'tanggal_kembali' => $hasilSimulasi['tanggal_kembali'], 'jumlah' => $hasilSimulasi['jumlah_diminta']]) }}" class="tombol-utama mt-5 w-full py-3">
                            Sewa Sekarang
                            <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
                        </a>
                        @guest
                            <p class="mt-3 text-center text-xs text-zinc-500">Anda akan diminta masuk terlebih dahulu sebelum melanjutkan checkout.</p>
                        @endguest
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
