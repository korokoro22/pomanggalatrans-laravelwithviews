<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * REVISI:NULLABLE JENIS PEMBELIAN
     * Ubah kolom jenis_pembelian di barang menjadi nullable.
     */
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->enum('jenis_pembelian', ['pembelian_toko', 'pembelian_langsung'])
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->enum('jenis_pembelian', ['pembelian_toko', 'pembelian_langsung'])
                  ->nullable(false)
                  ->change();
        });
    }
};
