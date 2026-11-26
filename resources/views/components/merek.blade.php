@props(['terang' => false])

{{-- Logo minimalis RimbaCamp --}}
<span class="inline-flex items-center gap-2.5">
    <x-ikon nama="tenda" @class(['h-5 w-5 shrink-0', 'text-white' => $terang, 'text-[#14532D]' => ! $terang]) />
    <span @class(['text-[16px] font-semibold tracking-tight', 'text-white' => $terang, 'text-zinc-900' => ! $terang])>RimbaCamp</span>
</span>
