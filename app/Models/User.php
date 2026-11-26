<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama_lengkap',
        'email',
        'no_hp',
        'password',
        'peran',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kode_pos',
    ];

    // Kolom yang disembunyikan dari serialisasi
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Konversi tipe data atribut otomatis
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'tanggal_lahir' => 'date',
            'password' => 'hashed',
        ];
    }

    // Relasi penyewaan milik pelanggan
    public function penyewaan(): HasMany
    {
        return $this->hasMany(Penyewaan::class, 'user_id');
    }

    // Relasi penyewaan yang diverifikasi oleh petugas
    public function verifikasiPenyewaan(): HasMany
    {
        return $this->hasMany(Penyewaan::class, 'petugas_id');
    }
}
