<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tabel ini mencatat rincian batch barang yang diambil saat transaksi keluar
     * dengan tipe paket_service. Satu detail transaksi bisa mengambil dari banyak batch.
     */
    public function up(): void
    {
        // ============================================
        // TAMBAHAN: Tabel transaksi_keluar_detail_batch
        // ============================================
        if (!Schema::hasTable('transaksi_keluar_detail_batch')) {
            Schema::create('transaksi_keluar_detail_batch', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transaksi_keluar_detail_id')
                      ->constrained('transaksi_keluar_detail')
                      ->cascadeOnDelete();
                $table->foreignId('barang_id')
                      ->nullable()
                      ->constrained('barang')
                      ->nullOnDelete();
                $table->decimal('qty_diambil', 15, 2);
                $table->timestamps();
            });

            // Index untuk performa
            Schema::table('transaksi_keluar_detail_batch', function (Blueprint $table) {
                $table->index('transaksi_keluar_detail_id', 'idx_tkdb_detail_id');
                $table->index('barang_id', 'idx_tkdb_barang_id');
            });
        }

        // ============================================
        // TAMBAHAN: Backfill data existing
        // ============================================
        $this->backfillExistingData();
    }

    /**
     * Backfill data existing dari transaksi_keluar_detail yang tipe = paket_service
     * Hanya insert data baru, tidak mengubah data existing
     */
    private function backfillExistingData(): void
    {
        // Ambil semua transaksi_keluar_detail yang tipe = paket_service
        // dan belum memiliki catatan di transaksi_keluar_detail_batch
        $details = DB::table('transaksi_keluar_detail')
            ->where('tipe', 'paket_service')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('transaksi_keluar_detail_batch')
                      ->whereColumn('transaksi_keluar_detail_batch.transaksi_keluar_detail_id', 'transaksi_keluar_detail.id');
            })
            ->get();

        foreach ($details as $detail) {
            // Ambil paket service item
            $paketItems = DB::table('paket_service_item')
                ->where('paket_service_id', $detail->paket_service_id)
                ->get();

            foreach ($paketItems as $item) {
                // Cek apakah barang masih ada
                $batch = DB::table('barang')
                    ->where('id', $item->barang_id)
                    ->first();

                if ($batch) {
                    // Cek apakah sudah ada (mencegah duplicate)
                    $exists = DB::table('transaksi_keluar_detail_batch')
                        ->where('transaksi_keluar_detail_id', $detail->id)
                        ->where('barang_id', $item->barang_id)
                        ->exists();

                    if (!$exists) {
                        DB::table('transaksi_keluar_detail_batch')->insert([
                            'transaksi_keluar_detail_id' => $detail->id,
                            'barang_id'                  => $item->barang_id,
                            'qty_diambil'                => $item->qty,
                            'created_at'                 => $detail->created_at ?? now(),
                            'updated_at'                 => $detail->created_at ?? now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluar_detail_batch');
    }
};
