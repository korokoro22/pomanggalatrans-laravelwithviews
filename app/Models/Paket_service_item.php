<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket_service_item extends Model
{
    use HasFactory;

    protected $table = 'paket_service_item';

    protected $fillable = [
        'paket_service_id',
        'barang_id',
        'qty',
    ];

    // Item milik satu paket service
    public function paketService()
    {
        return $this->belongsTo(Paket_service::class, 'paket_service_id');
    }

    // Item mereferensikan satu barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}