@props(['nama', 'label', 'keterangan' => null, 'wajib' => true])

{{-- Dropzone unggah gambar dengan pratinjau --}}
<div>
    <span class="label-input">{{ $label }}</span>
    <label @class([
        'group flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed px-4 py-6 text-center transition hover:border-emerald-900 hover:bg-emerald-50/40',
        'border-red-300 bg-red-50/40' => $errors->has($nama),
        'border-stone-300 bg-stone-50' => ! $errors->has($nama),
    ])>
        <img data-pratinjau-gambar class="hidden max-h-44 rounded-lg object-contain" alt="Pratinjau {{ $label }}">
        <span data-pratinjau-ikon class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-zinc-500 ring-1 ring-stone-200 group-hover:text-emerald-900">
            <x-ikon nama="unggah" />
        </span>
        <span data-pratinjau-nama class="text-sm font-medium text-zinc-700">Klik untuk memilih gambar</span>
        <span class="text-xs text-zinc-500">JPG, PNG, atau WEBP, maksimal 2 MB</span>
        <input type="file" name="{{ $nama }}" accept="image/png,image/jpeg,image/webp" @required($wajib) class="sr-only" data-pratinjau>
    </label>
    @if ($keterangan)
        <p class="mt-2 flex items-start gap-1.5 text-xs text-amber-800">
            <x-ikon nama="info" kelas="h-4 w-4 shrink-0" />
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
