<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan_armada extends Model
{
    use HasFactory;
    protected $table = 'keuangan_armada';
    protected $fillable = [
        'bus_id',
        'periode_bulan',
        'periode_tahun',
        'pemasukan'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
}
