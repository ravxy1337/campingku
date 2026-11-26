<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AutentikasiController extends Controller
{
    // Tampilkan halaman masuk pengguna
    public function formMasuk(): View
    {
        return view('autentikasi.masuk');
    }

    // Proses autentikasi dan pengalihan sesuai peran
    public function prosesMasuk(Request $request): RedirectResponse
    {
        $kredensial = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            $peran = Auth::user()->peran;

            if ($peran === 'admin') {
                return redirect()->route('admin.dashboard')->with('sukses', 'Selamat datang di Panel Admin.');
            }

            if ($peran === 'petugas') {
                return redirect()->route('petugas.antrean')->with('sukses', 'Selamat datang di Panel Petugas.');
            }

            return redirect()->intended(route('pelanggan.sewa-saya'))->with('sukses', 'Berhasil masuk sebagai pelanggan.');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    // Tampilkan halaman pendaftaran pelanggan baru
    public function formDaftar(): View
    {
        return view('autentikasi.daftar');
    }

    // Proses pendaftaran akun pelanggan dengan validasi wajib @gmail.com
    public function prosesDaftar(Request $request): RedirectResponse
    {
        $dataValid = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.regex' => 'Alamat email wajib menggunakan domain @gmail.com.',
            'email.unique' => 'Alamat email sudah terdaftar di sistem.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $pengguna = User::create([
            'nama_lengkap' => $dataValid['nama_lengkap'],
            'email' => strtolower($dataValid['email']),
            'no_hp' => $dataValid['no_hp'],
            'password' => Hash::make($dataValid['password']),
            'peran' => 'pelanggan',
        ]);

        Auth::login($pengguna);

        return redirect()->route('pelanggan.sewa-saya')->with('sukses', 'Pendaftaran berhasil. Selamat datang!');
    }

    // Proses keluar dari sesi pengguna
    public function prosesKeluar(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')->with('sukses', 'Anda telah keluar dari sistem.');
    }
}

