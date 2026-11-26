<?php

namespace Database\Seeders;

use App\Models\AlatCamping;
use App\Models\KategoriAlat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // Jalankan seeder data awal sistem
    public function run(): void
    {
        // Buat 3 akun utama sesuai peran
        User::create([
            'nama_lengkap' => 'Administrator Sistem',
            'email' => 'admin@gmail.com',
            'no_hp' => '081234567890',
            'password' => Hash::make('password'),
            'peran' => 'admin',
        ]);

        User::create([
            'nama_lengkap' => 'Petugas Toko',
            'email' => 'petugas@gmail.com',
            'no_hp' => '081234567891',
            'password' => Hash::make('password'),
            'peran' => 'petugas',
        ]);

        User::create([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'pelanggan@gmail.com',
            'no_hp' => '081234567892',
            'password' => Hash::make('password'),
            'peran' => 'pelanggan',
        ]);

        // Buat 3 kategori alat camping
        $kategoriTenda = KategoriAlat::create([
            'nama_kategori' => 'Tenda & Shelter',
            'slug' => 'tenda-shelter',
        ]);

        $kategoriTidur = KategoriAlat::create([
            'nama_kategori' => 'Perlengkapan Tidur & Tas',
            'slug' => 'perlengkapan-tidur-tas',
        ]);

        $kategoriMasak = KategoriAlat::create([
            'nama_kategori' => 'Peralatan Masak & Penerangan',
            'slug' => 'peralatan-masak-penerangan',
        ]);

        // Buat 6 data alat camping beserta stok dan kelengkapan standar
        AlatCamping::create([
            'kategori_id' => $kategoriTenda->id,
            'nama_alat' => 'Tenda Dome Kapasitas 4 Orang Double Layer',
            'deskripsi' => 'Tenda dome tahan hujan deras dengan bahan outer polyester PU 3000mm dan rangka fiberglass kokoh.',
            'kelengkapan_standar' => '1x Inner Tenda, 1x Outer Flysheet, 2x Frame Utama, 1x Frame Teras, 12x Pasak Besi, 4x Tali Pancang, 1x Tas Tenda',
            'harga_per_hari' => 55000,
            'stok_total' => 8,
        ]);

        AlatCamping::create([
            'kategori_id' => $kategoriTenda->id,
            'nama_alat' => 'Flysheet Waterproof 3x4 Meter',
            'deskripsi' => 'Pelindung tambahan multifungsi untuk atap dapur darurat atau pelindung tenda dari hujan dan angin.',
            'kelengkapan_standar' => '1x Lembar Flysheet 3x4m (19 Loop), 2x Tiang Flysheet Lipat, 6x Pasak, 6x Tali Guyline, 1x Pouch',
            'harga_per_hari' => 25000,
            'stok_total' => 12,
        ]);

        AlatCamping::create([
            'kategori_id' => $kategoriTidur->id,
            'nama_alat' => 'Carrier Gunung 60 Liter BackSystem Torso',
            'deskripsi' => 'Tas ransel gunung kapasitas besar dengan penyangga punggung ergonomis dan raincover bawaan.',
            'kelengkapan_standar' => '1x Tas Carrier 60L, 1x Raincover Pelindung Hujan, 2x Frame Aluminium Internal',
            'harga_per_hari' => 40000,
            'stok_total' => 10,
        ]);

        AlatCamping::create([
            'kategori_id' => $kategoriTidur->id,
            'nama_alat' => 'Sleeping Bag Polar Bulu Tebal',
            'deskripsi' => 'Kantong tidur model mummy dengan lapisan dalam polar hangat untuk suhu dingin pegunungan.',
            'kelengkapan_standar' => '1x Sleeping Bag Polar, 1x Sarung Kompresi',
            'harga_per_hari' => 15000,
            'stok_total' => 20,
        ]);

        AlatCamping::create([
            'kategori_id' => $kategoriMasak->id,
            'nama_alat' => 'Cooking Set Nesting DS-300 + Kompor Portable',
            'deskripsi' => 'Paket panci penggorengan aluminium anodize anti lengket lengkap dengan kompor lipat windproof.',
            'kelengkapan_standar' => '1x Panci Besar, 1x Panci Kecil, 1x Teflon, 1x Kompor Windproof, 3x Mangkuk Plastik, 1x Sendok Sayur, 1x Centong Nasi, 1x Tas Jaring',
            'harga_per_hari' => 30000,
            'stok_total' => 10,
        ]);

        AlatCamping::create([
            'kategori_id' => $kategoriMasak->id,
            'nama_alat' => 'Lampu Lentera Tenda LED Rechargeable',
            'deskripsi' => 'Lentera gantung LED baterai isi ulang tahan hingga 12 jam dengan 3 mode pencahayaan.',
            'kelengkapan_standar' => '1x Unit Lentera LED, 1x Kabel Charger USB Type-C, 1x Karabiner Gantung, 1x Kotak Penyimpanan',
            'harga_per_hari' => 15000,
            'stok_total' => 15,
        ]);
    }
}
