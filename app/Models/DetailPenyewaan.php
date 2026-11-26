<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenyewaan extends Model
{
    // Nama tabel di database
    protected $table = 'detail_penyewaan';

    // Kolom yang dapat diisi
    protected $fillable = [
        'penyewaan_id',
        'alat_id',
        'paket_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    // Relasi ke transaksi penyewaan utama
    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class, 'penyewaan_id');
    }

    // Relasi ke alat camping yang disewa
    public function alat(): BelongsTo
    {
        return $this->belongsTo(AlatCamping::class, 'alat_id');
    }

    // Relasi ke paket sewa yang disewa
    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketSewa::class, 'paket_id');
    }
}

