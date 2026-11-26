@props(['alat', 'kelas' => 'aspect-[4/3]'])

{{-- Gambar alat atau ilustrasi pengganti --}}
<div {{ $attributes->class(['relative overflow-hidden bg-stone-100', $kelas]) }}>
    @if ($alat->gambar)
        <img src="{{ asset('storage/'.$alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="h-full w-full object-cover">
    @else
        <div class="absolute inset-0 bg-emerald-900">
            <x-pola-latar />
        </div>
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 text-white ring-1 ring-white/20 backdrop-blur-sm">
                <x-ikon nama="tenda" kelas="h-8 w-8" />
            </span>
        </div>
    @endif
    {{ $slot }}
</div>

