<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * REVISI:NULLABLE JENIS PEMBELIAN
     * Perbaiki data existing: jenis_pembelian NULL untuk stok_awal & barang_bekas.
     */
    public function up(): void
    {
        DB::table('barang_masuk')
            ->whereIn('jenis_masuk', ['stok_awal', 'barang_bekas'])
            ->update(['jenis_pembelian' => null]);

        DB::statement("
            UPDATE barang b
            INNER JOIN barang_masuk_detail bmd ON bmd.barang_id = b.id
            INNER JOIN barang_masuk bm ON bm.id = bmd.barang_masuk_id
            SET b.jenis_pembelian = NULL
            WHERE bm.jenis_masuk IN ('stok_awal', 'barang_bekas')
        ");
    }

    public function down(): void
    {
        // Tidak perlu rollback
    }
};
