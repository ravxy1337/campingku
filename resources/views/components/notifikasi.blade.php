@php
    // Abaikan pesan sambutan generik saat login agar tidak memunculkan banner yang tidak perlu
    $pesanSukses = session('sukses');
    $pesanSambutan = [
        'Selamat datang di Panel Admin.',
        'Selamat datang di Panel Petugas.',
        'Berhasil masuk sebagai pelanggan.',
    ];
    $tampilkanSukses = filled($pesanSukses) && ! in_array($pesanSukses, $pesanSambutan, true);
@endphp

{{-- Pesan flash aksi sukses, galat, dan validasi --}}
@if ($tampilkanSukses || session('galat') || $errors->any())
    <div class="mb-6 space-y-2.5">
        @if ($tampilkanSukses)
            <div class="flex items-start gap-3 rounded-lg border border-emerald-200/80 bg-emerald-50/60 px-4 py-3 text-sm text-[#14532D]">
                <x-ikon nama="cek-lingkaran" kelas="mt-0.5 h-4 w-4 shrink-0" />
                <p class="flex-1">{{ $pesanSukses }}</p>
                <button type="button" onclick="this.parentElement.remove()" class="text-[#14532D]/60 transition hover:text-[#14532D]" aria-label="Tutup">
                    <x-ikon nama="silang" kelas="h-4 w-4" />
                </button>
            </div>
        @endif

        @if (session('galat'))
            <div class="flex items-start gap-3 rounded-lg border border-red-200/80 bg-red-50/70 px-4 py-3 text-sm text-red-800">
                <x-ikon nama="peringatan" kelas="mt-0.5 h-4 w-4 shrink-0" />
                <p class="flex-1">{{ session('galat') }}</p>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-800/60 transition hover:text-red-800" aria-label="Tutup">
                    <x-ikon nama="silang" kelas="h-4 w-4" />
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-3 rounded-lg border border-red-200/80 bg-red-50/70 px-4 py-3 text-sm text-red-800">
                <x-ikon nama="peringatan" kelas="mt-0.5 h-4 w-4 shrink-0" />
                <div class="flex-1">
                    <p class="font-medium">Periksa kembali isian Anda</p>
                    <ul class="mt-1 list-inside list-disc space-y-0.5 text-red-700">
                        @foreach ($errors->all() as $pesanGalat)
                            <li>{{ $pesanGalat }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endif
