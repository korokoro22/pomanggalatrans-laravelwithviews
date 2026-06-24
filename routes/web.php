<?php

use App\Http\Controllers\BarangKeluarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataBusController;
use App\Http\Controllers\KeuanganArmadaController;
use App\Http\Controllers\MasterBarangController;

Route::get('/', function () {
    return view('welcome');
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
Route::resource('dashboard', DashboardController::class);
Route::resource('barang-masuk', BarangMasukController::class);
Route::resource('barang-keluar', BarangKeluarController::class);
Route::resource('data-bus', DataBusController::class);
Route::resource('keuangan-armada', KeuanganArmadaController::class);
