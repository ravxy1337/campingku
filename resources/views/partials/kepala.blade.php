{{-- Meta, font, dan konfigurasi Tailwind bersama --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('judul', 'Beranda') | RimbaCamp</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
                },
            },
        },
    };
</script>
<style type="text/tailwindcss">
    @layer components {
        .kartu { @apply rounded-2xl bg-white ring-1 ring-stone-200; }
        .label-input { @apply mb-1.5 block text-sm font-medium text-zinc-800; }
        .kolom-input { @apply w-full rounded-xl border-0 bg-stone-50 px-3.5 py-2.5 text-sm text-zinc-900 ring-1 ring-inset ring-stone-200 transition placeholder:text-zinc-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-900; }
        .tombol { @apply inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50; }
        .tombol-utama { @apply tombol bg-emerald-900 text-white hover:bg-emerald-950 focus-visible:ring-emerald-900; }
        .tombol-gelap { @apply tombol bg-zinc-900 text-white hover:bg-zinc-800 focus-visible:ring-zinc-900; }
        .tombol-garis { @apply tombol bg-white text-zinc-800 ring-1 ring-inset ring-stone-300 hover:bg-stone-50 focus-visible:ring-zinc-400; }
        .tombol-bahaya { @apply tombol bg-red-700 text-white hover:bg-red-800 focus-visible:ring-red-700; }
    }
    .gulir-gelap { scrollbar-width: thin; scrollbar-color: #3f3f46 #18181b; }
    .gulir-gelap::-webkit-scrollbar { width: 6px; }
    .gulir-gelap::-webkit-scrollbar-track { background: #18181b; }
    .gulir-gelap::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 9999px; }
</style>

