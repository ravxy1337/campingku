@extends('layouts.autentikasi')

@section('judul', 'Masuk')

@section('konten')
<div>
    <h1 class="text-2xl font-semibold tracking-tight text-zinc-900">Masuk ke RimbaCamp</h1>
    <p class="mt-1.5 text-sm text-zinc-500">Kelola penyewaan dan status pesanan perlengkapan Anda.</p>
</div>

<form action="{{ route('login.proses') }}" method="POST" class="mt-8 space-y-5">
    @csrf
    <div>
        <label for="email" class="label-input">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            autocomplete="email"
            class="kolom-input"
            placeholder="nama@gmail.com"
        >
    </div>

    <div>
        <label for="password" class="label-input">Kata sandi</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="current-password"
            class="kolom-input"
            placeholder="Masukkan kata sandi"
        >
    </div>

    <button type="submit" class="tombol-utama w-full py-2.5">
        Masuk
        <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
    </button>
</form>

<p class="mt-6 text-sm text-zinc-500">
    Belum punya akun?
    <a href="{{ route('register') }}" class="font-medium text-[#14532D] hover:underline">Daftar sekarang</a>
</p>

{{-- Akun demo ringkas satu baris --}}
<div class="mt-8 border-t border-zinc-200 pt-5">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <span class="text-xs text-zinc-500">Akun demo (sandi: <code class="font-mono text-zinc-700">password</code>)</span>
        <div class="flex items-center gap-1.5">
            @foreach (['admin@gmail.com' => 'Admin', 'petugas@gmail.com' => 'Petugas', 'pelanggan@gmail.com' => 'Pelanggan'] as $emailDemo => $peranDemo)
                <button
                    type="button"
                    data-email="{{ $emailDemo }}"
                    class="isi-demo rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium text-zinc-700 transition hover:border-[#14532D] hover:bg-white hover:text-[#14532D]"
                >
                    {{ $peranDemo }}
                </button>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Isi otomatis email dan kata sandi akun demo
    document.querySelectorAll('.isi-demo').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            document.getElementById('email').value = tombol.dataset.email;
            document.getElementById('password').value = 'password';
        });
    });
</script>
@endsection
