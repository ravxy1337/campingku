<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    @include('partials.kepala')
</head>
<body class="h-full bg-white font-sans text-zinc-900 antialiased">
    @php
        $pengguna = auth()->user();
        $adalahAdmin = $pengguna->peran === 'admin';
        $adalahPetugas = in_array($pengguna->peran, ['petugas', 'admin'], true);

        $grupArea = $adalahAdmin ? 'Admin' : ($adalahPetugas ? 'Petugas' : 'Pelanggan');

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

        $tanggalHariIni = now('Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y');
    @endphp

    {{-- Latar gelap sidebar mobile --}}
    <div id="latar-sidebar" class="fixed inset-0 z-40 hidden bg-zinc-950/40 backdrop-blur-xs lg:hidden" onclick="alihkanSidebar()"></div>

    {{-- Sidebar kiri minimalist netral --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-zinc-200 bg-[#FAFAFA] transition-transform duration-200 lg:translate-x-0">
        <div class="flex h-14 items-center justify-between border-b border-zinc-200 px-5">
            <a href="{{ route('beranda') }}"><x-merek /></a>
            <button type="button" class="rounded-lg p-1 text-zinc-500 hover:bg-zinc-200/60 hover:text-zinc-900 lg:hidden" onclick="alihkanSidebar()" aria-label="Tutup menu">
                <x-ikon nama="silang" kelas="h-4 w-4" />
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4">
            @if ($adalahAdmin)
                <p class="px-2.5 text-[11px] font-medium uppercase tracking-wider text-zinc-400">Manajemen</p>
                <ul class="mt-2 space-y-0.5">
                    @foreach ($daftarMenuAdmin as $menuAdmin)
                        <li>
                            <a href="{{ $menuAdmin['url'] }}" @class([
                                'group flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-[13px] transition',
                                'bg-zinc-200/75 font-semibold text-zinc-900' => $menuAdmin['aktif'],
                                'font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' => ! $menuAdmin['aktif'],
                            ])>
                                <x-ikon :nama="$menuAdmin['ikon']" @class(['h-4 w-4 shrink-0', 'text-[#14532D]' => $menuAdmin['aktif'], 'text-zinc-400 group-hover:text-zinc-700' => ! $menuAdmin['aktif']]) />
                                <span class="truncate">{{ $menuAdmin['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-6 px-2.5 text-[11px] font-medium uppercase tracking-wider text-zinc-400">Operasional Toko</p>
            @else
                <p class="px-2.5 text-[11px] font-medium uppercase tracking-wider text-zinc-400">{{ $adalahPetugas ? 'Operasional Toko' : 'Menu Pelanggan' }}</p>
            @endif

            <ul class="mt-2 space-y-0.5">
                @foreach ($daftarMenu as $menu)
                    <li>
                        <a href="{{ $menu['url'] }}" @class([
                            'group flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-[13px] transition',
                            'bg-zinc-200/75 font-semibold text-zinc-900' => $menu['aktif'],
                            'font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' => ! $menu['aktif'],
                        ])>
                            <x-ikon :nama="$menu['ikon']" @class(['h-4 w-4 shrink-0', 'text-[#14532D]' => $menu['aktif'], 'text-zinc-400 group-hover:text-zinc-700' => ! $menu['aktif']]) />
                            <span class="truncate">{{ $menu['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            @if (! $adalahPetugas)
                <div class="mt-6 rounded-xl border border-zinc-200 bg-white p-4">
                    <div class="flex items-center gap-2 text-xs font-semibold text-zinc-900">
                        <x-ikon nama="tameng" kelas="h-4 w-4 text-[#14532D]" />
                        Ketentuan Sewa
                    </div>
                    <ul class="mt-2.5 space-y-1.5 text-xs leading-relaxed text-zinc-600">
                        <li>Ambil dan kembalikan alat langsung di toko.</li>
                        <li>Serahkan KTP fisik asli sebagai jaminan selama masa sewa.</li>
                    </ul>
                </div>
            @endif
        </nav>

        {{-- Profil pengguna --}}
        <div class="border-t border-zinc-200 p-3">
            <div class="flex items-center gap-2.5 rounded-lg px-2 py-1.5">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-zinc-900 text-xs font-semibold text-white">
                    {{ strtoupper(substr($pengguna->nama_lengkap, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-semibold text-zinc-900">{{ $pengguna->nama_lengkap }}</p>
                    <p class="truncate text-[11px] capitalize text-zinc-500">{{ $pengguna->peran }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" class="rounded-lg p-1.5 text-zinc-400 transition hover:bg-zinc-200/60 hover:text-zinc-900">
                        <x-ikon nama="keluar" kelas="h-4 w-4" />
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Area konten utama --}}
    <div class="lg:pl-64">
        {{-- Topbar tunggal tanpa duplikasi judul di body --}}
        <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b border-zinc-200 bg-white/95 px-4 backdrop-blur-sm sm:px-8">
            <button type="button" class="-ml-1 rounded-lg p-1.5 text-zinc-600 hover:bg-zinc-100 lg:hidden" onclick="alihkanSidebar()" aria-label="Buka menu">
                <x-ikon nama="menu" kelas="h-5 w-5" />
            </button>

            <div class="flex min-w-0 flex-1 items-center gap-2 text-sm">
                <span class="hidden text-zinc-400 sm:inline">{{ $grupArea }}</span>
                <span class="hidden text-zinc-300 sm:inline">/</span>
                <h1 class="truncate font-semibold tracking-tight text-zinc-900">@yield('judul')</h1>
            </div>

            <div class="flex items-center gap-4">
                <span class="hidden text-xs text-zinc-500 tabular-nums md:inline">{{ $tanggalHariIni }}</span>
                <a href="{{ route('beranda') }}" class="tombol-garis hidden px-3 py-1.5 text-xs sm:inline-flex">
                    <x-ikon nama="katalog" kelas="h-3.5 w-3.5" />
                    Lihat Katalog
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-8">
            <x-notifikasi />
            @yield('konten')
        </main>
    </div>

    <script>
        function alihkanSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('latar-sidebar').classList.toggle('hidden');
        }
    </script>
    @stack('skrip')
</body>
</html>
