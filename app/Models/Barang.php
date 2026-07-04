<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'foto',
        'nama_barang',
        'kategori',
        'gudang',        // tambah ini
        'qty',
        'satuan',
        'harga_jual',
        'qty_satuan',
        'stok_saat_ini',
        'tanggal_masuk',
        'qr_code',
    ];

    protected $appends = ['foto_url', 'tanggal_masuk_formatted'];

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    public function getTanggalMasukFormattedAttribute()
    {
        return $this->tanggal_masuk
            ? \Carbon\Carbon::parse($this->tanggal_masuk)->format('d-m-Y')
            : '-';
    }

    // Barang punya banyak riwayat masuk
    public function barangMasuk()
    {
        return $this->hasMany(Barang_masuk::class, 'barang_id');
    }

    // Barang punya banyak riwayat keluar
    public function transaksiKeluarDetail()
    {
        return $this->hasMany(Transaksi_keluar_detail::class, 'barang_id');
    }

    // Barang bisa masuk ke banyak paket service
    public function paketServiceItem()
    {
        return $this->hasMany(Paket_service_item::class, 'barang_id');
    }

    public function barangMasukDetail()
    {
        return $this->hasMany(Barang_masuk_detail::class, 'barang_id');
    }

    public function transaksiKeluarDetails()
    {
        return $this->hasMany(Transaksi_keluar_detail::class, 'barang_id');
    }

    public function barangMasukDetails()
    {
        return $this->hasMany(Barang_masuk_detail::class, 'barang_id');
    }
}
