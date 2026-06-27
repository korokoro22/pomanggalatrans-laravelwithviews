<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang_masuk_detail extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_detail';

    protected $fillable = [
        'barang_masuk_id',
        'barang_id',
        'nama_barang',
        'foto',
        'qty',
        'satuan',
        'qty_satuan',
        'harga_jual',
        'subtotal',
    ];

    // Detail milik satu barang masuk
    public function barangMasuk()
    {
        return $this->belongsTo(Barang_masuk::class, 'barang_masuk_id');
    }

    // Detail mereferensikan satu barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}