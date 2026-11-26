@extends('layouts.publik')

@section('judul', 'Sewa Perlengkapan Outdoor')

@php
    $jumlahPerKategori = \App\Models\AlatCamping::selectRaw('kategori_id, count(*) as total')
        ->groupBy('kategori_id')
        ->pluck('total', 'kategori_id');
    $totalSemuaAlat = $jumlahPerKategori->sum();
@endphp

@section('hero')
    {{-- Full-bleed photographic hero --}}
    <section class="relative -mt-16 flex min-h-[460px] items-center overflow-hidden bg-zinc-950 pt-16 pb-16 md:min-h-[500px]">
        <img
            src="https://images.unsplash.com/photo-1760715650044-4e588c2dd2e2?auto=format&fit=crop&w=1800&q=80"
            alt="Tenda camping di alam pegunungan saat matahari terbit"
            class="absolute inset-0 h-full w-full object-cover object-center"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/75 via-zinc-950/40 to-zinc-950/50"></div>

        <div class="relative z-10 mx-auto w-full max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <p class="text-xs font-medium tracking-wide text-zinc-200 sm:text-sm">
                Sewa Perlengkapan Outdoor Terpercaya
            </p>
            <h1 class="mt-3 max-w-2xl text-4xl font-semibold tracking-tight text-white md:text-5xl md:leading-[1.12]">
                Perlengkapan Siap Pakai untuk Perjalanan Anda.
            </h1>
            <p class="mt-4 max-w-xl text-sm leading-relaxed text-zinc-200/90 sm:text-base">
                Cek ketersediaan stok harian secara langsung, pesan online dalam hitungan menit, dan ambil di toko.
            </p>
        </div>
    </section>
@endsection

