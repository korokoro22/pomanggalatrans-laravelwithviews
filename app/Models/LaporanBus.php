<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Transaksi_keluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanBusController extends Controller
{
    public function index(Request $request)
    {
        $busList = Bus::orderBy('nama_bus')->get();

        $query = Transaksi_keluar::with('bus', 'details')
                                  ->orderBy('tanggal', 'desc');

        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $transaksis = $query->get();

        return view('laporan-bus.index', compact('transaksis', 'busList'));
    }

    public function show(Request $request, $busId)
    {
        $bus = Bus::findOrFail($busId);

        $query = Transaksi_keluar::with('details')
                                  ->where('bus_id', $busId)
                                  ->orderBy('tanggal', 'desc');

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $transaksis = $query->get();

        // Kelompokkan per bulan-tahun
        $grouped = $transaksis->groupBy(function ($t) {
            return \Carbon\Carbon::parse($t->tanggal)->format('Y-m');
        })->sortKeysDesc();

        return view('laporan-bus.show', compact('bus', 'grouped'));
    }

    public function exportPdf(Request $request)
    {
        $busList = Bus::orderBy('nama_bus')->get();

        $query = Transaksi_keluar::with('bus', 'details')
                                  ->orderBy('tanggal', 'desc');

        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $transaksis = $query->get();

        $pdf = Pdf::loadView('laporan-bus.pdf-index', compact('transaksis'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-bus-' . now()->format('d-m-Y') . '.pdf');
    }

    public function exportPdfShow(Request $request, $busId)
    {
        $bus = Bus::findOrFail($busId);

        $query = Transaksi_keluar::with('details')
                                  ->where('bus_id', $busId)
                                  ->orderBy('tanggal', 'desc');

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $transaksis = $query->get();

        $grouped = $transaksis->groupBy(function ($t) {
            return \Carbon\Carbon::parse($t->tanggal)->format('Y-m');
        })->sortKeysDesc();

        $pdf = Pdf::loadView('laporan-bus.pdf-show', compact('bus', 'grouped'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-bus-' . Str::slug($bus->nama_bus) . '.pdf');
    }
}