<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat_camping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_alat')->cascadeOnDelete();
            $table->string('nama_alat', 150);
            $table->text('deskripsi');
            $table->text('kelengkapan_standar');
            $table->decimal('harga_per_hari', 12, 2);
            $table->integer('stok_total');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat_camping');
    }
};
