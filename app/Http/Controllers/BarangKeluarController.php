<?php

namespace App\Http\Controllers;

class BarangKeluarController extends Controller {
    public function index() {
        return view('barang-keluar/index');
    }

    public function create() {
        return view('barang-keluar/create');
    }
}