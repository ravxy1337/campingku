<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPaketSewa extends Model
{
    // Nama tabel di database
    protected $table = 'detail_paket_sewa';

    // Kolom yang dapat diisi
    protected $fillable = [
        'paket_id',
        'alat_id',
        'jumlah_dalam_paket',
    ];

    // Relasi ke paket sewa induk
    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketSewa::class, 'paket_id');
    }

    // Relasi ke alat camping dalam paket
    public function alat(): BelongsTo
    {
        return $this->belongsTo(AlatCamping::class, 'alat_id');
    }
}

