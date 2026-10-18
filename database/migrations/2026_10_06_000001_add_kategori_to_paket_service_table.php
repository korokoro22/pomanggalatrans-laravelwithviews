<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * REVISI:KATEGORI PAKET SERVICE
     * Tambah kolom kategori di tabel paket_service.
     */
    public function up(): void
    {
        Schema::table('paket_service', function (Blueprint $table) {
            $table->enum('kategori', ['paket_oli', 'paket_reguler'])
                  ->default('paket_reguler')
                  ->after('nama_paket');
        });
    }

    public function down(): void
    {
        Schema::table('paket_service', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
