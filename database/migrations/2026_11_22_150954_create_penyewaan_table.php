<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyewaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_sewa');
            $table->date('tanggal_kembali');
            $table->date('tanggal_dikembalikan_aktual')->nullable();
            $table->enum('tipe_pembayaran', ['dp', 'lunas']);
            $table->decimal('total_harga', 12, 2);
            $table->decimal('jumlah_dibayar', 12, 2);
            $table->string('bukti_bayar')->nullable();
            $table->string('foto_ktp_jaminan')->nullable();
            $table->enum('status_sewa', ['menunggu_acc', 'ditolak', 'siap_diambil', 'sedang_disewa', 'selesai', 'dibatalkan'])->default('menunggu_acc');
            $table->enum('status_jaminan_ktp', ['belum_diserahkan', 'ditahan_di_toko', 'dikembalikan'])->default('belum_diserahkan');
            $table->text('catatan_penolakan')->nullable();
            $table->text('catatan_kondisi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyewaan');
    }
};
