@extends('layouts.autentikasi')

@section('judul', 'Daftar')

@section('konten')
<div>
    <h1 class="text-2xl font-semibold tracking-tight text-zinc-900">Buat akun pelanggan</h1>
    <p class="mt-1.5 text-sm text-zinc-500">Lengkapi data diri Anda menggunakan alamat email @gmail.com.</p>
</div>

<form action="{{ route('register.proses') }}" method="POST" class="mt-8 space-y-4">
    @csrf
    <div>
        <label for="nama_lengkap" class="label-input">Nama lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required autofocus class="kolom-input" placeholder="Sesuai KTP">
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="email" class="label-input">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="kolom-input" placeholder="nama@gmail.com">
        </div>
        <div>
            <label for="no_hp" class="label-input">No. HP / WhatsApp</label>
            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required class="kolom-input tabular-nums" placeholder="08xxxxxxxxxx">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="password" class="label-input">Kata sandi</label>
            <input type="password" id="password" name="password" required autocomplete="new-password" class="kolom-input" placeholder="Min. 6 karakter">
        </div>
        <div>
            <label for="password_confirmation" class="label-input">Ulangi kata sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="kolom-input" placeholder="Ketik ulang">
        </div>
    </div>

    <button type="submit" class="tombol-utama mt-2 w-full py-2.5">
        Buat Akun
        <x-ikon nama="panah-kanan" kelas="h-4 w-4" />
    </button>
</form>

<p class="mt-6 text-sm text-zinc-500">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="font-medium text-[#14532D] hover:underline">Masuk di sini</a>
</p>
@endsection
