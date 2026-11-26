@props(['nama', 'label', 'keterangan' => null, 'wajib' => true])

{{-- Dropzone unggah berkas dengan pratinjau --}}
<div>
    <span class="label-input">{{ $label }}</span>
    <label @class([
        'group flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border border-dashed px-4 py-6 text-center transition hover:border-[#14532D] hover:bg-zinc-50',
        'border-red-300 bg-red-50/40' => $errors->has($nama),
        'border-zinc-300 bg-zinc-50/70' => ! $errors->has($nama),
    ])>
        <img data-pratinjau-gambar class="hidden max-h-44 rounded-lg border border-zinc-200 object-contain" alt="Pratinjau {{ $label }}">
        <span data-pratinjau-ikon class="flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 transition group-hover:border-[#14532D]/30 group-hover:text-[#14532D]">
            <x-ikon nama="unggah" kelas="h-4 w-4" />
        </span>
        <span data-pratinjau-nama class="text-sm font-medium text-zinc-700">Klik untuk memilih gambar</span>
        <span class="text-xs text-zinc-500">JPG, PNG, atau WEBP, maksimal 2 MB</span>
        <input type="file" name="{{ $nama }}" accept="image/png,image/jpeg,image/webp" @required($wajib) class="sr-only" data-pratinjau>
    </label>
    @if ($keterangan)
        <p class="mt-2 flex items-start gap-1.5 text-xs text-zinc-600">
            <x-ikon nama="info" kelas="mt-0.5 h-3.5 w-3.5 shrink-0 text-amber-700" />
            {{ $keterangan }}
        </p>
    @endif
</div>

@once
    @push('skrip')
        <script>
            // Pratinjau gambar sebelum diunggah
            document.querySelectorAll('[data-pratinjau]').forEach((masukan) => {
                masukan.addEventListener('change', () => {
                    const wadah = masukan.closest('label');
                    const berkas = masukan.files[0];
                    if (!berkas) return;
                    const gambar = wadah.querySelector('[data-pratinjau-gambar]');
                    gambar.src = URL.createObjectURL(berkas);
                    gambar.classList.remove('hidden');
                    wadah.querySelector('[data-pratinjau-ikon]').classList.add('hidden');
                    wadah.querySelector('[data-pratinjau-nama]').textContent = berkas.name;
                });
            });
        </script>
    @endpush
@endonce
