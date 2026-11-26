@extends('layouts.dashboard')

@section('judul', 'Sewa Saya')

@section('konten')
@php
    $ringkasan = [
        ['Total Pesanan', $daftarPenyewaan->count(), 'daftar'],
        ['Sedang Berjalan', $daftarPenyewaan->whereIn('status_sewa', ['menunggu_acc', 'siap_diambil', 'sedang_disewa'])->count(), 'jam'],
        ['Selesai', $daftarPenyewaan->where('status_sewa', 'selesai')->count(), 'cek-lingkaran'],
    ];
@endphp

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-zinc-600">
        Halo, <span class="font-medium text-zinc-900">{{ strtok(auth()->user()->nama_lengkap, ' ') }}</span>. Pantau status pesanan dan jaminan KTP Anda di sini.
    </p>
    <a href="{{ route('beranda') }}" class="tombol-utama self-start sm:self-auto">
        <x-ikon nama="katalog" kelas="h-4 w-4" />
        Sewa Alat Baru
    </a>
</div>

{{-- Kartu statistik bersih --}}
<div class="mt-6 grid gap-4 sm:grid-cols-3">
    @foreach ($ringkasan as [$labelRingkas, $nilaiRingkas, $ikonRingkas])
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-500">{{ $labelRingkas }}</span>
                <x-ikon :nama="$ikonRingkas" kelas="h-4 w-4 text-zinc-400" />
            </div>
            <p class="mt-3 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums">{{ $nilaiRingkas }}</p>
        </div>
    @endforeach
</div>

{{-- Daftar penyewaan --}}
<div class="mt-6 space-y-4">
    @forelse ($daftarPenyewaan as $sewa)
        <article class="kartu overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-zinc-200/80 bg-[#FAFAFA] px-6 py-3.5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="font-mono text-sm font-medium text-zinc-900">{{ $sewa->kode_booking }}</span>
                    <span class="text-xs text-zinc-500">{{ $sewa->created_at->locale('id')->diffForHumans() }}</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <x-lencana-status :status="$sewa->status_sewa" />
                    <x-lencana-status :status="$sewa->status_jaminan_ktp" />
                </div>
            </div>

            <div class="grid gap-6 p-6 lg:grid-cols-12">
                <div class="space-y-4 lg:col-span-5">
                    @foreach ($sewa->detailPenyewaan as $detail)
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-600">
                                <x-ikon nama="tenda" kelas="h-4 w-4" />
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-zinc-900">{{ $detail->alat->nama_alat ?? 'Alat dihapus' }}</p>
                                <p class="text-xs text-zinc-500 tabular-nums">{{ $detail->jumlah }} unit &middot; @rupiah($detail->harga_satuan)/hari</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="flex items-center gap-2 text-sm text-zinc-600 tabular-nums">
                        <x-ikon nama="kalender" kelas="h-4 w-4 text-zinc-400" />
                        {{ $sewa->tanggal_sewa->locale('id')->translatedFormat('d M') }} &ndash; {{ $sewa->tanggal_kembali->locale('id')->translatedFormat('d M Y') }}
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <x-alur-sewa :status="$sewa->status_sewa" />
                </div>

                <div class="rounded-lg border border-zinc-200/80 bg-[#F7F8F7] p-4 lg:col-span-3">
                    <p class="text-xs text-zinc-500">Total ({{ $sewa->tipe_pembayaran === 'dp' ? 'DP 50%' : 'Lunas' }})</p>
                    <p class="mt-0.5 text-lg font-semibold text-zinc-900 tabular-nums">@rupiah($sewa->total_harga)</p>
                    <p class="mt-1 text-xs text-zinc-500">
                        Dibayar <span class="font-medium text-[#14532D] tabular-nums">@rupiah($sewa->jumlah_dibayar)</span>
                    </p>
                </div>
            </div>

            @if ($sewa->status_sewa === 'ditolak' && $sewa->catatan_penolakan)
                <div class="flex items-start gap-3 border-t border-red-200/80 bg-red-50/60 px-6 py-3.5 text-sm text-red-800">
                    <x-ikon nama="peringatan" kelas="mt-0.5 h-4 w-4 shrink-0" />
                    <div>
                        <p class="font-medium">Alasan penolakan</p>
                        <p class="mt-0.5 text-red-700">{{ $sewa->catatan_penolakan }}</p>
                    </div>
                </div>
            @elseif ($sewa->status_sewa === 'siap_diambil')
                <div class="flex items-start gap-3 border-t border-emerald-200/80 bg-emerald-50/50 px-6 py-3.5 text-sm text-[#14532D]">
                    <x-ikon nama="toko" kelas="mt-0.5 h-4 w-4 shrink-0" />
                    <p>Pesanan disetujui. Datang ke toko membawa <strong>KTP fisik asli</strong> untuk serah terima alat.</p>
                </div>
            @endif
        </article>
    @empty
        <div class="kartu flex flex-col items-center px-6 py-16 text-center">
            <span class="flex h-11 w-11 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-500">
                <x-ikon nama="tenda" kelas="h-5 w-5" />
            </span>
            <p class="mt-4 font-semibold text-zinc-900">Belum ada penyewaan</p>
            <p class="mt-1 max-w-sm text-sm text-zinc-500">Mulai perjalanan Anda dengan memilih perlengkapan camping dari katalog.</p>
            <a href="{{ route('beranda') }}" class="tombol-utama mt-6">Jelajahi Katalog</a>
        </div>
    @endforelse
</div>
@endsection
