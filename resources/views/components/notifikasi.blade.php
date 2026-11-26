{{-- Pesan flash sukses, galat, dan validasi --}}
@if (session('sukses') || session('galat') || $errors->any())
    <div class="mb-6 space-y-3">
        @if (session('sukses'))
            <div class="flex items-start gap-3 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-900 ring-1 ring-inset ring-emerald-900/10">
                <x-ikon nama="cek-lingkaran" kelas="mt-px h-5 w-5 shrink-0" />
                <p class="flex-1">{{ session('sukses') }}</p>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-900/60 hover:text-emerald-900"><x-ikon nama="silang" kelas="h-4 w-4" /></button>
            </div>
        @endif

        @if (session('galat'))
            <div class="flex items-start gap-3 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-inset ring-red-900/10">
                <x-ikon nama="peringatan" kelas="mt-px h-5 w-5 shrink-0" />
                <p class="flex-1">{{ session('galat') }}</p>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-800/60 hover:text-red-800"><x-ikon nama="silang" kelas="h-4 w-4" /></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-3 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-inset ring-red-900/10">
                <x-ikon nama="peringatan" kelas="mt-px h-5 w-5 shrink-0" />
                <div class="flex-1">
                    <p class="font-semibold">Periksa kembali isian Anda</p>
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

