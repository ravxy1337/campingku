@extends('layouts.dashboard')

@section('judul', 'Sewa Saya')

@section('konten')
@php
    // Ringkasan jumlah penyewaan
    $ringkasan = [
        ['Total Pesanan', $daftarPenyewaan->count(), 'daftar'],
        ['Sedang Berjalan', $daftarPenyewaan->whereIn('status_sewa', ['menunggu_acc', 'siap_diambil', 'sedang_disewa'])->count(), 'jam'],
        ['Selesai', $daftarPenyewaan->where('status_sewa', 'selesai')->count(), 'cek-lingkaran'],
    ];
@endphp

{{-- Kepala halaman --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Halo, {{ strtok(auth()->user()->nama_lengkap, ' ') }}</h1>
        <p class="mt-1 text-sm text-zinc-500">Pantau status pesanan dan jaminan KTP Anda di sini.</p>
    </div>
    <a href="{{ route('beranda') }}" class="tombol-utama">
        <x-ikon nama="katalog" kelas="h-4 w-4" />
        Sewa Alat Baru
    </a>
</div>

{{-- Kartu ringkasan --}}
<div class="mt-8 grid gap-4 sm:grid-cols-3">
    @foreach ($ringkasan as [$labelRingkas, $nilaiRingkas, $ikonRingkas])
        <div class="kartu flex items-center gap-4 p-5">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-900"><x-ikon :nama="$ikonRingkas" /></span>
            <div>
                <p class="text-sm text-zinc-500">{{ $labelRingkas }}</p>
                <p class="text-2xl font-bold text-zinc-900">{{ $nilaiRingkas }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- Daftar penyewaan --}}
<div class="mt-8 space-y-4">
    @forelse ($daftarPenyewaan as $sewa)
        <article class="kartu overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-stone-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="font-mono text-sm font-medium text-zinc-900">{{ $sewa->kode_booking }}</span>
                    <span class="text-xs text-zinc-400">{{ $sewa->created_at->diffForHumans() }}</span>
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
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-stone-100 text-zinc-600"><x-ikon nama="tenda" /></span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-zinc-900">{{ $detail->alat->nama_alat ?? 'Alat dihapus' }}</p>
                                <p class="text-xs text-zinc-500">{{ $detail->jumlah }} unit &middot; @rupiah($detail->harga_satuan)/hari</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="flex items-center gap-2 text-sm text-zinc-600">
                        <x-ikon nama="kalender" kelas="h-4 w-4 text-zinc-400" />
                        {{ $sewa->tanggal_sewa->translatedFormat('d M') }} &ndash; {{ $sewa->tanggal_kembali->translatedFormat('d M Y') }}
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <x-alur-sewa :status="$sewa->status_sewa" />
                </div>

                <div class="rounded-xl bg-stone-50 p-4 ring-1 ring-stone-200 lg:col-span-3">
                    <p class="text-xs text-zinc-500">Total ({{ $sewa->tipe_pembayaran === 'dp' ? 'DP 50%' : 'Lunas' }})</p>
                    <p class="text-lg font-bold text-zinc-900">@rupiah($sewa->total_harga)</p>
                    <p class="mt-1 text-xs text-zinc-500">Dibayar <span class="font-semibold text-emerald-900">@rupiah($sewa->jumlah_dibayar)</span></p>
                </div>
            </div>

            {{-- Pesan kontekstual per status --}}
            @if ($sewa->status_sewa === 'ditolak' && $sewa->catatan_penolakan)
                <div class="flex items-start gap-3 border-t border-red-100 bg-red-50 px-6 py-4 text-sm text-red-800">
                    <x-ikon nama="peringatan" kelas="mt-0.5 h-5 w-5 shrink-0" />
                    <div><p class="font-semibold">Alasan penolakan</p><p class="mt-0.5 text-red-700">{{ $sewa->catatan_penolakan }}</p></div>
                </div>
            @elseif ($sewa->status_sewa === 'siap_diambil')
                <div class="flex items-start gap-3 border-t border-emerald-100 bg-emerald-50 px-6 py-4 text-sm text-emerald-900">
                    <x-ikon nama="toko" kelas="mt-0.5 h-5 w-5 shrink-0" />
                    <p>Pesanan disetujui. Datang ke toko membawa <strong>KTP fisik asli</strong> untuk serah terima alat.</p>
                </div>
            @endif
        </article>
    @empty
        <div class="kartu flex flex-col items-center px-6 py-16 text-center">
            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-900"><x-ikon nama="tenda" kelas="h-7 w-7" /></span>
            <p class="mt-4 font-semibold text-zinc-900">Belum ada penyewaan</p>
            <p class="mt-1 max-w-sm text-sm text-zinc-500">Mulai petualangan pertamamu dengan memilih alat dari katalog.</p>
            <a href="{{ route('beranda') }}" class="tombol-utama mt-6">Jelajahi Katalog</a>
        </div>
    @endforelse
</div>
@endsection
