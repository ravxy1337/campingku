@extends('layouts.dashboard')

@section('judul', 'Manajemen Pengguna')

@section('konten')
@php
    $daftarPengguna = \App\Models\User::orderByRaw("CASE peran WHEN 'admin' THEN 1 WHEN 'petugas' THEN 2 ELSE 3 END")
        ->orderBy('nama_lengkap')
        ->get();
@endphp

<p class="text-sm text-zinc-600">Daftar akun admin, petugas toko, dan pelanggan terdaftar.</p>

<div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                <tr>
                    <th class="px-6 py-3">Pengguna</th>
                    <th class="px-6 py-3">No. HP</th>
                    <th class="px-6 py-3">Peran</th>
                    <th class="px-6 py-3">NIK</th>
                    <th class="px-6 py-3">Terdaftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($daftarPengguna as $akun)
                    <tr class="transition hover:bg-zinc-50/70">
                        <td class="px-6 py-4">
                            <p class="font-medium text-zinc-900">{{ $akun->nama_lengkap }}</p>
                            <p class="text-xs text-zinc-500">{{ $akun->email }}</p>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-zinc-600 tabular-nums">{{ $akun->no_hp ?? '-' }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span @class([
                                'inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium capitalize',
                                'border-emerald-200/80 bg-emerald-50/70 text-[#14532D]' => $akun->peran === 'admin',
                                'border-zinc-300 bg-zinc-100 text-zinc-800' => $akun->peran === 'petugas',
                                'border-zinc-200 bg-zinc-50 text-zinc-600' => $akun->peran === 'pelanggan',
                            ])>
                                {{ $akun->peran }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-zinc-600">{{ $akun->nik ?? '-' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-xs text-zinc-500 tabular-nums">{{ $akun->created_at?->locale('id')->translatedFormat('d M Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center text-sm text-zinc-500">Belum ada pengguna terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
