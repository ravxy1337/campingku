@props(['sisa'])

{{-- Indikator ketersediaan stok hari ini --}}
@if ($sisa > 0)
    <span class="inline-flex items-center gap-1.5 rounded-md border border-zinc-200/60 bg-white/90 px-2.5 py-1 text-xs font-medium text-zinc-700 backdrop-blur-sm tabular-nums">
        <span class="h-1.5 w-1.5 rounded-full bg-[#166534]"></span>
        Tersedia &middot; {{ $sisa }} unit
    </span>
@else
    <span class="inline-flex items-center gap-1.5 rounded-md border border-zinc-200/60 bg-white/90 px-2.5 py-1 text-xs font-medium text-zinc-500 backdrop-blur-sm">
        <span class="h-1.5 w-1.5 rounded-full bg-zinc-400"></span>
        Sedang Disewa
    </span>
@endif
