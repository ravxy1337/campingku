@extends('layouts.dashboard')

@section('judul', 'Laporan Transaksi')

@section('konten')
@php
    $daftarTransaksi = \App\Models\Penyewaan::with(['pelanggan', 'detailPenyewaan.alat'])
        ->latest()
        ->get();

    $totalTransaksi = $daftarTransaksi->count();
    $transaksiSelesai = $daftarTransaksi->where('status_sewa', 'selesai')->count();
    $totalPemasukan = $daftarTransaksi
        ->whereIn('status_sewa', ['siap_diambil', 'sedang_disewa', 'selesai'])
        ->sum('jumlah_dibayar');
@endphp

<p class="text-sm text-zinc-600">Rekap seluruh transaksi penyewaan dan status pembayaran pelanggan.</p>

<div class="mt-6 grid gap-4 sm:grid-cols-3">
    <div class="rounded-xl border border-[#E4E4E7] bg-white p-5">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-zinc-500">Total Transaksi</span>
            <x-ikon nama="laporan" kelas="h-4 w-4 text-zinc-400" />
        </div>
        <p class="mt-3 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums">{{ $totalTransaksi }}</p>
    </div>
    <div class="rounded-xl border border-[#E4E4E7] bg-white p-5">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-zinc-500">Transaksi Selesai</span>
            <x-ikon nama="cek-lingkaran" kelas="h-4 w-4 text-zinc-400" />
        </div>
        <p class="mt-3 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums">{{ $transaksiSelesai }}</p>
    </div>
    <div class="rounded-xl border border-[#E4E4E7] bg-white p-5">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-zinc-500">Total Pembayaran Masuk</span>
            <x-ikon nama="uang" kelas="h-4 w-4 text-zinc-400" />
        </div>
        <p class="mt-3 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums">@rupiah($totalPemasukan)</p>
    </div>
</div>

<div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Kode Booking</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Alat</th>
                    <th class="px-6 py-3">Jadwal</th>
                    <th class="px-6 py-3">Pembayaran</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($daftarTransaksi as $sewa)
                    <tr class="transition hover:bg-zinc-50/70">
                        <td class="whitespace-nowrap px-6 py-4 font-mono text-xs font-medium text-zinc-900">{{ $sewa->kode_booking }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-800">{{ $sewa->pelanggan->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4 text-zinc-600">
                            @foreach ($sewa->detailPenyewaan as $detail)
                                <span class="block max-w-[200px] truncate">{{ $detail->alat->nama_alat ?? '-' }} ({{ $detail->jumlah }}x)</span>
                            @endforeach
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-zinc-600 tabular-nums">
                            {{ $sewa->tanggal_sewa->locale('id')->translatedFormat('d M') }} &ndash; {{ $sewa->tanggal_kembali->locale('id')->translatedFormat('d M Y') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 tabular-nums">
                            <p class="font-medium text-zinc-900">@rupiah($sewa->jumlah_dibayar)</p>
                            <p class="text-xs text-zinc-500">{{ strtoupper($sewa->tipe_pembayaran) }} &middot; Total @rupiah($sewa->total_harga)</p>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4"><x-lencana-status :status="$sewa->status_sewa" /></td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <a href="{{ route('petugas.verifikasi.detail', $sewa) }}" class="tombol-garis px-3 py-1.5 text-xs">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-14 text-center text-sm text-zinc-500">Belum ada transaksi penyewaan yang tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
