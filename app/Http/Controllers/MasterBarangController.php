<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MasterBarangController extends Controller {
    
    public function index(Request $request)
    {
        $query = Barang::query();
        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }
        if ($request->filled('gudang')) {
            $query->where('gudang', $request->gudang);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_masuk', $request->tanggal);
        } else {
            if ($request->filled('bulan')) {
                $query->whereMonth('tanggal_masuk', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_masuk', $request->tahun);
            }
        }
        $barangs = $query
            ->selectRaw('
                nama_barang,
                COUNT(*) as jumlah_batch,
                SUM(stok_saat_ini) as total_stok,
                MAX(tanggal_masuk) as tanggal_masuk_terakhir,
                MIN(harga_jual) as harga_terendah,
                MAX(harga_jual) as harga_tertinggi
            ')
            ->groupBy('nama_barang')
            ->orderByDesc('tanggal_masuk_terakhir')
            ->paginate(10)
            ->withQueryString();
    
        // tambahkan encoded_nama untuk link detail
        $barangs->getCollection()->transform(function ($item) {
            $item->encoded_nama = base64_encode($item->nama_barang);
            return $item;
        });
    
        $namaList = $barangs->pluck('nama_barang');
        $samples = Barang::whereIn('nama_barang', $namaList)
            ->orderByDesc('tanggal_masuk')
            ->get()
            ->groupBy('nama_barang')
            ->map(fn($group) => $group->first());
    
        return view('master-barang.index', compact('barangs', 'samples'));
    }
    
    public function showByNama(Request $request, $encodedNama)
    {
        $nama_barang = base64_decode($encodedNama);
    
        $query = Barang::where('nama_barang', $nama_barang);
        if ($request->filled('gudang')) {
            $query->where('gudang', $request->gudang);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_masuk', $request->tanggal);
        } else {
            if ($request->filled('bulan')) {
                $query->whereMonth('tanggal_masuk', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_masuk', $request->tahun);
            }
        }
        $barangs = $query->orderByDesc('tanggal_masuk')->get();
    
        // kirim juga versi encoded, dibutuhkan buat link2 di view (form filter, reset, export pdf)
        $encoded_nama = $encodedNama;
    
        return view('master-barang.show-by-nama', compact('barangs', 'nama_barang', 'encoded_nama'));
    }

    public function show($id)
    {
        $barang = Barang::with([
            'transaksiKeluarDetail.transaksiKeluar.bus',
            'barangMasukDetail.barangMasuk'
        ])->findOrFail($id);
        
        $encoded_nama = base64_encode($barang->nama_barang);

        return view('master-barang.show', compact('barang', 'encoded_nama'));
    }

    // Controller
    public function exportPdfByNama(Request $request, $nama_barang)
    {
        $query = Barang::where('nama_barang', $nama_barang);

        if ($request->filled('gudang')) {
            $query->where('gudang', $request->gudang);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_masuk', $request->tanggal);
        } else {
            if ($request->filled('bulan')) {
                $query->whereMonth('tanggal_masuk', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_masuk', $request->tahun);
            }
        }

        $barangs = $query->orderByDesc('tanggal_masuk')->get();

        $pdf = Pdf::loadView('master-barang.pdf-show-by-nama', compact('barangs', 'nama_barang'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('batch-' . Str::slug($nama_barang) . '-' . now()->format('d-m-Y') . '.pdf');
    }

    public function exportPdf(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }

        if ($request->filled('gudang')) {
            $query->where('gudang', $request->gudang);
        }


        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_masuk', $request->tanggal);
        } else {
            if ($request->filled('bulan')) {
                $query->whereMonth('tanggal_masuk', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_masuk', $request->tahun);
            }
        }

        $barangs = $query
            ->selectRaw('
                nama_barang,
                COUNT(*) as jumlah_batch,
                SUM(stok_saat_ini) as total_stok,
                MAX(tanggal_masuk) as tanggal_masuk_terakhir,
                MIN(harga_jual) as harga_terendah,
                MAX(harga_jual) as harga_tertinggi
            ')
            ->groupBy('nama_barang')
            ->orderByDesc('tanggal_masuk_terakhir')
            ->get();

        $pdf = Pdf::loadView('master-barang.pdf-index', compact('barangs'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('master-barang-' . now()->format('d-m-Y') . '.pdf');
    }

    public function exportPdfShow($id)
    {
        $barang = Barang::with('transaksiKeluarDetail.transaksiKeluar.bus')->findOrFail($id);
        $storagePath = storage_path('app/public/');

        $pdf = Pdf::loadView('master-barang.pdf-show', compact('barang', 'storagePath'))
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
            'kondisi'     => $barang->kondisi,
            'harga_jual'  => $barang->harga_jual,
            'satuan'      => $barang->satuan,
            'stok_saat_ini' => $barang->stok_saat_ini,
            'foto'        => $barang->foto ? asset('storage/' . $barang->foto) : null,
        ]);
    }
}