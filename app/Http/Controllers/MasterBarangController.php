<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class MasterBarangController extends Controller {
    public function index()
    {
        $barangs = Barang::orderBy('created_at', 'desc')->get();
        return view('master-barang.index', compact('barangs'));
    }

    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('master-barang.show', compact('barang'));
    }
}