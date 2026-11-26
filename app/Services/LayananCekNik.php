<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LayananCekNik
{
    // Periksa NIK ke API parse1337 dengan cache hasil sukses
    public function periksaNik(string $nik): array
    {
        if (! preg_match('/^\d{16}$/', $nik)) {
            return $this->gagal('Format NIK harus berupa 16 digit angka.');
        }

        $dataTersimpan = Cache::get("cek_nik.{$nik}");
        if ($dataTersimpan) {
            return $this->berhasil($dataTersimpan);
        }

        try {
            $respon = Http::acceptJson()
                ->timeout(10)
                ->withOptions(['verify' => $this->sertifikatCa()])
                ->get(config('services.cek_nik.url'), ['nik' => $nik]);
        } catch (\Throwable $e) {
            Log::warning('Gagal menghubungi API cek NIK', ['nik' => $nik, 'galat' => $e->getMessage()]);

            return $this->gagal('Layanan verifikasi NIK tidak dapat dihubungi. Coba beberapa saat lagi.');
        }

        if ($respon->failed() || $respon->json('status') !== 'success') {
            return $this->gagal($respon->json('pesan') ?: 'NIK tidak valid.');
        }

        $data = $respon->json('data');
        $dataTerstruktur = [
            'nik' => $data['nik'] ?? $nik,
            'jenis_kelamin' => $data['kelamin'] ?? null,
            'tanggal_lahir' => empty($data['lahir']) ? null : Carbon::createFromFormat('d/m/Y', $data['lahir'])->format('Y-m-d'),
            'provinsi' => $data['provinsi'] ?? null,
            'kota_kabupaten' => $data['kotakab'] ?? null,
            'kecamatan' => $data['kecamatan'] ?? null,
            'kode_pos' => $data['tambahan']['kodepos'] ?? null,
        ];

        Cache::put("cek_nik.{$nik}", $dataTerstruktur, now()->addDay());

        return $this->berhasil($dataTerstruktur);
    }

    // Pakai CA bundle lokal jika tersedia agar SSL valid di Windows
    private function sertifikatCa(): string|bool
    {
        $lokasi = config('services.cek_nik.ca_bundle');

        return $lokasi && is_file($lokasi) ? $lokasi : true;
    }

    // Format respon sukses
    private function berhasil(array $data): array
    {
        return ['berhasil' => true, 'pesan' => 'NIK valid dan terverifikasi.', 'data' => $data];
    }

    // Format respon gagal
    private function gagal(string $pesan): array
    {
        return ['berhasil' => false, 'pesan' => $pesan];
    }
}
