<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'foto',
        'nama_barang',
        'qty',
        'satuan',
        'harga_jual',
        'qty_satuan',
        'stok_saat_ini',
        'tanggal_masuk',
        'qr_code',
    ];

    // Barang punya banyak riwayat masuk
    public function barangMasuk()
    {
        return $this->hasMany(Barang_masuk::class, 'barang_id');
    }

    // Barang punya banyak riwayat keluar
    public function transaksiKeluarDetail()
    {
        return $this->hasMany(Transaksi_keluar_detail::class, 'barang_id');
    }

    // Barang bisa masuk ke banyak paket service
    public function paketServiceItem()
    {
        return $this->hasMany(Paket_service_item::class, 'barang_id');
    }

    public function barangMasukDetail()
    {
        return $this->hasMany(Barang_masuk_detail::class, 'barang_id');
    }
}
