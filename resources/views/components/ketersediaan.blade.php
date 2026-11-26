@props(['sisa'])

{{-- Lencana ketersediaan stok hari ini --}}
@if ($sisa > 0)
    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-emerald-900 shadow-sm ring-1 ring-inset ring-emerald-900/10">
        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
        Tersedia &middot; {{ $sisa }} unit
    </span>
@else
    <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-900 px-2.5 py-1 text-xs font-semibold text-white">
        <span class="h-1.5 w-1.5 rounded-full bg-zinc-400"></span>
        Sedang Disewa
    </span>
@endif