@section('konten_penuh')
<div x-data="pencarianKatalog('{{ now()->toDateString() }}', '{{ now()->addDay()->toDateString() }}')">
    {{-- Section Showcase Kategori + Floating Horizontal Booking Bar --}}
    <section class="bg-[#F7F8F7] pb-14">
        {{-- Floating Horizontal Booking & Search Bar --}}
        <div class="relative z-20 mx-auto -mt-10 max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-zinc-200/90 bg-white p-3 shadow-[0_8px_30px_rgba(0,0,0,0.06)] md:p-4">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-0">
                    <div class="grid flex-1 grid-cols-1 divide-y divide-zinc-200 sm:grid-cols-2 sm:divide-y-0 md:grid-cols-4 md:divide-x md:divide-zinc-200">
                        {{-- Kolom 1: Tgl Ambil --}}
                        <div class="px-3 py-2 md:py-1">
                            <label for="tgl-ambil" class="block text-xs text-zinc-500">Tgl Ambil</label>
                            <input
                                type="date"
                                id="tgl-ambil"
                                x-model="tanggalAmbil"
                                min="{{ now()->toDateString() }}"
                                @change="hitungKembali()"
                                class="mt-1 w-full border-0 bg-transparent p-0 text-sm font-semibold text-zinc-900 tabular-nums focus:outline-none focus:ring-0"
                            >
                        </div>

                        {{-- Kolom 2: Durasi --}}
                        <div class="px-3 py-2 md:py-1">
                            <label for="durasi-sewa" class="block text-xs text-zinc-500">Durasi</label>
                            <select
                                id="durasi-sewa"
                                x-model="durasi"
                                @change="hitungKembali()"
                                class="mt-1 w-full cursor-pointer border-0 bg-transparent p-0 text-sm font-semibold text-zinc-900 focus:outline-none focus:ring-0"
                            >
                                <option value="1">1 Hari</option>
                                <option value="2">2 Hari</option>
                                <option value="3">3 Hari</option>
                                <option value="4">4&ndash;7 Hari</option>
                            </select>
                        </div>

                        {{-- Kolom 3: Maks. Pengembalian --}}
                        <div class="px-3 py-2 md:py-1">
                            <label for="tgl-kembali" class="block text-xs text-zinc-500">Maks. Pengembalian</label>
                            <input
                                type="date"
                                id="tgl-kembali"
                                x-model="tanggalKembali"
                                :min="minKembali()"
                                @change="if (!tanggalKembali || tanggalKembali <= tanggalAmbil) tanggalKembali = minKembali()"
                                class="mt-1 w-full border-0 bg-transparent p-0 text-sm font-semibold text-zinc-900 tabular-nums focus:outline-none focus:ring-0"
                            >
                        </div>

                        {{-- Kolom 4: Cari --}}
                        <div class="px-3 py-2 md:py-1">
                            <label for="cari-alat" class="block text-xs text-zinc-500">Cari</label>
                            <input
                                type="search"
                                id="cari-alat"
                                x-model="kataKunci"
                                @input="filterKatalog()"
                                @keydown.enter.prevent="cariDanGulir()"
                                placeholder="Cari tenda, carrier, kompor..."
                                class="mt-1 w-full border-0 bg-transparent p-0 text-sm font-medium text-zinc-900 placeholder:font-normal placeholder:text-zinc-400 focus:outline-none focus:ring-0"
                            >
                        </div>
                    </div>

                    {{-- Kolom 5: Tombol Cari --}}
                    <div class="md:pl-4">
                        <button
                            type="button"
                            @click="cariDanGulir()"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#14532D] px-6 py-3 text-sm font-medium text-white transition hover:bg-[#0F3F22] md:w-auto"
                        >
                            <x-ikon nama="cari" kelas="h-4 w-4" />
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Showcase Pilihan Kategori --}}
        <div class="mx-auto mt-12 max-w-6xl px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="text-xl font-semibold tracking-tight text-zinc-900">Pilihan Kategori</h2>
                <p class="mt-1 text-sm text-zinc-600">Pilih kategori perlengkapan sesuai rencana perjalanan Anda.</p>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <a
                    href="{{ route('beranda') }}#katalog"
                    @class([
                        'group rounded-xl border bg-white p-5 transition',
                        'border-[#14532D] ring-1 ring-[#14532D]' => ! $kategoriId,
                        'border-zinc-200/80 hover:border-zinc-400' => $kategoriId,
                    ])
                >
                    <div class="flex items-center justify-between">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-200/80 bg-zinc-50 text-zinc-700">
                            <x-ikon nama="katalog" kelas="h-4 w-4" />
                        </span>
                        <x-ikon nama="panah-kanan" kelas="h-4 w-4 text-zinc-300 transition group-hover:translate-x-0.5 group-hover:text-zinc-600" />
                    </div>
                    <p class="mt-4 text-sm font-semibold text-zinc-900">Semua Kategori</p>
                    <p class="mt-1 text-xs text-zinc-500 tabular-nums">{{ $totalSemuaAlat }} perlengkapan</p>
                </a>

                @foreach ($daftarKategori as $kategori)
                    @php
                        $aktifKategori = (string) $kategoriId === (string) $kategori->id;
                        $namaLower = strtolower($kategori->nama_kategori);
                        $ikonKategori = match (true) {
                            str_contains($namaLower, 'tenda') => 'tenda',
                            str_contains($namaLower, 'tidur') || str_contains($namaLower, 'tas') => 'tas',
                            str_contains($namaLower, 'masak') || str_contains($namaLower, 'penerangan') => 'api',
                            default => 'tag',
                        };
                    @endphp
                    <a
                        href="{{ route('beranda', ['kategori_id' => $kategori->id]) }}#katalog"
                        @class([
                            'group rounded-xl border bg-white p-5 transition',
                            'border-[#14532D] ring-1 ring-[#14532D]' => $aktifKategori,
                            'border-zinc-200/80 hover:border-zinc-400' => ! $aktifKategori,
                        ])
                    >
                        <div class="flex items-center justify-between">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-200/80 bg-zinc-50 text-zinc-700">
                                <x-ikon :nama="$ikonKategori" kelas="h-4 w-4" />
                            </span>
                            <x-ikon nama="panah-kanan" kelas="h-4 w-4 text-zinc-300 transition group-hover:translate-x-0.5 group-hover:text-zinc-600" />
                        </div>
                        <p class="mt-4 text-sm font-semibold text-zinc-900">{{ $kategori->nama_kategori }}</p>
                        <p class="mt-1 text-xs text-zinc-500 tabular-nums">{{ $jumlahPerKategori[$kategori->id] ?? 0 }} perlengkapan</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Section Katalog Utama --}}
    <section id="katalog" class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">Katalog Alat Camping</h2>
                    <p class="mt-1 text-sm text-zinc-600">
                        <span id="jumlah-tampil" x-text="jumlahTampil" class="font-medium text-zinc-900 tabular-nums">{{ $daftarAlat->count() }}</span> perlengkapan tersedia untuk disewa
                    </p>
                </div>

                <a
                    href="{{ route('beranda') }}#katalog"
                    @click="if (kataKunci) { $event.preventDefault(); resetPencarian(); }"
                    class="inline-flex items-center gap-2 self-start rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-400 hover:text-zinc-900 sm:self-auto"
                >
                    Lihat Semua
                    <x-ikon nama="panah-kanan" kelas="h-3.5 w-3.5" />
                </a>
            </div>

            {{-- Grid Kartu Produk --}}
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($daftarAlat as $alat)
                    @php
                        $urlDetail = route('alat.detail', $alat);
                    @endphp
                    <article
                        data-nama="{{ strtolower($alat->nama_alat.' '.$alat->kategori->nama_kategori) }}"
                        class="kartu-alat group flex flex-col overflow-hidden rounded-xl border border-zinc-200/80 bg-white transition hover:border-zinc-300"
                    >
                        <a
                            href="{{ $urlDetail }}"
                            :href="'{{ $urlDetail }}?tanggal_sewa=' + tanggalAmbil + '&tanggal_kembali=' + tanggalKembali + '&jumlah=1'"
                            class="block"
                        >
                            <x-gambar-alat :alat="$alat">
                                <div class="absolute left-3 top-3">
                                    <x-ketersediaan :sisa="$alat->sisa_hari_ini" />
                                </div>
                            </x-gambar-alat>
                        </a>

                        <div class="flex flex-1 flex-col p-5">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-zinc-500">
                                {{ $alat->kategori->nama_kategori }}
                            </p>
                            <h3 class="mt-1.5 text-base font-semibold leading-snug text-zinc-900">
                                <a
                                    href="{{ $urlDetail }}"
                                    :href="'{{ $urlDetail }}?tanggal_sewa=' + tanggalAmbil + '&tanggal_kembali=' + tanggalKembali + '&jumlah=1'"
                                    class="hover:text-[#14532D]"
                                >
                                    {{ $alat->nama_alat }}
                                </a>
                            </h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-zinc-500">
                                {{ $alat->deskripsi }}
                            </p>

                            <div class="mt-auto pt-5">
                                <div class="flex items-baseline justify-between border-t border-zinc-100 pt-4">
                                    <span class="text-xs text-zinc-500">Tarif sewa</span>
                                    <p class="text-base font-semibold text-zinc-900 tabular-nums">
                                        @rupiah($alat->harga_per_hari)<span class="text-xs font-normal text-zinc-500"> / hari</span>
                                    </p>
                                </div>

                                <a
                                    href="{{ $urlDetail }}"
                                    :href="'{{ $urlDetail }}?tanggal_sewa=' + tanggalAmbil + '&tanggal_kembali=' + tanggalKembali + '&jumlah=1'"
                                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-zinc-200 bg-zinc-50/70 px-4 py-2.5 text-sm font-medium text-zinc-800 transition hover:border-[#14532D] hover:bg-[#14532D] hover:text-white"
                                >
                                    Lihat Detail &amp; Cek Jadwal
                                    <x-ikon nama="panah-kanan" kelas="h-3.5 w-3.5" />
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full flex flex-col items-center rounded-xl border border-zinc-200/80 bg-zinc-50/50 px-6 py-16 text-center">
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500">
                            <x-ikon nama="kubus" />
                        </span>
                        <p class="mt-4 font-semibold text-zinc-900">Belum ada perlengkapan</p>
                        <p class="mt-1 text-sm text-zinc-500">Kategori ini belum memiliki alat camping yang terdaftar.</p>
                    </div>
                @endforelse
            </div>

            {{-- State kosong pencarian --}}
            <div x-show="jumlahTampil === 0" x-cloak class="mt-8 rounded-xl border border-zinc-200/80 bg-zinc-50/60 px-6 py-12 text-center">
                <p class="text-sm font-medium text-zinc-900">Tidak ada perlengkapan yang sesuai dengan kata kunci tersebut.</p>
                <p class="mt-1 text-sm text-zinc-500">Coba kata kunci lain atau tampilkan semua katalog.</p>
                <button type="button" @click="resetPencarian()" class="tombol-garis mt-4">
                    Tampilkan Semua Alat
                </button>
            </div>
        </div>
    </section>

    {{-- Section Cara Sewa --}}
    <section id="cara-sewa" class="border-t border-zinc-200/70 bg-[#F7F8F7] py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl">
                <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">Cara Sewa di RimbaCamp</h2>
                <p class="mt-1 text-sm text-zinc-600">Proses penyewaan ringkas dari pengecekan stok hingga pengambilan perlengkapan di toko.</p>
            </div>

            <div class="mt-8 grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['01', 'Pilih Alat & Jadwal', 'Tentukan tanggal pengambilan dan durasi sewa untuk memastikan stok tersedia pada hari keberangkatan Anda.'],
                    ['02', 'Pesan Online & Verifikasi', 'Pilih pembayaran DP 50% atau lunas, unggah bukti transfer, serta verifikasi NIK dan foto KTP asli.'],
                    ['03', 'Ambil di Toko (Self-Pickup)', 'Setelah pesanan disetujui petugas, ambil perlengkapan di toko dengan menyerahkan KTP fisik asli sebagai jaminan.'],
                ] as [$nomorLangkah, $judulLangkah, $isiLangkah])
                    <div class="rounded-xl border border-zinc-200/80 bg-white p-6">
                        <span class="font-mono text-xs font-medium text-[#14532D] tabular-nums">{{ $nomorLangkah }}</span>
                        <h3 class="mt-3 text-base font-semibold text-zinc-900">{{ $judulLangkah }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $isiLangkah }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection

