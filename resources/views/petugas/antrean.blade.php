@extends('layouts.dashboard')

@section('judul', 'Antrean Pesanan')

@section('konten')
@php
    // Kartu statistik antrean
    $kartuStatistik = [
        'menunggu_acc' => ['Menunggu ACC', 'jam', 'bg-amber-50 text-amber-700'],
        'siap_diambil' => ['Siap Diambil', 'toko', 'bg-emerald-50 text-emerald-900'],
        'sedang_disewa' => ['Sedang Disewa', 'kubus', 'bg-zinc-100 text-zinc-900'],
        'selesai' => ['Selesai', 'cek-lingkaran', 'bg-stone-100 text-zinc-600'],
    ];
@endphp

{{-- Kepala halaman --}}
<div>
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Antrean Pesanan</h1>
    <p class="mt-1 text-sm text-zinc-500">Verifikasi pesanan, serah terima alat, dan pengembalian di toko.</p>
</div>

{{-- Statistik --}}
<div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
    @foreach ($kartuStatistik as $kunciStatus => [$labelStatistik, $ikonStatistik, $warnaStatistik])
        <a href="{{ route('petugas.antrean', ['status' => $kunciStatus]) }}" @class(['kartu group p-5 transition hover:ring-zinc-300', 'ring-2 ring-emerald-900 hover:ring-emerald-900' => $filterStatus === $kunciStatus])>
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $warnaStatistik }}"><x-ikon :nama="$ikonStatistik" /></span>
                <x-ikon nama="panah-kanan" kelas="h-4 w-4 text-zinc-300 transition group-hover:translate-x-0.5 group-hover:text-zinc-500" />
            </div>
            <p class="mt-4 text-3xl font-bold text-zinc-900">{{ $ringkasanAntrean[$kunciStatus] }}</p>
            <p class="text-sm text-zinc-500">{{ $labelStatistik }}</p>
        </a>
    @endforeach
</div>

{{-- Tabel antrean --}}
<div class="kartu mt-8 overflow-hidden">
    <div class="flex flex-col gap-3 border-b border-stone-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="font-semibold text-zinc-900">{{ $filterStatus ? \App\Models\Penyewaan::LABEL_STATUS[$filterStatus] ?? 'Pesanan' : 'Semua Antrean Aktif' }}</h2>
        @if ($filterStatus)
            <a href="{{ route('petugas.antrean') }}" class="inline-flex items-center gap-1 text-sm font-medium text-zinc-500 hover:text-zinc-900"><x-ikon nama="silang" kelas="h-4 w-4" /> Hapus filter</a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-xs font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Alat</th>
                    <th class="px-6 py-3">Jadwal</th>
                    <th class="px-6 py-3">Pembayaran</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($daftarAntrean as $item)
                    <tr class="transition hover:bg-stone-50/60">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-xs font-semibold text-white">{{ strtoupper(substr($item->pelanggan->nama_lengkap, 0, 1)) }}</span>
                                <div>
                                    <p class="font-medium text-zinc-900">{{ $item->pelanggan->nama_lengkap }}</p>
                                    <p class="font-mono text-xs text-zinc-500">{{ $item->kode_booking }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($item->detailPenyewaan as $detail)
                                <p class="max-w-[220px] truncate text-zinc-900">{{ $detail->alat->nama_alat ?? '-' }}</p>
                                <p class="text-xs text-zinc-500">{{ $detail->jumlah }} unit</p>
                            @endforeach
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-600">{{ $item->tanggal_sewa->translatedFormat('d M') }} &ndash; {{ $item->tanggal_kembali->translatedFormat('d M') }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <p class="font-medium text-zinc-900">@rupiah($item->total_harga)</p>
                            <p class="text-xs text-zinc-500">{{ $item->tipe_pembayaran === 'dp' ? 'DP' : 'Lunas' }} &middot; dibayar @rupiah($item->jumlah_dibayar)</p>
                        </td>
                        <td class="px-6 py-4"><x-lencana-status :status="$item->status_sewa" /></td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('petugas.verifikasi.detail', $item) }}" class="tombol-garis whitespace-nowrap px-3.5 py-2">
                                Proses
                                <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-stone-100 text-zinc-400"><x-ikon nama="kotak-masuk" /></span>
                            <p class="mt-4 font-semibold text-zinc-900">Antrean kosong</p>
                            <p class="mt-1 text-sm text-zinc-500">Tidak ada pesanan pada status ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
