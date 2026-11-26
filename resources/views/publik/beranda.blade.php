@extends('layouts.publik')

@section('judul', 'Katalog Alat Camping')

@section('konten')
{{-- Hero katalog --}}
<section class="relative overflow-hidden rounded-3xl bg-emerald-900 px-6 py-12 sm:px-12 sm:py-16">
    <x-pola-latar />
    <div class="relative max-w-2xl">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-emerald-50 ring-1 ring-white/15">
            <x-ikon nama="toko" kelas="h-3.5 w-3.5" />
            Ambil langsung di toko
        </span>
        <h1 class="mt-5 text-3xl font-bold tracking-tight text-white sm:text-5xl">Fokus nikmatin view dan kopi,urusan tenda kami yang handle.</h1>
        <p class="mt-4 text-base text-emerald-100/80">Lihat ketersediaan hari ini, simulasikan tanggal sewa, dan booking online dalam hitungan menit.</p>

        <div class="relative mt-8 max-w-md">
            <x-ikon nama="cari" kelas="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-zinc-400" />
            <input type="search" id="cari-alat" placeholder="Cari tenda, carrier, sleeping bag..." class="w-full rounded-2xl border-0 bg-white py-3.5 pl-12 pr-4 text-sm text-zinc-900 shadow-lg shadow-emerald-950/20 placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-white/60">
        </div>
    </div>
</section>

{{-- Filter kategori --}}
<div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h2 class="text-xl font-bold tracking-tight text-zinc-900">Daftar Alat</h2>
        <p class="mt-1 text-sm text-zinc-500"><span id="jumlah-tampil">{{ $daftarAlat->count() }}</span> alat ditampilkan</p>
    </div>
    <div class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0">
        <a href="{{ route('beranda') }}" @class(['whitespace-nowrap rounded-full px-4 py-2 text-sm font-medium transition', 'bg-zinc-900 text-white' => ! $kategoriId, 'bg-white text-zinc-600 ring-1 ring-stone-200 hover:ring-zinc-400' => $kategoriId])>Semua</a>
        @foreach ($daftarKategori as $kategori)
            <a href="{{ route('beranda', ['kategori_id' => $kategori->id]) }}" @class(['whitespace-nowrap rounded-full px-4 py-2 text-sm font-medium transition', 'bg-zinc-900 text-white' => (string) $kategoriId === (string) $kategori->id, 'bg-white text-zinc-600 ring-1 ring-stone-200 hover:ring-zinc-400' => (string) $kategoriId !== (string) $kategori->id])>{{ $kategori->nama_kategori }}</a>
        @endforeach
    </div>
</div>

{{-- Grid kartu alat --}}
<div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($daftarAlat as $alat)
        <a href="{{ route('alat.detail', $alat) }}" data-nama="{{ strtolower($alat->nama_alat.' '.$alat->kategori->nama_kategori) }}" class="kartu-alat kartu group flex flex-col overflow-hidden transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-zinc-900/5">
            <x-gambar-alat :alat="$alat">
                <div class="absolute left-4 top-4"><x-ketersediaan :sisa="$alat->sisa_hari_ini" /></div>
            </x-gambar-alat>

            <div class="flex flex-1 flex-col p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-emerald-900">{{ $alat->kategori->nama_kategori }}</p>
                <h3 class="mt-1.5 font-semibold leading-snug text-zinc-900">{{ $alat->nama_alat }}</h3>
                <p class="mt-2 line-clamp-2 text-sm text-zinc-500">{{ $alat->deskripsi }}</p>

                <div class="mt-auto flex items-end justify-between pt-5">
                    <div>
                        <p class="text-xs text-zinc-500">Mulai dari</p>
                        <p class="text-lg font-bold text-zinc-900">@rupiah($alat->harga_per_hari)<span class="text-sm font-normal text-zinc-500">/hari</span></p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-stone-100 text-zinc-700 transition group-hover:bg-emerald-900 group-hover:text-white">
                        <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
                    </span>
                </div>
            </div>
        </a>
    @empty
        <div class="kartu col-span-full flex flex-col items-center px-6 py-16 text-center">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-stone-100 text-zinc-500"><x-ikon nama="kubus" /></span>
            <p class="mt-4 font-semibold text-zinc-900">Belum ada alat</p>
            <p class="mt-1 text-sm text-zinc-500">Kategori ini belum memiliki alat camping.</p>
        </div>
    @endforelse
</div>

<p id="pencarian-kosong" class="hidden py-16 text-center text-sm text-zinc-500">Tidak ada alat yang cocok dengan pencarian.</p>

<script>
    // Pencarian alat di sisi klien
    document.getElementById('cari-alat').addEventListener('input', (e) => {
        const kataKunci = e.target.value.trim().toLowerCase();
        let jumlah = 0;
        document.querySelectorAll('.kartu-alat').forEach((kartu) => {
            const cocok = kartu.dataset.nama.includes(kataKunci);
            kartu.classList.toggle('hidden', !cocok);
            jumlah += cocok ? 1 : 0;
        });
        document.getElementById('jumlah-tampil').textContent = jumlah;
        document.getElementById('pencarian-kosong').classList.toggle('hidden', jumlah > 0);
    });
</script>
@endsection
