<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlatCamping extends Model
{
    // Nama tabel di database
    protected $table = 'alat_camping';

    // Kolom yang dapat diisi
    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'deskripsi',
        'kelengkapan_standar',
        'harga_per_hari',
        'stok_total',
        'gambar',
    ];

    // Relasi ke kategori alat
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriAlat::class, 'kategori_id');
    }

    // Relasi ke detail paket sewa
    public function detailPaketSewa(): HasMany
    {
        return $this->hasMany(DetailPaketSewa::class, 'alat_id');
    }

    // Relasi ke detail penyewaan
    public function detailPenyewaan(): HasMany
    {
        return $this->hasMany(DetailPenyewaan::class, 'alat_id');
    }

    // Hitung sisa stok tersedia pada rentang tanggal
    public function hitungStokTersedia(string $tanggalSewa, string $tanggalKembali): int
    {
        $terpakai = $this->detailPenyewaan()
            ->whereHas('penyewaan', function ($query) use ($tanggalSewa, $tanggalKembali) {
                $query->whereIn('status_sewa', ['menunggu_acc', 'siap_diambil', 'sedang_disewa'])
                    ->where('tanggal_sewa', '<=', $tanggalKembali)
                    ->where('tanggal_kembali', '>=', $tanggalSewa);
            })
            ->sum('jumlah');

        return max(0, $this->stok_total - (int) $terpakai);
    }
}

