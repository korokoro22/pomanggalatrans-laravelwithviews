<?php

use App\Http\Controllers\BarangKeluarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataBusController;
use App\Http\Controllers\KeuanganArmadaController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\PaketServiceController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/masterbarang', function () {
    return view('masterbarang');
});

Route::get('/barangmasuk', function () {
    return view('barangmasuk');
});

Route::get('/barangkeluar', function () {
    return view('barangkeluar');
});

Route::get('/databus', function () {
    return view('databus');
});

Route::get('/keuanganarmada', function () {
    return view('keuanganarmada');
}); 

Route::resource('master-barang', MasterBarangController::class);
Route::get('master-barang-export', [MasterBarangController::class, 'exportPdf'])->name('master-barang.export-pdf');
Route::get('master-barang-export/{id}', [MasterBarangController::class, 'exportPdfShow'])->name('master-barang.export-pdf-show');
Route::get('master-barang-json/{id}', [MasterBarangController::class, 'getJson'])->name('master-barang.json');

Route::resource('dashboard', DashboardController::class);

Route::resource('barang-masuk', BarangMasukController::class);
Route::get('barang-masuk-export', [BarangMasukController::class, 'exportPdf'])->name('barang-masuk.export-pdf');
Route::get('barang-masuk-export/{id}', [BarangMasukController::class, 'exportPdfShow'])->name('barang-masuk.export-pdf-show');

Route::resource('barang-keluar', BarangKeluarController::class);
Route::get('barang-keluar-export-pdf', [BarangKeluarController::class, 'exportPdf'])->name('barang-keluar.export-pdf');
Route::get('barang-keluar-export-pdf/{id}', [BarangKeluarController::class, 'exportPdfShow'])->name('barang-keluar.export-pdf-show');
Route::get('barang-keluar/paket-by-bus/{busId}', [BarangKeluarController::class, 'getPaketByBus'])->name('barang-keluar.paket-by-bus');

Route::resource('data-bus', DataBusController::class);
Route::get('data-bus-export', [DataBusController::class, 'exportPdf'])->name('data-bus.export-pdf');
Route::get('data-bus-export/{id}', [DataBusController::class, 'exportPdfShow'])->name('data-bus.export-pdf-show');

Route::resource('keuangan-armada', KeuanganArmadaController::class);
Route::get('keuangan-armada-export', [KeuanganArmadaController::class, 'exportPdf'])->name('keuangan-armada.export-pdf');
Route::get('keuangan-armada-export/{id}', [KeuanganArmadaController::class, 'exportPdfShow'])->name('keuangan-armada.export-pdf-show');

Route::resource('paket-service', PaketServiceController::class);
Route::get('paket-service-export', [PaketServiceController::class, 'exportPdf'])->name('paket-service.export-pdf');
Route::get('paket-service-export/{id}', [PaketServiceController::class, 'exportPdfShow'])->name('paket-service.export-pdf-show');