<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_paket_sewa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('paket_sewa')->cascadeOnDelete();
            $table->foreignId('alat_id')->constrained('alat_camping')->cascadeOnDelete();
            $table->integer('jumlah_dalam_paket');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_paket_sewa');
    }
};
