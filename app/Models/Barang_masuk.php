<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang_masuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'jenis_masuk',
        'no_invoice',
        'kategori_nota',
        'jenis_pembelian', // REVISI: JENIS PEMBELIAN
        'tanggal_masuk',
        'supplier',
        'bukti_nota',
        'penerima',
        'bus_id',
        'keterangan',
    ];

    public function details()
    {
        return $this->hasMany(Barang_masuk_detail::class, 'barang_masuk_id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
}