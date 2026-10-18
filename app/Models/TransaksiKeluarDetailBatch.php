<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ============================================
 * MODEL BARU: TransaksiKeluarDetailBatch
 * ============================================
 * Mencatat rincian batch barang yang diambil saat transaksi keluar
 * dengan tipe paket_service. Satu detail transaksi bisa mengambil dari banyak batch.
 */
class TransaksiKeluarDetailBatch extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keluar_detail_batch';

    protected $fillable = [
        'transaksi_keluar_detail_id',
        'barang_id',
        'qty_diambil',
    ];

    // Relasi ke transaksi_keluar_detail
    public function transaksiKeluarDetail()
    {
        return $this->belongsTo(Transaksi_keluar_detail::class, 'transaksi_keluar_detail_id');
    }

    // Relasi ke barang (batch yang diambil)
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
