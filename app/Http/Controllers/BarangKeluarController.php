<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Bus;
use App\Models\Paket_service;
use App\Models\Transaksi_keluar;
use App\Models\Transaksi_keluar_detail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller {
    public function index(Request $request)
    {
        $query = Transaksi_keluar::normal()
                    ->with(['bus', 'details'])
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

        $transaksiKeluars = $query->paginate(10)->withQueryString();
        $busList          = Bus::orderBy('nama_bus')->get();

        return view('barang-keluar.index', compact('transaksiKeluars', 'busList'));
    }

    public function create()
    {
        $busList = Bus::orderBy('nama_bus')->get();
        $barangs = Barang::where('stok_saat_ini', '>', 0)
                     ->orderBy('nama_barang')
                     ->get()
                     ->map(fn($b) => [
                         'id'            => $b->id,
                         'kode_barang'   => $b->kode_barang,
                         'nama_barang'   => $b->nama_barang,
                         'foto'          => $b->foto ? asset('storage/' . $b->foto) : null,
                         'harga_jual'    => $b->harga_jual,
                         'satuan'        => $b->satuan,
                         'stok_saat_ini' => $b->stok_saat_ini,
                         'gudang'        => $b->gudang,
                         'tanggal_masuk' => $b->tanggal_masuk
                             ? \Carbon\Carbon::parse($b->tanggal_masuk)->format('d-m-Y')
                             : '-',
                     ])
                     ->keyBy('id');

        return view('barang-keluar.create', compact('busList', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_id'                => 'required|exists:bus,id',
            'tanggal'               => 'required|date',
            'total_transaksi'       => 'required|numeric|min:0',
            'items'                 => 'required|array|min:1',
            'items.*.tipe'          => 'required|in:paket_service,per_item,biaya_pengerjaan',
            'items.*.harga_satuan'  => 'nullable|numeric|min:0',
            'items.*.biaya_pengerjaan' => 'nullable|numeric|min:0',
            'items.*.keterangan'    => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $totalTransaksi = 0;

                $transaksi = Transaksi_keluar::create([
                    'bus_id'          => $request->bus_id,
                    'kategori'        => 'normal',
                    'tanggal'         => $request->tanggal,
                    'total_transaksi' => 0,
                ]);

                foreach ($request->items as $item) {

                    if ($item['tipe'] === 'paket_service') {

                        $paket = Paket_service::with('paketServiceItem.barang')
                                    ->findOrFail($item['paket_service_id']);

                        $subtotal = $paket->harga;

                        Transaksi_keluar_detail::create([
                            'transaksi_keluar_id' => $transaksi->id,
                            'tipe'                => 'paket_service',
                            'paket_service_id'    => $paket->id,
                            'nama_item'           => $paket->nama_paket,
                            'qty'                 => 1,
                            'harga_satuan'        => $paket->harga,
                            'subtotal'            => $subtotal,
                        ]);

                        foreach ($paket->paketServiceItem as $paketItem) {
                            $paketItem->barang->decrement('stok_saat_ini', $paketItem->qty);
                        }

                    } elseif ($item['tipe'] === 'biaya_pengerjaan') {

                        $biaya = $item['biaya_pengerjaan'] ?? 0;

                        if ($biaya <= 0) {
                            throw new \Exception("Biaya pengerjaan harus diisi dan lebih dari 0.");
                        }

                        $subtotal = $biaya;

                        Transaksi_keluar_detail::create([
                            'transaksi_keluar_id' => $transaksi->id,
                            'tipe'                => 'biaya_pengerjaan',
                            'nama_item'           => $item['keterangan'] ?? 'Biaya Pengerjaan',
                            'keterangan'          => $item['keterangan'] ?? null,
                            'qty'                 => 1,
                            'harga_satuan'        => $biaya,
                            'subtotal'            => $subtotal,
                        ]);

                    } else {

                        $barang = Barang::findOrFail($item['barang_id']);

                        if ($barang->stok_saat_ini < $item['qty']) {
                            throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok_saat_ini} {$barang->satuan}.");
                        }

                        $hargaSatuan = isset($item['harga_satuan']) && $item['harga_satuan'] > 0
                            ? $item['harga_satuan']
                            : $barang->harga_jual;

                        $subtotal = $hargaSatuan * $item['qty'];

                        Transaksi_keluar_detail::create([
                            'transaksi_keluar_id' => $transaksi->id,
                            'tipe'                => 'per_item',
                            'barang_id'           => $barang->id,
                            'nama_item'           => $barang->nama_barang,
                            'qty'                 => $item['qty'],
                            'satuan'              => $barang->satuan,
                            'harga_satuan'        => $hargaSatuan,
                            'subtotal'            => $subtotal,
                        ]);

                        $barang->decrement('stok_saat_ini', $item['qty']);
                    }

                    $totalTransaksi += $subtotal;
                }

                $transaksi->update(['total_transaksi' => $totalTransaksi]);
            });

        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }

        return redirect()->route('barang-keluar.index')
                        ->with('success', 'Transaksi keluar berhasil disimpan.');
    }

    public function show($id)
    {
        $transaksi = Transaksi_keluar::normal()
            ->with([
                'bus',
                'details.paketService.paketServiceItem.barang',
                'details.barang.barangMasukDetails.barangMasuk',
            ])->findOrFail($id);

        return view('barang-keluar.show', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi_keluar::normal()
            ->with([
                'bus',
                'details.paketService',
                'details.barang',
            ])->findOrFail($id);

        $busList = Bus::orderBy('nama_bus')->get();

        $barangs = Barang::where('stok_saat_ini', '>', 0)
                    ->orWhereHas('transaksiKeluarDetail', function($q) use ($id) {
                        $q->where('transaksi_keluar_id', $id);
                    })
                    ->orderBy('nama_barang')
                    ->get()
                    ->map(fn($b) => [
                        'id'            => $b->id,
                        'kode_barang'   => $b->kode_barang,
                        'nama_barang'   => $b->nama_barang,
                        'foto'          => $b->foto ? asset('storage/' . $b->foto) : null,
                        'harga_jual'    => $b->harga_jual,
                        'satuan'        => $b->satuan,
                        'stok_saat_ini' => $b->stok_saat_ini,
                        'gudang'        => $b->gudang,
                        'tanggal_masuk' => $b->tanggal_masuk
                            ? \Carbon\Carbon::parse($b->tanggal_masuk)->format('d-m-Y')
                            : '-',
                    ])
                    ->keyBy('id');

        return view('barang-keluar.edit', compact('transaksi', 'busList', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bus_id'       => 'required|exists:bus,id',
            'tanggal'      => 'required|date',
            'items'        => 'required|array|min:1',
            'items.*.tipe' => 'required|in:paket_service,per_item,biaya_pengerjaan',
            'items.*.biaya_pengerjaan' => 'nullable|numeric|min:0',
            'items.*.keterangan'       => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {

                $transaksi = Transaksi_keluar::normal()
                    ->with([
                        'details.paketService.paketServiceItem.barang',
                        'details.barang',
                    ])->findOrFail($id);

                foreach ($transaksi->details as $detail) {
                    if ($detail->tipe === 'paket_service' && $detail->paketService) {
                        foreach ($detail->paketService->paketServiceItem as $psi) {
                            if ($psi->barang) {
                                $psi->barang->increment('stok_saat_ini', $psi->qty);
                            }
                        }
                    } else if ($detail->tipe === 'per_item' && $detail->barang) {
                        $detail->barang->increment('stok_saat_ini', $detail->qty);
                    }
                }

                $transaksi->details()->delete();

                $transaksi->update([
                    'bus_id'  => $request->bus_id,
                    'tanggal' => $request->tanggal,
                ]);

                $totalTransaksi = 0;

                foreach ($request->items as $item) {

                    if ($item['tipe'] === 'paket_service') {

                        $paket = Paket_service::with('paketServiceItem.barang')
                                    ->findOrFail($item['paket_service_id']);

                        $subtotal = $paket->harga;

                        Transaksi_keluar_detail::create([
                            'transaksi_keluar_id' => $transaksi->id,
                            'tipe'                => 'paket_service',
                            'paket_service_id'    => $paket->id,
                            'nama_item'           => $paket->nama_paket,
                            'qty'                 => 1,
                            'harga_satuan'        => $paket->harga,
                            'subtotal'            => $subtotal,
                        ]);

                        foreach ($paket->paketServiceItem as $psi) {
                            $psi->barang->decrement('stok_saat_ini', $psi->qty);
                        }

                        } elseif ($item['tipe'] === 'biaya_pengerjaan') {

                            $biaya = $item['biaya_pengerjaan'] ?? 0;

                            if ($biaya <= 0) {
                                throw new \Exception("Biaya pengerjaan harus diisi dan lebih dari 0.");
                            }

                            $subtotal = $biaya;

                            Transaksi_keluar_detail::create([
                                'transaksi_keluar_id' => $transaksi->id,
                                'tipe'                => 'biaya_pengerjaan',
                                'nama_item'           => $item['keterangan'] ?? 'Biaya Pengerjaan',
                                'keterangan'          => $item['keterangan'] ?? null,
                                'qty'                 => 1,
                                'harga_satuan'        => $biaya,
                                'subtotal'            => $subtotal,
                            ]);

                    } else {

                        $barang = Barang::findOrFail($item['barang_id']);

                        if ($barang->stok_saat_ini < $item['qty']) {
                            throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok_saat_ini} {$barang->satuan}.");
                        }

                        $subtotal = $barang->harga_jual * $item['qty'];

                        Transaksi_keluar_detail::create([
                            'transaksi_keluar_id' => $transaksi->id,
                            'tipe'                => 'per_item',
                            'barang_id'           => $barang->id,
                            'nama_item'           => $barang->nama_barang,
                            'qty'                 => $item['qty'],
                            'satuan'              => $barang->satuan,
                            'harga_satuan'        => $barang->harga_jual,
                            'subtotal'            => $subtotal,
                        ]);

                        $barang->decrement('stok_saat_ini', $item['qty']);
                    }

                    $totalTransaksi += $subtotal;
                }

                $transaksi->update(['total_transaksi' => $totalTransaksi]);
            });

        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }

        return redirect()->route('barang-keluar.index')
                        ->with('success', 'Transaksi keluar berhasil diupdate.');
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $transaksi = Transaksi_keluar::normal()
                    ->with([
                        'details.paketService.paketServiceItem.barang',
                        'details.barang',
                    ])->findOrFail($id);

                foreach ($transaksi->details as $detail) {
                    if ($detail->tipe === 'paket_service' && $detail->paketService) {
                        foreach ($detail->paketService->paketServiceItem as $psi) {
                            if ($psi->barang) {
                                $psi->barang->increment('stok_saat_ini', $psi->qty);
                            }
                        }
                    } else if ($detail->tipe === 'per_item' && $detail->barang) {
                        $detail->barang->increment('stok_saat_ini', $detail->qty);
                    }
                }

                $transaksi->details()->delete();
                $transaksi->delete();
            });

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }

        return redirect()->route('barang-keluar.index')
                        ->with('success', 'Transaksi keluar berhasil dihapus dan stok telah dikembalikan.');
    }

    public function getPaketByBus($busId)
    {
        $pakets = Paket_service::where('bus_id', $busId)
                    ->get(['id', 'nama_paket', 'harga']);

        return response()->json($pakets);
    }

    public function exportPdf(Request $request)
    {
        $query = Transaksi_keluar::normal()
                    ->with(['bus', 'details'])
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

        $transaksiKeluars = $query->get();
        $storagePath = storage_path('app/public/');

        $pdf = Pdf::loadView('barang-keluar.pdf-index', compact('transaksiKeluars', 'storagePath'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('transaksi-keluar-' . now()->format('d-m-Y') . '.pdf');
    }

    public function exportPdfShow($id)
    {
        $transaksi = Transaksi_keluar::normal()
            ->with([
                'bus',
                'details.paketService.paketServiceItem.barang',
                'details.barang.barangMasukDetails.barangMasuk',
            ])->findOrFail($id);

        $storagePath = storage_path('app/public/');

        $pdf = Pdf::loadView('barang-keluar.pdf-show', compact('transaksi', 'storagePath'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('transaksi-keluar-' . $transaksi->bus->plat_nomor . '-' . $transaksi->tanggal . '.pdf');
    }
}