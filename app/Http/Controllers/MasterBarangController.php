<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterBarangController extends Controller {
    public function index(Request $request)
    {
        $query = Barang::orderBy('created_at', 'desc');

        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_masuk', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_masuk', $request->tahun);
        }

        $barangs = $query->get();

        return view('master-barang.index', compact('barangs'));
    }

    public function show($id)
    {
        $barang = Barang::with([
            'transaksiKeluarDetail.transaksiKeluar.bus',
            'barangMasukDetail.barangMasuk'
        ])->findOrFail($id);

        return view('master-barang.show', compact('barang'));
    }

    public function exportPdf(Request $request)
    {
        $query = Barang::orderBy('created_at', 'desc');

        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_masuk', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_masuk', $request->tahun);
        }

        $barangs = $query->get();

        $pdf = Pdf::loadView('master-barang.pdf-index', compact('barangs'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('master-barang-' . now()->format('d-m-Y') . '.pdf');
    }

    public function exportPdfShow($id)
    {
        $barang = Barang::with('transaksiKeluarDetail.transaksiKeluar.bus')->findOrFail($id);

        $pdf = Pdf::loadView('master-barang.pdf-show', compact('barang'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('barang-' . $barang->kode_barang . '.pdf');
    }

    public function getJson($id)
    {
        $barang = Barang::findOrFail($id);

        return response()->json([
            'id'          => $barang->id,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'kategori'    => $barang->kategori,
            'harga_jual'  => $barang->harga_jual,
            'satuan'      => $barang->satuan,
            'stok_saat_ini' => $barang->stok_saat_ini,
            'foto'        => $barang->foto ? asset('storage/' . $barang->foto) : null,
        ]);
    }
}