<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-white">
<head>
    @include('partials.kepala')
</head>
<body class="min-h-screen bg-white font-sans text-zinc-900 antialiased">
    @php
        $kategoriNavbar = $daftarKategori ?? \App\Models\KategoriAlat::orderBy('nama_kategori')->get();
        $penggunaAktif = auth()->user();
        $jumlahKeranjang = 0;
        $tujuanKeranjang = route('login');
        $labelKeranjang = 'Masuk untuk melihat penyewaan';

        if ($penggunaAktif) {
            if ($penggunaAktif->peran === 'pelanggan') {
                $jumlahKeranjang = \App\Models\Penyewaan::where('user_id', $penggunaAktif->id)
                    ->whereIn('status_sewa', ['menunggu_acc', 'siap_diambil', 'sedang_disewa'])
                    ->count();
                $tujuanKeranjang = route('pelanggan.sewa-saya');
                $labelKeranjang = 'Penyewaan Saya';
            } elseif ($penggunaAktif->peran === 'petugas') {
                $jumlahKeranjang = \App\Models\Penyewaan::whereIn('status_sewa', ['menunggu_acc', 'siap_diambil', 'sedang_disewa'])->count();
                $tujuanKeranjang = route('petugas.antrean');
                $labelKeranjang = 'Antrean Pesanan';
            } else {
                $jumlahKeranjang = \App\Models\Penyewaan::whereIn('status_sewa', ['menunggu_acc', 'siap_diambil', 'sedang_disewa'])->count();
                $tujuanKeranjang = route('admin.dashboard');
                $labelKeranjang = 'Dashboard Admin';
            }
        }

        $ruteBeranda = request()->routeIs('beranda');
        $adaHero = View::hasSection('hero');
    @endphp

    {{-- Navbar full-bleed sticky minimalis --}}
    <header
        x-data="{ menuMobile: false, kategoriTerbuka: false, akunTerbuka: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 24"
        @class([
            'sticky top-0 z-40 border-b border-white/10 backdrop-blur-md transition-colors duration-200',
            'bg-zinc-950/65' => $adaHero,
            'bg-zinc-950/90' => ! $adaHero,
        ])
        @if ($adaHero)
            :class="scrolled ? 'bg-zinc-950/90' : 'bg-zinc-950/65'"
        @endif
    >
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
            {{-- Kiri: Logo + menu navigasi --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('beranda') }}" class="shrink-0">
                    <x-merek terang />
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-zinc-200 md:flex">
                    {{-- Dropdown Kategori --}}
                    <div class="relative" @click.outside="kategoriTerbuka = false">
                        <button
                            type="button"
                            @click="kategoriTerbuka = !kategoriTerbuka"
                            class="inline-flex items-center gap-1 text-zinc-200 transition hover:text-white"
                        >
                            <span>Kategori</span>
                            <x-ikon nama="chevron-bawah" kelas="h-3.5 w-3.5 transition-transform duration-150" ::class="kategoriTerbuka && 'rotate-180'" />
                        </button>

                        <div
                            x-show="kategoriTerbuka"
                            x-transition.opacity.duration.150ms
                            x-cloak
                            class="absolute left-0 mt-3 w-56 rounded-xl border border-zinc-200 bg-white p-1.5 shadow-lg shadow-zinc-950/10"
                        >
                            <a
                                href="{{ route('beranda') }}#katalog"
                                @click="kategoriTerbuka = false"
                                class="block rounded-lg px-3 py-2 text-sm text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900"
                            >
                                Semua Perlengkapan
                            </a>
                            @foreach ($kategoriNavbar as $kategoriItem)
                                <a
                                    href="{{ route('beranda', ['kategori_id' => $kategoriItem->id]) }}#katalog"
                                    @click="kategoriTerbuka = false"
                                    class="block rounded-lg px-3 py-2 text-sm text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900"
                                >
                                    {{ $kategoriItem->nama_kategori }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ $ruteBeranda ? '#cara-sewa' : route('beranda').'#cara-sewa' }}" class="text-zinc-200 transition hover:text-white">
                        Cara Sewa
                    </a>
                    <a href="#kontak" class="text-zinc-200 transition hover:text-white">
                        Kontak
                    </a>
                </nav>
            </div>

            {{-- Kanan: Masuk / Akun + Tombol ikon keranjang putih --}}
            <div class="flex items-center gap-3">
                @auth
                    @php
                        $ruteDashboard = match ($penggunaAktif->peran) {
                            'admin' => route('admin.dashboard'),
                            'petugas' => route('petugas.antrean'),
                            default => route('pelanggan.sewa-saya'),
                        };
                    @endphp
                    <div class="relative hidden sm:block" @click.outside="akunTerbuka = false">
                        <button
                            type="button"
                            @click="akunTerbuka = !akunTerbuka"
                            class="inline-flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-sm font-medium text-zinc-100 transition hover:bg-white/10"
                        >
                            <span class="max-w-[140px] truncate">{{ $penggunaAktif->nama_lengkap }}</span>
                            <x-ikon nama="chevron-bawah" kelas="h-3.5 w-3.5 text-zinc-400" />
                        </button>

                        <div
                            x-show="akunTerbuka"
                            x-transition.opacity.duration.150ms
                            x-cloak
                            class="absolute right-0 mt-2.5 w-52 rounded-xl border border-zinc-200 bg-white p-1.5 shadow-lg shadow-zinc-950/10"
                        >
                            <div class="border-b border-zinc-100 px-3 py-2">
                                <p class="truncate text-xs font-medium text-zinc-900">{{ $penggunaAktif->nama_lengkap }}</p>
                                <p class="text-[11px] capitalize text-zinc-500">{{ $penggunaAktif->peran }}</p>
                            </div>
                            <a href="{{ $ruteDashboard }}" class="mt-1 block rounded-lg px-3 py-2 text-sm text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900">
                                {{ $penggunaAktif->peran === 'pelanggan' ? 'Sewa Saya' : 'Panel Dashboard' }}
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm text-red-700 transition hover:bg-red-50">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden px-2.5 py-1.5 text-sm font-medium text-zinc-100 transition hover:text-white sm:inline-block">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="hidden px-2 py-1.5 text-sm font-medium text-zinc-300 transition hover:text-white md:inline-block">
                        Daftar
                    </a>
                @endauth

                {{-- Tombol ikon keranjang putih bersih dengan indikator jumlah presisi --}}
                <a
                    href="{{ $tujuanKeranjang }}"
                    title="{{ $labelKeranjang }}"
                    aria-label="{{ $labelKeranjang }}"
                    class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white text-zinc-900 transition hover:bg-zinc-100"
                >
                    <x-ikon nama="keranjang" kelas="h-4 w-4" />
                    <span class="absolute -right-1.5 -top-1.5 flex h-4 min-w-4 items-center justify-center rounded bg-[#14532D] px-1 text-[10px] font-semibold text-white tabular-nums">
                        {{ $jumlahKeranjang }}
                    </span>
                </a>

                {{-- Tombol menu mobile --}}
                <button
                    type="button"
                    @click="menuMobile = !menuMobile"
                    aria-label="Menu navigasi"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/15 text-zinc-200 transition hover:bg-white/10 md:hidden"
                >
                    <x-ikon nama="menu" kelas="h-5 w-5" />
                </button>
            </div>
        </div>

        {{-- Panel navigasi mobile --}}
        <div x-show="menuMobile" x-cloak class="border-t border-white/10 bg-zinc-950/95 px-4 py-4 md:hidden">
            <div class="space-y-1">
                <p class="px-2 pb-1 text-[11px] font-medium uppercase tracking-wider text-zinc-400">Kategori</p>
                <a href="{{ route('beranda') }}#katalog" @click="menuMobile = false" class="block rounded-lg px-2 py-2 text-sm text-zinc-200 hover:bg-white/5">Semua Perlengkapan</a>
                @foreach ($kategoriNavbar as $kategoriItem)
                    <a href="{{ route('beranda', ['kategori_id' => $kategoriItem->id]) }}#katalog" @click="menuMobile = false" class="block rounded-lg px-2 py-2 text-sm text-zinc-200 hover:bg-white/5">
                        {{ $kategoriItem->nama_kategori }}
                    </a>
                @endforeach
            </div>
            <div class="mt-3 space-y-1 border-t border-white/10 pt-3">
                <a href="{{ $ruteBeranda ? '#cara-sewa' : route('beranda').'#cara-sewa' }}" @click="menuMobile = false" class="block rounded-lg px-2 py-2 text-sm text-zinc-200 hover:bg-white/5">Cara Sewa</a>
                <a href="#kontak" @click="menuMobile = false" class="block rounded-lg px-2 py-2 text-sm text-zinc-200 hover:bg-white/5">Kontak</a>
            </div>
            <div class="mt-3 flex items-center gap-3 border-t border-white/10 pt-3">
                @auth
                    <a href="{{ $ruteDashboard }}" class="flex-1 rounded-lg bg-[#14532D] px-3 py-2 text-center text-sm font-medium text-white">
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-white/15 px-3 py-2 text-sm font-medium text-zinc-200">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex-1 rounded-lg border border-white/15 px-3 py-2 text-center text-sm font-medium text-white">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 rounded-lg bg-[#14532D] px-3 py-2 text-center text-sm font-medium text-white">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    @yield('hero')

    @hasSection('konten_penuh')
        <main>
            @if (session('sukses') || session('galat') || $errors->any())
                <div class="mx-auto max-w-6xl px-4 pt-6 sm:px-6 lg:px-8">
                    <x-notifikasi />
                </div>
            @endif
            @yield('konten_penuh')
        </main>
    @else
        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <x-notifikasi />
            @yield('konten')
        </main>
    @endif

    {{-- Footer kontak & informasi toko --}}
    <footer id="kontak" class="border-t border-zinc-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <x-merek />
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-zinc-600">
                        Penyewaan perlengkapan outdoor terawat dengan stok harian yang transparan, pemesanan online praktis, dan pengambilan langsung di toko.
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Operasional Toko</p>
                    <ul class="mt-3 space-y-2 text-sm text-zinc-600">
                        <li>Ambil &amp; kembali langsung di toko</li>
                        <li>Setiap hari, 08.00 &ndash; 21.00 WIB</li>
                        <li>Wajib jaminan KTP fisik asli</li>
                    </ul>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Kontak</p>
                    <ul class="mt-3 space-y-2 text-sm text-zinc-600">
                        <li>halo@rimbacamp.id</li>
                        <li class="tabular-nums">+62 812-3456-7890</li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 flex flex-col items-start justify-between gap-2 border-t border-zinc-200/80 pt-6 text-xs text-zinc-500 sm:flex-row sm:items-center">
                <p>&copy; {{ now()->year }} RimbaCamp. Seluruh hak cipta dilindungi.</p>
                <p>Layanan Self-Pickup &middot; Jaminan KTP Asli</p>
            </div>
        </div>
    </footer>

    @stack('skrip')
</body>
</html>
