<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi_keluar_detail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keluar_detail';

    protected $fillable = [
        'transaksi_keluar_id',
        'barang_id',
        'paket_service_id', // tambahan
        'tipe',             // tambahan
        'nama_item',
        'qty',
        'satuan',
        'harga_satuan',
        'subtotal',
    ];

    // Detail milik satu transaksi keluar
    public function transaksiKeluar()
    {
        return $this->belongsTo(Transaksi_keluar::class, 'transaksi_keluar_id');
    }

    // Detail mereferensikan satu barang (nullable, hanya untuk tipe per_item)
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Detail mereferensikan satu paket service (nullable, hanya untuk tipe paket_service)
    public function paketService()
    {
        return $this->belongsTo(Paket_service::class, 'paket_service_id');
    }
}