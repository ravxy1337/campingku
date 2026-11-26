<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penyewaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->cascadeOnDelete();
            $table->foreignId('alat_id')->nullable()->constrained('alat_camping')->nullOnDelete();
            $table->foreignId('paket_id')->nullable()->constrained('paket_sewa')->nullOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penyewaan');
    }
};