@push('skrip')
<script>
    // Logika perhitungan tanggal otomatis & filter pencarian katalog
    function pencarianKatalog(hariIni, besok) {
        return {
            tanggalAmbil: hariIni,
            durasi: '1',
            tanggalKembali: besok,
            kataKunci: '',
            jumlahTampil: {{ $daftarAlat->count() }},

            tambahHari(tanggalStr, jumlahHari) {
                if (!tanggalStr) return hariIni;
                const [tahun, bulan, hari] = tanggalStr.split('-').map(Number);
                const tanggal = new Date(Date.UTC(tahun, bulan - 1, hari + jumlahHari));
                return tanggal.toISOString().slice(0, 10);
            },

            minKembali() {
                return this.tambahHari(this.tanggalAmbil, 1);
            },

            hitungKembali() {
                const hari = Math.max(1, parseInt(this.durasi, 10) || 1);
                this.tanggalKembali = this.tambahHari(this.tanggalAmbil, hari);
            },

            filterKatalog() {
                const kata = this.kataKunci.trim().toLowerCase();
                let hitung = 0;
                document.querySelectorAll('.kartu-alat').forEach((kartu) => {
                    const cocok = kartu.dataset.nama.includes(kata);
                    kartu.classList.toggle('hidden', !cocok);
                    if (cocok) hitung++;
                });
                this.jumlahTampil = hitung;
            },

            cariDanGulir() {
                this.filterKatalog();
                const target = document.getElementById('katalog');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            },

            resetPencarian() {
                this.kataKunci = '';
                this.filterKatalog();
            },
        };
    }
</script>
@endpush
