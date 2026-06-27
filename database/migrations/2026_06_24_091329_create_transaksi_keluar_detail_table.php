<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_keluar_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_keluar_id')->constrained('transaksi_keluar')->cascadeOnDelete();
            $table->foreignId('barang_id')->nullable()->constrained('barang')->nullOnDelete();
            $table->foreignId('paket_service_id')->nullable()->constrained('paket_service')->nullOnDelete();
            $table->enum('tipe', ['paket_service', 'per_item']);
            $table->string('nama_item');
            $table->integer('qty')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluar_detail');
    }
};
