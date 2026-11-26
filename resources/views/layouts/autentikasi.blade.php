<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    @include('partials.kepala')
</head>
<body class="h-full font-sans text-zinc-900 antialiased">
    <div class="flex min-h-full">
        {{-- Panel kiri bertema hutan --}}
        <aside class="relative hidden w-[44%] overflow-hidden bg-emerald-900 lg:flex lg:flex-col lg:justify-between lg:p-12">
            <x-pola-latar />
            <a href="{{ route('beranda') }}" class="relative"><x-merek terang /></a>

            <div class="relative max-w-md">
                <h2 class="text-4xl font-bold leading-tight tracking-tight text-white">Siapkan petualangan, kami siapkan alatnya.</h2>
                <p class="mt-4 text-base leading-relaxed text-emerald-100/80">Cek ketersediaan secara real-time, booking online, lalu ambil langsung di toko.</p>

                <dl class="mt-10 grid grid-cols-3 gap-4">
                    @foreach ([['Alat', 'Siap sewa'], ['Online', 'Booking 24 jam'], ['Aman', 'Jaminan KTP']] as [$judulFitur, $isiFitur])
                        <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                            <dt class="text-sm font-semibold text-white">{{ $judulFitur }}</dt>
                            <dd class="mt-1 text-xs text-emerald-100/70">{{ $isiFitur }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <p class="relative text-xs text-emerald-100/60">&copy; {{ now()->year }} RimbaCamp</p>
        </aside>

        {{-- Panel kanan formulir --}}
        <main class="flex flex-1 flex-col justify-center px-6 py-12 sm:px-12">
            <div class="mx-auto w-full max-w-md">
                <a href="{{ route('beranda') }}" class="mb-10 inline-block lg:hidden"><x-merek /></a>
                <x-notifikasi />
                @yield('konten')
            </div>
        </main>
    </div>
</body>
</html>

