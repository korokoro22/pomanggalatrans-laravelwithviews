<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket_service extends Model
{
    use HasFactory;

    protected $table = 'paket_service';

    protected $fillable = [
        'bus_id',
        'nama_paket',
        'harga',
    ];

    // PaketService milik satu bus
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    // PaketService punya banyak item barang
    public function paketServiceItem()
    {
        return $this->hasMany(Paket_service_item::class, 'paket_service_id');
    }


    // PaketService bisa muncul di banyak transaksi keluar detail
    public function transaksiKeluarDetail()
    {
        return $this->hasMany(Transaksi_keluar_detail::class, 'paket_service_id');
    }
}