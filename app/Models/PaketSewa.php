<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaketSewa extends Model
{
    // Nama tabel di database
    protected $table = 'paket_sewa';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga_paket',
        'gambar',
    ];

    // Relasi ke detail isi paket sewa
    public function detailPaket(): HasMany
    {
        return $this->hasMany(DetailPaketSewa::class, 'paket_id');
    }

    // Relasi ke detail penyewaan paket
    public function detailPenyewaan(): HasMany
    {
        return $this->hasMany(DetailPenyewaan::class, 'paket_id');
    }
}

