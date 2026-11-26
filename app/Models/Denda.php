<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    // Nama tabel di database
    protected $table = 'denda';

    // Kolom yang dapat diisi
    protected $fillable = [
        'penyewaan_id',
        'jenis_denda',
        'keterangan',
        'nominal_denda',
        'status_bayar',
    ];

    // Relasi ke transaksi penyewaan terkait
    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class, 'penyewaan_id');
    }
}

