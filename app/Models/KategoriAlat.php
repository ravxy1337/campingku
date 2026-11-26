<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriAlat extends Model
{
    // Nama tabel di database
    protected $table = 'kategori_alat';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    // Relasi ke daftar alat camping
    public function alatCamping(): HasMany
    {
        return $this->hasMany(AlatCamping::class, 'kategori_id');
    }
}

