@extends('layouts.dashboard')

@section('judul', 'Antrean Pesanan')

@section('konten')
@php
    $kartuStatistik = [
        'menunggu_acc' => ['Menunggu ACC', 'jam'],
        'siap_diambil' => ['Siap Diambil', 'toko'],
        'sedang_disewa' => ['Sedang Disewa', 'kubus'],
        'selesai' => ['Selesai', 'cek-lingkaran'],
    ];
@endphp

<p class="text-sm text-zinc-600">Verifikasi pesanan, serah terima alat, dan pengembalian di toko.</p>

{{-- Statistik antrean bersih --}}
<div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
    @foreach ($kartuStatistik as $kunciStatus => [$labelStatistik, $ikonStatistik])
        <a
            href="{{ route('petugas.antrean', ['status' => $kunciStatus]) }}"
            @class([
                'group rounded-xl border bg-white p-5 transition',
                'border-[#14532D] ring-1 ring-[#14532D]' => $filterStatus === $kunciStatus,
                'border-zinc-200 hover:border-zinc-300' => $filterStatus !== $kunciStatus,
            ])
        >
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-500">{{ $labelStatistik }}</span>
                <x-ikon :nama="$ikonStatistik" kelas="h-4 w-4 text-zinc-400 group-hover:text-zinc-600" />
            </div>
            <p class="mt-3 text-2xl font-semibold tracking-tight text-zinc-900 tabular-nums">{{ $ringkasanAntrean[$kunciStatus] }}</p>
        </a>
    @endforeach
</div>

{{-- Tabel antrean --}}
<div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="flex flex-col gap-3 border-b border-zinc-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-sm font-semibold text-zinc-900">
            {{ $filterStatus ? \App\Models\Penyewaan::LABEL_STATUS[$filterStatus] ?? 'Pesanan' : 'Semua Antrean Aktif' }}
        </h2>
        @if ($filterStatus)
            <a href="{{ route('petugas.antrean') }}" class="inline-flex items-center gap-1 text-xs font-medium text-zinc-500 hover:text-zinc-900">
                <x-ikon nama="silang" kelas="h-3.5 w-3.5" />
                Hapus filter
            </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Alat</th>
                    <th class="px-6 py-3">Jadwal</th>
                    <th class="px-6 py-3">Pembayaran</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($daftarAntrean as $item)
                    <tr class="transition hover:bg-zinc-50/70">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-zinc-900 text-xs font-semibold text-white">
                                    {{ strtoupper(substr($item->pelanggan->nama_lengkap, 0, 1)) }}
                                </span>
                                <div>
                                    <p class="font-medium text-zinc-900">{{ $item->pelanggan->nama_lengkap }}</p>
                                    <p class="font-mono text-xs text-zinc-500">{{ $item->kode_booking }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($item->detailPenyewaan as $detail)
                                <p class="max-w-[220px] truncate font-medium text-zinc-900">{{ $detail->alat->nama_alat ?? '-' }}</p>
                                <p class="text-xs text-zinc-500 tabular-nums">{{ $detail->jumlah }} unit</p>
                            @endforeach
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-600 tabular-nums">
                            {{ $item->tanggal_sewa->locale('id')->translatedFormat('d M') }} &ndash; {{ $item->tanggal_kembali->locale('id')->translatedFormat('d M') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 tabular-nums">
                            <p class="font-medium text-zinc-900">@rupiah($item->total_harga)</p>
                            <p class="text-xs text-zinc-500">{{ $item->tipe_pembayaran === 'dp' ? 'DP' : 'Lunas' }} &middot; dibayar @rupiah($item->jumlah_dibayar)</p>
                        </td>
                        <td class="px-6 py-4"><x-lencana-status :status="$item->status_sewa" /></td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('petugas.verifikasi.detail', $item) }}" class="tombol-garis whitespace-nowrap px-3 py-1.5 text-xs">
                                Proses
                                <x-ikon nama="panah-kanan" kelas="h-3.5 w-3.5" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-400">
                                <x-ikon nama="kotak-masuk" kelas="h-5 w-5" />
                            </span>
                            <p class="mt-3 font-medium text-zinc-900">Antrean kosong</p>
                            <p class="mt-1 text-sm text-zinc-500">Tidak ada pesanan pada status ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
