@extends('layouts.dashboard')

@section('judul', 'Kelola Denda')

@section('konten')
@php
    $daftarDenda = \App\Models\Denda::with('penyewaan.pelanggan')->latest()->get();
@endphp

<p class="text-sm text-zinc-600">Catatan denda keterlambatan pengembalian atau kerusakan perlengkapan.</p>

<div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Kode Booking</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Jenis Denda</th>
                    <th class="px-6 py-3">Keterangan</th>
                    <th class="px-6 py-3">Nominal</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($daftarDenda as $denda)
                    <tr class="transition hover:bg-zinc-50/70">
                        <td class="whitespace-nowrap px-6 py-4 font-mono text-xs font-medium text-zinc-900">{{ $denda->penyewaan->kode_booking ?? '-' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-800">{{ $denda->penyewaan->pelanggan->nama_lengkap ?? '-' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 capitalize text-zinc-600">{{ $denda->jenis_denda }}</td>
                        <td class="px-6 py-4 text-zinc-600">{{ $denda->keterangan }}</td>
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-zinc-900 tabular-nums">@rupiah($denda->nominal_denda)</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span @class([
                                'inline-flex items-center gap-1.5 rounded-md border px-2 py-0.5 text-xs font-medium',
                                'border-emerald-200/80 bg-emerald-50/70 text-[#14532D]' => $denda->status_bayar === 'lunas',
                                'border-amber-200/80 bg-amber-50/70 text-amber-800' => $denda->status_bayar !== 'lunas',
                            ])>
                                {{ $denda->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-14 text-center text-sm text-zinc-500">Belum ada catatan denda keterlambatan atau kerusakan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
