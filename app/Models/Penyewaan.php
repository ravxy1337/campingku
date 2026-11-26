<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penyewaan extends Model
{
    // Label tampilan status sewa dan jaminan KTP
    public const LABEL_STATUS = [
        'menunggu_acc' => 'Menunggu ACC',
        'ditolak' => 'Ditolak',
        'siap_diambil' => 'Siap Diambil',
        'sedang_disewa' => 'Sedang Disewa',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        'belum_diserahkan' => 'KTP Belum Diserahkan',
        'ditahan_di_toko' => 'KTP Ditahan di Toko',
        'dikembalikan' => 'KTP Dikembalikan',
    ];

    // Nama tabel di database
    protected $table = 'penyewaan';

    // Kolom yang dapat diisi
    protected $fillable = [
        'kode_booking',
        'user_id',
        'petugas_id',
        'tanggal_sewa',
        'tanggal_kembali',
        'tanggal_dikembalikan_aktual',
        'tipe_pembayaran',
        'total_harga',
        'jumlah_dibayar',
        'bukti_bayar',
        'foto_ktp_jaminan',
        'status_sewa',
        'status_jaminan_ktp',
        'catatan_penolakan',
        'catatan_kondisi',
    ];

    // Konversi tipe data tanggal otomatis
    protected function casts(): array
    {
        return [
            'tanggal_sewa' => 'date',
            'tanggal_kembali' => 'date',
            'tanggal_dikembalikan_aktual' => 'date',
        ];
    }

    // Relasi ke akun pelanggan penyewa
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke akun petugas verifikator
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // Relasi ke rincian item penyewaan
    public function detailPenyewaan(): HasMany
    {
        return $this->hasMany(DetailPenyewaan::class, 'penyewaan_id');
    }

    // Relasi ke daftar denda penyewaan
    public function denda(): HasMany
    {
        return $this->hasMany(Denda::class, 'penyewaan_id');
    }
}

