@extends('layouts.dashboard')

@section('judul', 'Dashboard Admin')

@section('konten')
@php
    $transaksiTerbaru = \App\Models\Penyewaan::with(['pelanggan', 'detailPenyewaan.alat'])
        ->latest()
        ->take(5)
        ->get();

    $daftarKpi = [
        ['Total Alat Camping', $statistik['total_alat'], 'tenda'],
        ['Kategori Alat', $statistik['total_kategori'], 'tag'],
        ['Penyewaan Aktif', $statistik['penyewaan_aktif'], 'jam'],
        ['Total Pembayaran Masuk', null, 'uang', $statistik['total_pendapatan']],
    ];
@endphp

{{-- Subheader aksi tanpa duplikasi judul halaman --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-zinc-600">Ringkasan data katalog, penyewaan aktif, dan pendapatan masuk.</p>
    <div class="flex items-center gap-2.5">
        <a href="{{ route('petugas.antrean') }}" class="tombol-garis">
            Antrean Verifikasi
        </a>
        <a href="{{ route('admin.alat.create') }}" class="tombol-utama">
            <x-ikon nama="tambah" kelas="h-4 w-4" />
            Tambah Alat Katalog
        </a>
    </div>
</div>

{{-- Kartu KPI minimalis tanpa kotak ikon pastel --}}
<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($daftarKpi as $kpi)
        <div class="rounded-xl border border-[#E4E4E7] bg-white p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-500">{{ $kpi[0] }}</span>
                <x-ikon :nama="$kpi[2]" kelas="h-4 w-4 text-zinc-400" />
            </div>
            <p class="mt-3 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums">
                @if (isset($kpi[3]))
                    @rupiah($kpi[3])
                @else
                    {{ $kpi[1] }}
                @endif
            </p>
        </div>
    @endforeach
</div>

{{-- Tabel transaksi terbaru --}}
<div class="mt-8 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="flex items-center justify-between border-b border-zinc-200 px-6 py-4">
        <div>
            <h2 class="text-sm font-semibold text-zinc-900">Transaksi Penyewaan Terbaru</h2>
            <p class="mt-0.5 text-xs text-zinc-500">Menampilkan hingga 5 pengajuan sewa terbaru.</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-600 transition hover:text-zinc-900">
            Lihat Semua
            <x-ikon nama="panah-kanan" kelas="h-3.5 w-3.5" />
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Kode Booking</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Perlengkapan</th>
                    <th class="px-6 py-3">Jadwal Sewa</th>
                    <th class="px-6 py-3">Dibayar</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($transaksiTerbaru as $sewa)
                    <tr class="transition hover:bg-zinc-50/70">
                        <td class="whitespace-nowrap px-6 py-3.5 font-mono text-xs font-medium text-zinc-900">
                            <a href="{{ route('petugas.verifikasi.detail', $sewa) }}" class="hover:text-[#14532D] hover:underline">
                                {{ $sewa->kode_booking }}
                            </a>
                        </td>
                        <td class="whitespace-nowrap px-6 py-3.5 text-zinc-800">{{ $sewa->pelanggan->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-3.5 text-zinc-600">
                            @foreach ($sewa->detailPenyewaan as $detail)
                                <span class="block max-w-[220px] truncate">{{ $detail->alat->nama_alat ?? '-' }} ({{ $detail->jumlah }}x)</span>
                            @endforeach
                        </td>
                        <td class="whitespace-nowrap px-6 py-3.5 text-xs text-zinc-600 tabular-nums">
                            {{ $sewa->tanggal_sewa->locale('id')->translatedFormat('d M') }} &ndash; {{ $sewa->tanggal_kembali->locale('id')->translatedFormat('d M Y') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-3.5 font-medium text-zinc-900 tabular-nums">@rupiah($sewa->jumlah_dibayar)</td>
                        <td class="whitespace-nowrap px-6 py-3.5"><x-lencana-status :status="$sewa->status_sewa" /></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-zinc-500">
                            Belum ada transaksi penyewaan yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
