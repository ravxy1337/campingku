@props(['status'])

@php
    // Warna lencana per status
    $warna = match ($status) {
        'menunggu_acc', 'ditahan_di_toko' => 'bg-amber-50 text-amber-800 ring-amber-600/20',
        'ditolak', 'dibatalkan' => 'bg-red-50 text-red-700 ring-red-600/20',
        'siap_diambil', 'dikembalikan' => 'bg-emerald-50 text-emerald-900 ring-emerald-700/20',
        'sedang_disewa' => 'bg-zinc-900 text-white ring-zinc-900',
        'selesai' => 'bg-emerald-900 text-white ring-emerald-900',
        default => 'bg-stone-100 text-zinc-600 ring-zinc-500/20',
    };
@endphp

<span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $warna }}">
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    {{ \App\Models\Penyewaan::LABEL_STATUS[$status] ?? $status }}
</span>

