<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang_masuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'no_invoice',
        'tanggal_masuk',
        'supplier',
        'bukti_nota',
        'penerima',
        'tanggal_masuk',
    ];

    // BarangMasuk punya banyak detail item
    public function details()
    {
        return $this->hasMany(Barang_masuk_detail::class, 'barang_masuk_id');
    }
}