<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;
    protected $table = 'bus';
    protected $fillable = [
        'nama_bus',
        'plat_nomor',
        'rute_trayek',
        'nama_driver'
    ];

    // Bus punya banyak transaksi keluar
    public function transaksiKeluar()
    {
        return $this->hasMany(Transaksi_keluar::class, 'bus_id');
    }
}
