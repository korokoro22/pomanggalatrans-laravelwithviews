<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class DataBusController extends Controller {
    public function index(Request $request)
    {
        $query = Bus::orderBy('created_at', 'desc');

        if ($request->filled('nama_bus')) {
            $query->where('nama_bus', 'like', '%' . $request->nama_bus . '%');
        }

        if ($request->filled('plat_nomor')) {
            $query->where('plat_nomor', 'like', '%' . $request->plat_nomor . '%');
        }

        $buses = $query->get();

        return view('data-bus.index', compact('buses'));
    }

    public function create()
    {
        return view('data-bus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bus'    => 'required|string',
            'plat_nomor'  => 'required|string|unique:bus,plat_nomor',
            'rute_trayek' => 'nullable|string',
            'nama_driver' => 'required|string',
        ]);

        Bus::create([
            'nama_bus'    => $request->nama_bus,
            'plat_nomor'  => $request->plat_nomor,
            'rute_trayek' => $request->rute_trayek,
            'nama_driver' => $request->nama_driver,
        ]);

        return redirect()->route('data-bus.index')
                         ->with('success', 'Data bus berhasil ditambahkan.');
    }

    public function show($id)
    {
        $bus = Bus::with([
            'transaksiKeluar.details.barang'
        ])->findOrFail($id);

        return view('data-bus.show', compact('bus'));
    }

    public function edit($id)
    {
        $bus = Bus::findOrFail($id);
        return view('data-bus.edit', compact('bus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bus'    => 'required|string',
            'plat_nomor'  => 'required|string|unique:bus,plat_nomor,' . $id,
            'rute_trayek' => 'nullable|string',
            'nama_driver' => 'required|string',
        ]);

        $bus = Bus::findOrFail($id);

        $bus->update([
            'nama_bus'    => $request->nama_bus,
            'plat_nomor'  => $request->plat_nomor,
            'rute_trayek' => $request->rute_trayek,
            'nama_driver' => $request->nama_driver,
        ]);

        return redirect()->route('data-bus.index')
                         ->with('success', 'Data bus berhasil diupdate.');
    }

    public function destroy($id)
    {
        $bus = Bus::findOrFail($id);
        $bus->delete();

        return redirect()->route('data-bus.index')
                         ->with('success', 'Data bus berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $query = Bus::orderBy('created_at', 'desc');

        if ($request->filled('nama_bus')) {
            $query->where('nama_bus', 'like', '%' . $request->nama_bus . '%');
        }

        if ($request->filled('plat_nomor')) {
            $query->where('plat_nomor', 'like', '%' . $request->plat_nomor . '%');
        }

        $buses = $query->get();

        $pdf = Pdf::loadView('data-bus.pdf-index', compact('buses'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('data-bus-' . now()->format('d-m-Y') . '.pdf');
    }

    public function exportPdfShow($id)
    {
        $bus = Bus::with('transaksiKeluar.details')->findOrFail($id);

        $pdf = Pdf::loadView('data-bus.pdf-show', compact('bus'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('bus-' . Str::slug($bus->plat_nomor) . '.pdf');
    }
}