<!DOCTYPE html>
<html lang="id" class="bg-stone-50">
<head>
    @include('partials.kepala')
</head>
<body class="min-h-screen font-sans text-zinc-900 antialiased">
    {{-- Navigasi atas publik --}}
    <header class="sticky top-0 z-30 border-b border-stone-200 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-8">
            <a href="{{ route('beranda') }}"><x-merek /></a>
            <nav class="flex items-center gap-2">
                @auth
                    <a href="{{ auth()->user()->peran === 'admin' ? route('admin.dashboard') : (auth()->user()->peran === 'petugas' ? route('petugas.antrean') : route('pelanggan.sewa-saya')) }}" class="tombol-utama py-2">
                        Dashboard
                        <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
                    </a>
                @else
                    <a href="{{ route('login') }}" class="tombol py-2 text-zinc-700 hover:bg-stone-100">Masuk</a>
                    <a href="{{ route('register') }}" class="tombol-utama py-2">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-8">
        <x-notifikasi />
        @yield('konten')
    </main>

    {{-- Kaki halaman --}}
    <footer class="border-t border-stone-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-6 text-xs text-zinc-500 sm:flex-row sm:px-8">
            <p>&copy; {{ now()->year }} RimbaCamp. Sistem Informasi Penyewaan Alat Camping.</p>
            <p>Ambil di toko &middot; Jaminan KTP asli</p>
        </div>
    </footer>
    @stack('skrip')
</body>
</html>

