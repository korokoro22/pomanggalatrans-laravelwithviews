<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaJalanDetail extends Model
{
    use HasFactory;

    protected $table = 'nota_jalan_detail';

    protected $fillable = [
        'nota_jalan_id',
        'nama_item',
        'qty',
        'satuan',
        'harga_satuan',
        'subtotal',
    ];

    public function notaJalan()
    {
        return $this->belongsTo(NotaJalan::class, 'nota_jalan_id');
    }
}