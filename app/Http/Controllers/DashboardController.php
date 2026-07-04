<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Barang_masuk;
use App\Models\Bus;
use App\Models\Transaksi_keluar;

class DashboardController extends Controller {
    public function index()
    {
        $totalBarang = Barang::count();
        $totalBus    = Bus::count();

        $today = now()->format('Y-m-d');

        $barangMasukHariIni = Barang_masuk::whereDate('tanggal_masuk', $today)->count();
        $barangKeluarHariIni = Transaksi_keluar::whereDate('tanggal', $today)->count();

        // Aktivitas masuk — ambil 10 terbaru
        $aktivitasMasuk = Barang_masuk::with('details')
            ->latest('tanggal_masuk')
            ->take(10)
            ->get()
            ->flatMap(function ($masuk) {
                return $masuk->details->map(function ($detail) use ($masuk) {
                    return [
                        'tanggal' => $masuk->tanggal_masuk,
                        'jenis'   => 'masuk',
                        'nama'    => $detail->nama_barang,
                        'qty'     => $detail->qty,
                    ];
                });
            });

        // Aktivitas keluar — ambil 10 terbaru
        $aktivitasKeluar = Transaksi_keluar::with('details')
            ->latest('tanggal')
            ->take(10)
            ->get()
            ->flatMap(function ($keluar) {
                return $keluar->details->map(function ($detail) use ($keluar) {
                    return [
                        'tanggal' => $keluar->tanggal,
                        'jenis'   => 'keluar',
                        'nama'    => $detail->nama_item,
                        'qty'     => $detail->qty,
                    ];
                });
            });

        // Gabungkan, urutkan tanggal terbaru, ambil 10
        $aktivitasTerbaru = $aktivitasMasuk
            ->merge($aktivitasKeluar)
            ->sortByDesc('tanggal')
            ->take(10)
            ->values();

        return view('dashboard', compact(
            'totalBarang',
            'totalBus',
            'barangMasukHariIni',
            'barangKeluarHariIni',
            'aktivitasTerbaru'
        ));
    }
}