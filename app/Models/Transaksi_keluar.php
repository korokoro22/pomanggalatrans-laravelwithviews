<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi_keluar extends Model
{
    use HasFactory;
    protected $table = 'transaksi_keluar';
    protected $fillable = [
        'bus_id',
        'tanggal',
        'total_transaksi'
    ];

    // TransaksiKeluar punya banyak detail
    public function details()
    {
        return $this->hasMany(Transaksi_keluar_detail::class, 'transaksi_keluar_id');
    }

    // TransaksiKeluar milik satu bus
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
}
