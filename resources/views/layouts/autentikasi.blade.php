<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    @include('partials.kepala')
</head>
<body class="h-full bg-white font-sans text-zinc-900 antialiased">
    <div class="flex min-h-full">
        {{-- Panel kiri: fotografi outdoor full-height dengan overlay gelap halus --}}
        <aside class="relative hidden w-[46%] overflow-hidden bg-zinc-950 lg:flex lg:flex-col lg:justify-between lg:p-12">
            <img
                src="https://images.unsplash.com/photo-1760715143101-74df20342df3?auto=format&fit=crop&w=1400&q=80"
                alt="Tenda camping di lembah pegunungan saat pagi hari"
                class="absolute inset-0 h-full w-full object-cover object-center"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/85 via-zinc-950/35 to-zinc-950/55"></div>

            <a href="{{ route('beranda') }}" class="relative z-10 w-fit">
                <x-merek terang />
            </a>

            <div class="relative z-10 max-w-md">
                <h2 class="text-3xl font-semibold leading-snug tracking-tight text-white">
                    Siapkan perjalanan Anda, kami siapkan perlengkapannya.
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-zinc-200/85">
                    Cek ketersediaan stok harian secara langsung, pesan online, lalu ambil perlengkapan langsung di toko.
                </p>
            </div>
        </aside>

        {{-- Panel kanan: formulir bersih --}}
        <main class="flex flex-1 flex-col justify-center bg-white px-6 py-12 sm:px-12">
            <div class="mx-auto w-full max-w-md">
                <a href="{{ route('beranda') }}" class="mb-10 inline-block lg:hidden">
                    <x-merek />
                </a>
                <x-notifikasi />
                @yield('konten')
            </div>
        </main>
    </div>
</body>
</html>
