<!DOCTYPE html>
<html lang="id" class="h-full bg-stone-50">
<head>
    @include('partials.kepala')
</head>
<body class="h-full font-sans text-zinc-900 antialiased">
    @php
        $pengguna = auth()->user();
        $adalahAdmin = $pengguna->peran === 'admin';
        $adalahPetugas = in_array($pengguna->peran, ['petugas', 'admin'], true);

        // Daftar menu sidebar utama sesuai peran
        $daftarMenuAdmin = [
            ['label' => 'Dashboard Admin', 'ikon' => 'grafik', 'url' => route('admin.dashboard'), 'aktif' => request()->routeIs('admin.dashboard')],
            ['label' => 'Kategori Alat', 'ikon' => 'tag', 'url' => route('admin.kategori.index'), 'aktif' => request()->routeIs('admin.kategori.*')],
            ['label' => 'Kelola Katalog Alat', 'ikon' => 'tenda', 'url' => route('admin.alat.index'), 'aktif' => request()->routeIs('admin.alat.*')],
            ['label' => 'Paket Sewa', 'ikon' => 'paket', 'url' => route('admin.paket.index'), 'aktif' => request()->routeIs('admin.paket.*')],
            ['label' => 'Kelola Denda', 'ikon' => 'uang', 'url' => route('admin.denda.index'), 'aktif' => request()->routeIs('admin.denda.*')],
            ['label' => 'Manajemen Pengguna', 'ikon' => 'pengguna', 'url' => route('admin.pengguna.index'), 'aktif' => request()->routeIs('admin.pengguna.*')],
            ['label' => 'Laporan Transaksi', 'ikon' => 'laporan', 'url' => route('admin.laporan.index'), 'aktif' => request()->routeIs('admin.laporan.*')],
        ];

        $daftarMenu = $adalahPetugas
            ? [
                ['label' => 'Antrean Pesanan', 'ikon' => 'kotak-masuk', 'url' => route('petugas.antrean'), 'aktif' => request()->routeIs('petugas.*')],
            ]
            : [
                ['label' => 'Sewa Saya', 'ikon' => 'daftar', 'url' => route('pelanggan.sewa-saya'), 'aktif' => request()->routeIs('pelanggan.sewa-saya')],
                ['label' => 'Katalog Alat', 'ikon' => 'katalog', 'url' => route('beranda'), 'aktif' => false],
            ];
    @endphp

    {{-- Latar gelap sidebar mobile --}}
    <div id="latar-sidebar" class="fixed inset-0 z-40 hidden bg-zinc-900/60 backdrop-blur-sm lg:hidden" onclick="alihkanSidebar()"></div>

    {{-- Sidebar navigasi --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-zinc-900 transition-transform duration-200 lg:translate-x-0">
        <div class="flex h-16 items-center justify-between px-6">
            <a href="{{ route('beranda') }}"><x-merek terang /></a>
            <button type="button" class="text-zinc-400 hover:text-white lg:hidden" onclick="alihkanSidebar()"><x-ikon nama="silang" /></button>
        </div>

        <nav class="gulir-gelap flex-1 overflow-y-auto px-4 py-5">
            @if ($adalahAdmin)
                <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Manajemen Admin</p>
                <ul class="mt-3 space-y-1">
                    @foreach ($daftarMenuAdmin as $menuAdmin)
                        <li>
                            <a href="{{ $menuAdmin['url'] }}" @class([
                                'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                'bg-emerald-900 text-white' => $menuAdmin['aktif'],
                                'text-zinc-400 hover:bg-white/5 hover:text-white' => ! $menuAdmin['aktif'],
                            ])>
                                <x-ikon :nama="$menuAdmin['ikon']" />
                                {{ $menuAdmin['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-6 px-3 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">Operasional Toko</p>
            @else
                <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">{{ $adalahPetugas ? 'Operasional Toko' : 'Menu Pelanggan' }}</p>
            @endif
            <ul class="mt-3 space-y-1">
                @foreach ($daftarMenu as $menu)
                    <li>
                        <a href="{{ $menu['url'] }}" @class([
                            'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                            'bg-emerald-900 text-white' => $menu['aktif'],
                            'text-zinc-400 hover:bg-white/5 hover:text-white' => ! $menu['aktif'],
                        ])>
                            <x-ikon :nama="$menu['ikon']" />
                            {{ $menu['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Ketentuan toko khusus pelanggan --}}
            @if (! $adalahPetugas)
                <div class="mt-8 rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                    <div class="flex items-center gap-2 text-sm font-semibold text-white">
                        <x-ikon nama="tameng" kelas="h-4 w-4 text-emerald-400" />
                        Ketentuan Sewa
                    </div>
                    <ul class="mt-3 space-y-2 text-xs leading-relaxed text-zinc-400">
                        <li>Ambil dan kembalikan alat langsung di toko.</li>
                        <li>Serahkan KTP fisik asli sebagai jaminan selama masa sewa.</li>
                    </ul>
                </div>
            @endif
        </nav>

        {{-- Profil pengguna --}}
        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3 rounded-xl px-2 py-2">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-900 text-sm font-semibold text-white">
                    {{ strtoupper(substr($pengguna->nama_lengkap, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-white">{{ $pengguna->nama_lengkap }}</p>
                    <p class="truncate text-xs capitalize text-zinc-500">{{ $pengguna->peran }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" class="rounded-lg p-2 text-zinc-400 transition hover:bg-white/5 hover:text-white">
                        <x-ikon nama="keluar" />
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Area konten utama --}}
    <div class="lg:pl-72">
        {{-- Topbar --}}
        <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-stone-200 bg-white/80 px-4 backdrop-blur-md sm:px-8">
            <button type="button" class="-ml-1 rounded-lg p-1.5 text-zinc-600 hover:bg-stone-100 lg:hidden" onclick="alihkanSidebar()"><x-ikon nama="menu" /></button>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-zinc-900">@yield('judul')</p>
                <p class="hidden truncate text-xs text-zinc-500 sm:block">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('beranda') }}" class="tombol-garis hidden py-2 sm:inline-flex">
                <x-ikon nama="katalog" kelas="h-4 w-4" />
                Lihat Katalog
            </a>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-8">
            <x-notifikasi />
            @yield('konten')
        </main>
    </div>

    <script>
        // Buka tutup sidebar mobile
        function alihkanSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('latar-sidebar').classList.toggle('hidden');
        }
    </script>
    @stack('skrip')
</body>
</html>
