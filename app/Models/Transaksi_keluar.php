<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Transaksi_keluar.php

class Transaksi_keluar extends Model
{
    use HasFactory;
    protected $table = 'transaksi_keluar';
    protected $fillable = [
        'bus_id',
        'kategori',
        'no_invoice',
        'supplier',
        'bukti_nota',
        'tanggal',
        'total_transaksi'
    ];

    public function details()
    {
        return $this->hasMany(Transaksi_keluar_detail::class, 'transaksi_keluar_id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    public function scopeNormal($query)
    {
        return $query->where('kategori', 'normal');
    }

    public function scopeNotaJalan($query)
    {
        return $query->where('kategori', 'nota_jalan');
    }
}
