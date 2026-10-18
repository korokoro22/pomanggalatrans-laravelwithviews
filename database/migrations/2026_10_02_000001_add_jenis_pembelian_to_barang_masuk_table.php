<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * REVISI: JENIS PEMBELIAN
     * Tambah kolom jenis_pembelian di tabel barang_masuk.
     */
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->enum('jenis_pembelian', ['pembelian_toko', 'pembelian_langsung'])
                  ->default('pembelian_toko')
                  ->after('kategori_nota');
        });

        // ============================================
        // REVISI: JENIS PEMBELIAN
        // Set semua data existing ke 'pembelian_toko' sebagai default
        // ============================================
        DB::table('barang_masuk')->whereNull('jenis_pembelian')->update(['jenis_pembelian' => 'pembelian_toko']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn('jenis_pembelian');
        });
    }
};
