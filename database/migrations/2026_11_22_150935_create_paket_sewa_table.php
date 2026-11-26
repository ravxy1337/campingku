<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_sewa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 150);
            $table->text('deskripsi');
            $table->decimal('harga_paket', 12, 2);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_sewa');
    }
};
