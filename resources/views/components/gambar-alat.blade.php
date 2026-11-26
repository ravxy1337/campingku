@props(['alat', 'kelas' => 'aspect-[4/3]'])

@php
    // Foto perlengkapan camping dari Unsplash sebagai fallback jika gambar belum diunggah
    $kunci = strtolower(($alat->nama_alat ?? '').' '.($alat->kategori->nama_kategori ?? ''));

    $fotoFallback = match (true) {
        str_contains($kunci, 'flysheet') || str_contains($kunci, 'tarp') => 'https://images.unsplash.com/photo-1764196259977-57f01dc9f1ca?auto=format&fit=crop&w=900&q=80',
        str_contains($kunci, 'tenda') || str_contains($kunci, 'dome') || str_contains($kunci, 'shelter') => 'https://images.unsplash.com/photo-1496425745709-5f9297566b46?auto=format&fit=crop&w=900&q=80',
        str_contains($kunci, 'carrier') || str_contains($kunci, 'ransel') || str_contains($kunci, 'tas') => 'https://images.unsplash.com/photo-1694140067339-1cc69e53485e?auto=format&fit=crop&w=900&q=80',
        str_contains($kunci, 'sleeping') || str_contains($kunci, 'matras') || str_contains($kunci, 'tidur') => 'https://images.unsplash.com/photo-1558477280-1bfed08ea5db?auto=format&fit=crop&w=900&q=80',
        str_contains($kunci, 'kompor') || str_contains($kunci, 'cooking') || str_contains($kunci, 'nesting') || str_contains($kunci, 'masak') => 'https://images.unsplash.com/photo-1522041350204-22285237eeca?auto=format&fit=crop&w=900&q=80',
        str_contains($kunci, 'lampu') || str_contains($kunci, 'lentera') || str_contains($kunci, 'senter') || str_contains($kunci, 'penerangan') => 'https://images.unsplash.com/photo-1503469519549-4415016d57a3?auto=format&fit=crop&w=900&q=80',
        default => 'https://images.unsplash.com/photo-1630545097402-e4e79fc9b9a5?auto=format&fit=crop&w=900&q=80',
    };

    $sumberGambar = $alat->gambar ? asset('storage/'.$alat->gambar) : $fotoFallback;
@endphp

<div {{ $attributes->class(['relative overflow-hidden bg-[#F4F4F5]', $kelas]) }}>
    <img
        src="{{ $sumberGambar }}"
        alt="{{ $alat->nama_alat }}"
        loading="lazy"
        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
        onerror="this.classList.add('opacity-0')"
    >
    {{ $slot }}
</div>
