@props(['status'])

@php
    // Lencana status datar yang tenang dan terukur
    $warna = match ($status) {
        'menunggu_acc', 'ditahan_di_toko' => 'border-amber-200/80 bg-amber-50/70 text-amber-800',
        'ditolak', 'dibatalkan' => 'border-red-200/80 bg-red-50/70 text-red-700',
        'siap_diambil', 'dikembalikan' => 'border-emerald-200/80 bg-emerald-50/70 text-[#14532D]',
        'sedang_disewa' => 'border-zinc-300 bg-zinc-100 text-zinc-800',
        'selesai' => 'border-zinc-200 bg-zinc-50 text-zinc-600',
        default => 'border-zinc-200 bg-zinc-50 text-zinc-600',
    };
@endphp

<span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-md border px-2 py-0.5 text-xs font-medium {{ $warna }}">
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></span>
    {{ \App\Models\Penyewaan::LABEL_STATUS[$status] ?? $status }}
</span>
