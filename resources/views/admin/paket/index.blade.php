@extends('layouts.dashboard')

@section('judul', 'Paket Sewa')

@section('konten')
@php
    $daftarPaket = \App\Models\PaketSewa::with('detailPaket.alat')->latest()->get();
@endphp

<p class="text-sm text-zinc-600">Daftar bundel paket penyewaan perlengkapan camping.</p>

<div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Nama Paket</th>
                    <th class="px-6 py-3">Isi Perlengkapan</th>
                    <th class="px-6 py-3">Harga Paket</th>
                    <th class="px-6 py-3">Dibuat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($daftarPaket as $paket)
                    <tr class="transition hover:bg-zinc-50/70">
                        <td class="px-6 py-4">
                            <p class="font-medium text-zinc-900">{{ $paket->nama_paket }}</p>
                            <p class="max-w-xs truncate text-xs text-zinc-500">{{ $paket->deskripsi }}</p>
                        </td>
                        <td class="px-6 py-4 text-zinc-600">
                            @forelse ($paket->detailPaket as $detail)
                                <span class="block text-xs">{{ $detail->alat->nama_alat ?? '-' }} ({{ $detail->jumlah_dalam_paket }}x)</span>
                            @empty
                                <span class="text-xs text-zinc-400">-</span>
                            @endforelse
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-zinc-900 tabular-nums">@rupiah($paket->harga_paket)</td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-zinc-500 tabular-nums">{{ $paket->created_at?->locale('id')->translatedFormat('d M Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-14 text-center text-sm text-zinc-500">Belum ada paket sewa yang tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
