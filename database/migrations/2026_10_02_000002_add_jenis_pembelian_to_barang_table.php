<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * REVISI: JENIS PEMBELIAN
     * Tambah kolom jenis_pembelian di tabel barang.
     */
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->enum('jenis_pembelian', ['pembelian_toko', 'pembelian_langsung'])
                  ->default('pembelian_toko')
                  ->after('kategori');
        });

        // ============================================
        // REVISI: JENIS PEMBELIAN
        // Backfill data existing: isi jenis_pembelian dari barang_masuk
        // ============================================
        DB::statement("
            UPDATE barang b
            INNER JOIN barang_masuk_detail bmd ON bmd.barang_id = b.id
            INNER JOIN barang_masuk bm ON bm.id = bmd.barang_masuk_id
            SET b.jenis_pembelian = bm.jenis_pembelian
        ");

        // Untuk barang yang tidak punya barang_masuk (fallback)
        DB::table('barang')->whereNull('jenis_pembelian')->update(['jenis_pembelian' => 'pembelian_toko']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('jenis_pembelian');
        });
    }
};
