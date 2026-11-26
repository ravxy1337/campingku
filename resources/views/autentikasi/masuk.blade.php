@extends('layouts.autentikasi')

@section('judul', 'Masuk')

@section('konten')
{{-- Judul halaman masuk --}}
<div>
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Selamat datang kembali</h1>
    <p class="mt-2 text-sm text-zinc-500">Masuk untuk mengelola penyewaan alat camping Anda.</p>
</div>

{{-- Formulir masuk --}}
<form action="{{ route('login.proses') }}" method="POST" class="mt-8 space-y-5">
    @csrf
    <div>
        <label for="email" class="label-input">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="kolom-input" placeholder="nama@gmail.com">
    </div>

    <div>
        <label for="password" class="label-input">Kata sandi</label>
        <input type="password" id="password" name="password" required autocomplete="current-password" class="kolom-input" placeholder="Masukkan kata sandi">
    </div>

    <button type="submit" class="tombol-utama w-full py-3">
        Masuk
        <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
    </button>
</form>

<p class="mt-6 text-center text-sm text-zinc-500">
    Belum punya akun?
    <a href="{{ route('register') }}" class="font-semibold text-emerald-900 hover:underline">Daftar sekarang</a>
</p>

{{-- Akun demo pengisian cepat --}}
<div class="mt-10 rounded-2xl bg-stone-50 p-4 ring-1 ring-stone-200">
    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Akun demo &middot; sandi: password</p>
    <div class="mt-3 grid grid-cols-3 gap-2">
        @foreach (['admin@gmail.com' => 'Admin', 'petugas@gmail.com' => 'Petugas', 'pelanggan@gmail.com' => 'Pelanggan'] as $emailDemo => $peranDemo)
            <button type="button" data-email="{{ $emailDemo }}" class="isi-demo rounded-xl bg-white px-3 py-2.5 text-left ring-1 ring-stone-200 transition hover:ring-emerald-900">
                <span class="block text-sm font-semibold text-zinc-900">{{ $peranDemo }}</span>
                <span class="block truncate text-xs text-zinc-500">{{ $emailDemo }}</span>
            </button>
        @endforeach
    </div>
</div>

<script>
    // Isi otomatis akun demo
    document.querySelectorAll('.isi-demo').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            document.getElementById('email').value = tombol.dataset.email;
            document.getElementById('password').value = 'password';
        });
    });
</script>
@endsection
