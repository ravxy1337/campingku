@props(['terang' => false])

{{-- Logo dan nama aplikasi --}}
<span class="inline-flex items-center gap-2.5">
    <span @class(['flex h-9 w-9 items-center justify-center rounded-xl', 'bg-white text-emerald-900' => $terang, 'bg-emerald-900 text-white' => ! $terang])>
        <x-ikon nama="tenda" kelas="w-5 h-5" />
    </span>
    <span class="leading-tight">
        <span @class(['block text-[15px] font-bold tracking-tight', 'text-white' => $terang, 'text-zinc-900' => ! $terang])>RimbaCamp</span>
        <span @class(['block text-[11px] font-medium', 'text-zinc-400' => $terang, 'text-zinc-500' => ! $terang])>Sewa Alat Camping</span>
    </span>
</span>

