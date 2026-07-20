<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaJalan extends Model
{
    use HasFactory;

    protected $table = 'nota_jalan';

    protected $fillable = [
        'bus_id',
        'tanggal',
        'no_invoice',
        'supplier',
        'bukti_nota',
        'total',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }

    public function details()
    {
        return $this->hasMany(NotaJalanDetail::class, 'nota_jalan_id');
    }
}