<?php

namespace App\Console\Commands;

use App\Models\Barang;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * ============================================
 * COMMAND BARU: RecalculateStock
 * ============================================
 * Menghitung ulang stok_saat_ini untuk semua barang
 * berdasarkan riwayat transaksi masuk dan keluar.
 *
 * Cara pakai:
 *   php artisan stock:recalculate
 *   php artisan stock:recalculate --dry-run
 */
class RecalculateStock extends Command
{
    protected $signature = 'stock:recalculate {--dry-run : Tampilkan perubahan tanpa menyimpan}';
    protected $description = 'Hitung ulang stok_saat_ini dari riwayat transaksi masuk dan keluar';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $this->info('========================================');
        $this->info('  HITUNG ULANG STOK BARANG');
        if ($dryRun) {
            $this->warn('  MODE DRY-RUN (tidak menyimpan)');
        }
        $this->info('========================================');
        $this->newLine();

        // Ambil semua barang
        $barangs = Barang::all();
        $total = $barangs->count();

        $this->info("Total barang: {$total}");
        $this->newLine();

        $changes = [];
        $errors = [];

        foreach ($barangs as $barang) {
            $id = $barang->id;
            $namaBarang = $barang->nama_barang;
            $stokLama = $barang->stok_saat_ini;

            // ============================================
            // TAMBAHAN: Hitung total masuk dari barang_masuk_detail
            // ============================================
            $totalMasuk = DB::table('barang_masuk_detail')
                ->where('barang_id', $id)
                ->sum('qty_satuan');

            // ============================================
            // TAMBAHAN: Hitung total keluar dari transaksi_keluar_detail (per_item)
            // ============================================
            $totalKeluarPerItem = DB::table('transaksi_keluar_detail')
                ->where('barang_id', $id)
                ->where('tipe', 'per_item')
                ->sum('qty');

            // ============================================
            // TAMBAHAN: Hitung total keluar dari paket_service_item
            // (melalui transaksi_keluar_detail yang tipe = paket_service)
            // ============================================
            $totalKeluarPaket = DB::table('paket_service_item')
                ->join('transaksi_keluar_detail', function ($join) {
                    $join->on('paket_service_item.paket_service_id', '=', 'transaksi_keluar_detail.paket_service_id')
                         ->where('transaksi_keluar_detail.tipe', '=', 'paket_service');
                })
                ->where('paket_service_item.barang_id', $id)
                ->sum('paket_service_item.qty');

            // ============================================
            // TAMBAHAN: Hitung stok baru
            // ============================================
            $stokBaru = $totalMasuk - $totalKeluarPerItem - $totalKeluarPaket;

            // Tampilkan perubahan
            if ($stokLama != $stokBaru) {
                $changes[] = [
                    'id'         => $id,
                    'nama'       => $namaBarang,
                    'kode'       => $barang->kode_barang,
                    'stok_lama'  => $stokLama,
                    'stok_baru'  => $stokBaru,
                    'selisih'    => $stokBaru - $stokLama,
                ];

                if (!$dryRun) {
                    DB::table('barang')
                        ->where('id', $id)
                        ->update(['stok_saat_ini' => $stokBaru]);
                }
            }
        }

        // Tampilkan hasil
        if (empty($changes)) {
            $this->info('Tidak ada perubahan stok. Semua data sudah benar.');
        } else {
            $this->info('Perubahan stok:');
            $this->newLine();

            $this->table(
                ['ID', 'Nama Barang', 'Kode', 'Stok Lama', 'Stok Baru', 'Selisih'],
                array_map(fn($c) => [
                    $c['id'],
                    $c['nama'],
                    $c['kode'],
                    $c['stok_lama'],
                    $c['stok_baru'],
                    ($c['selisih'] > 0 ? '+' : '') . $c['selisih'],
                ], $changes)
            );

            $this->newLine();
            $this->info('Total barang yang berubah: ' . count($changes));

            // Tampilkan barang dengan stok negatif (jika ada)
            $negatif = array_filter($changes, fn($c) => $c['stok_baru'] < 0);
            if (!empty($negatif)) {
                $this->newLine();
                $this->warn('PERINGATAN: Ada ' . count($negatif) . ' barang dengan stok negatif setelah kalkulasi:');
                foreach ($negatif as $n) {
                    $this->warn("  - {$n['nama']} ({$n['kode']}): {$n['stok_baru']}");
                }
                $this->warn('Stok negatif ini mungkin karena barang keluar tanpa barang masuk yang sesuai.');
            }

            if ($dryRun) {
                $this->newLine();
                $this->info('Dry-run selesai. Jalankan tanpa --dry-run untuk menyimpan perubahan.');
            } else {
                $this->newLine();
                $this->info('Stok berhasil dihitung ulang dan disimpan!');
            }
        }

        return 0;
    }
}
