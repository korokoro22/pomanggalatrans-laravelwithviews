<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Barang_masuk;
use App\Models\Barang_masuk_detail;
use App\Models\Bus;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;

class BarangMasukController extends Controller {
    public function index(Request $request)
    {
        $query = Barang_masuk::with('details')->orderBy('created_at', 'desc');

        if ($request->filled('no_invoice')) {
            $query->where('no_invoice', 'like', '%' . $request->no_invoice . '%');
        }

        if ($request->filled('nama_barang')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
        }

        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%' . $request->supplier . '%');
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

        // if ($request->filled('bulan')) {
        //     $query->whereMonth('tanggal_masuk', $request->bulan);
        // }

        // if ($request->filled('tahun')) {
        //     $query->whereYear('tanggal_masuk', $request->tahun);
        // }

        if ($request->filled('kategori_nota')) {
            $query->where('kategori_nota', $request->kategori_nota);
        }

        // ============================================
        // REVISI: FILTER JENIS MASUK
        // ============================================
        if ($request->filled('jenis_masuk')) {
            $query->where('jenis_masuk', $request->jenis_masuk);
        }

        // ============================================
        // REVISI: JENIS PEMBELIAN
        // Filter berdasarkan jenis_pembelian
        // ============================================
        if ($request->filled('jenis_pembelian')) {
            $query->where('jenis_pembelian', $request->jenis_pembelian);
        }

        $barangMasuks = $query->get();

        return view('barang-masuk.index', compact('barangMasuks'));
    }

    // public function create()
    // {
    //     $busList = Bus::orderBy('nama_bus')->get();
        
    //     $barangs = Barang::orderBy('nama_barang')
    //                  ->get()
    //                  ->map(fn($b) => [
    //                      'id'            => $b->id,
    //                      'kode_barang'   => $b->kode_barang,
    //                      'nama_barang'   => $b->nama_barang,
    //                      'foto'          => $b->foto ? asset('storage/' . $b->foto) : null,
    //                      'harga_jual'    => $b->harga_jual,
    //                      'satuan'        => $b->satuan,
    //                      'stok_saat_ini' => $b->stok_saat_ini,
    //                      'gudang'        => $b->gudang,
    //                      'tanggal_masuk' => $b->tanggal_masuk
    //                          ? \Carbon\Carbon::parse($b->tanggal_masuk)->format('d-m-Y')
    //                          : '-',
    //                  ])
    //                  ->keyBy('id');

    //     // TAMBAHAN: Ambil daftar kategori unik dari database, hilangkan 'item_bebas' agar bisa ditaruh manual di paling bawah
    //     $kategoriList = Barang::select('kategori')
    //                     ->whereNotNull('kategori')
    //                     ->distinct()
    //                     ->pluck('kategori')
    //                     ->reject(fn($k) => $k === 'item_bebas')
    //                     ->values();

    //     // Pastikan $kategoriList dipassing ke view
    //     return view('barang-masuk.create', compact('busList', 'barangs', 'kategoriList'));
    // }
    
    public function create()
    {
        $busList = Bus::orderBy('nama_bus')->get();
    
        $barangs = Barang::orderBy('nama_barang')
            ->get()
            ->map(function ($b) {
                return [
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
                ];
            })
            ->keyBy('id');
    
        $kategoriList = Barang::select('kategori')
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->reject(function ($k) {
                return $k === 'item_bebas';
            })
            ->values();
    
        return view(
            'barang-masuk.create',
            compact('busList', 'barangs', 'kategoriList')
        );
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'jenis_masuk'           => 'required|in:nota,stok_awal',
    //         'tanggal_masuk'         => 'required|date',
    //         'no_invoice'            => 'required_if:jenis_masuk,nota|nullable|string',
    //         'supplier'              => 'required_if:jenis_masuk,nota|nullable|string',
    //         'penerima'              => 'required_if:jenis_masuk,nota|nullable|string',
    //         'bukti_nota'            => 'nullable|image|mimes:jpg,jpeg,png',
    //         'kategori_nota'         => 'in:nota_bengkel,nota_jalan',
    
    //         'items'                      => 'required|array|min:1',
    //         'items.*.jenis'              => 'required|in:barang_baru,barang_ada',
    //         'items.*.nama_barang'        => 'required_if:items.*.jenis,barang_baru|nullable|string',
    //         'items.*.barang_id'          => 'required_if:items.*.jenis,barang_ada|nullable|string',
    //         'items.*.kategori'           => 'required|string',
    //         'items.*.kode_barang'        => 'string',
    //         'items.*.foto'               => 'nullable|image|mimes:jpg,jpeg,png',
    //         'items.*.qty'                => 'required|numeric|min:0.01',
    //         'items.*.satuan'             => 'required|string',
    //         'items.*.qty_satuan'         => 'required|numeric|min:0.01',
    //         'items.*.harga_jual'         => 'required|numeric|min:0',
    //         'items.*.subtotal'           => 'required|numeric|min:0',
    //         'items.*.gudang'             => 'required|in:gudang_utama,gudang_2,gudang_3',
    //     ]);
    
    //     DB::transaction(function () use ($request) {
    //         $manager    = new ImageManager(new Driver());
    //         $jenisMasuk = $request->jenis_masuk;
    
    //         // Langkah 1 — upload & kompres bukti nota (hanya untuk mode 'nota')
    //         $buktiNotaPath = null;
    //         if ($jenisMasuk === 'nota' && $request->hasFile('bukti_nota')) {
    //             $ext      = $request->file('bukti_nota')->extension();
    //             $namaFile = 'nota-' . Str::slug($request->no_invoice) . '.' . $ext;
    //             $folder   = storage_path('app/public/bukti_nota');
    
    //             if (!file_exists($folder)) {
    //                 mkdir($folder, 0755, true);
    //             }
    
    //             $manager->decode($request->file('bukti_nota'))
    //                     ->scaleDown(width: 1200)
    //                     ->encode(new JpegEncoder(quality: 75))
    //                     ->save($folder . '/' . $namaFile);
    
    //             $buktiNotaPath = 'bukti_nota/' . $namaFile;
    //         }
    
    //         // Langkah 2 — simpan header nota ke barang_masuk
    //         $barangMasuk = Barang_masuk::create([
    //             'jenis_masuk'   => $jenisMasuk,
    //             'no_invoice'    => $jenisMasuk === 'nota' ? $request->no_invoice : null,
    //             'supplier'      => $jenisMasuk === 'nota' ? $request->supplier : null,
    //             'kategori_nota' => 'nota_bengkel',
    //             'penerima'      => $jenisMasuk === 'nota' ? $request->penerima : null,
    //             'tanggal_masuk' => $request->tanggal_masuk,
    //             'bukti_nota'    => $buktiNotaPath,
    //         ]);
    
    //         // Langkah 3 — loop tiap item (TIDAK ADA PERUBAHAN dari kode kamu sekarang)
    //         foreach ($request->items as $index => $item) {
    
    //             $barangReferensi = null;
    //             if ($item['jenis'] === 'barang_ada') {
    //                 $barangReferensi = Barang::findOrFail($item['barang_id']);
    //             }
    
    //             $namaBarang = $item['jenis'] === 'barang_ada'
    //                 ? $barangReferensi->nama_barang
    //                 : $item['nama_barang'];
    
    //             // Upload & kompres foto barang
    //             $fotoPath = null;
    //             $fotoFile = $request->file("items.{$index}.foto");
    //             if ($fotoFile && $fotoFile->isValid()) {
    //                 $ext      = $fotoFile->extension();
    //                 $namaFoto = 'barang-' . Str::slug($namaBarang) . '-' . time() . '.' . $ext;
    //                 $folder   = storage_path('app/public/barang');
    
    //                 if (!file_exists($folder)) {
    //                     mkdir($folder, 0755, true);
    //                 }
    
    //                 $manager->decode($fotoFile)
    //                         ->scaleDown(width: 800)
    //                         ->encode(new JpegEncoder(quality: 75))
    //                         ->save($folder . '/' . $namaFoto);
    
    //                 $fotoPath = 'barang/' . $namaFoto;
    //             }
    
    //             // Fallback: kalau jenis "barang_ada" dan tidak upload foto baru, pakai foto dari barang yang dipilih
    //             if (!$fotoPath && $item['jenis'] === 'barang_ada' && $barangReferensi && $barangReferensi->foto) {
    //                 $fotoPath = $barangReferensi->foto;
    //             }
    
    //             // Rapikan string kategori jika user mengetik baru
    //             $kategoriInput = Str::slug($item['kategori'], '_');
    
    //             // Langkah 4 — buat baris baru di tabel barang
    //             $barang = Barang::create([
    //                 'kode_barang'   => $item['kode_barang'],
    //                 'nama_barang'   => $namaBarang,
    //                 'kategori'      => $kategoriInput,
    //                 'gudang'        => $item['gudang'],
    //                 'foto'          => $fotoPath,
    //                 'qty'           => $item['qty'],
    //                 'satuan'        => $item['satuan'],
    //                 'qty_satuan'    => $item['qty_satuan'],
    //                 'stok_saat_ini' => $item['qty_satuan'],
    //                 'harga_jual'    => $item['harga_jual'],
    //                 'tanggal_masuk' => $request->tanggal_masuk,
    //                 'qr_code'       => null,
    //             ]);
    
    //             // Langkah 5 — generate QR code
    //             $qrData = (string) $barang->id;
    
    //             $qrFolder = storage_path('app/public/qrcode');
    //             if (!file_exists($qrFolder)) {
    //                 mkdir($qrFolder, 0755, true);
    //             }
    
    //             $namaQr     = 'barang-' . Str::slug($barang->nama_barang) . '-' . $barang->id . '.svg';
    //             $qrFileName = 'qrcode/' . $namaQr;
    //             $qrFilePath = storage_path('app/public/' . $qrFileName);
    
    //             QrCode::format('svg')
    //                 ->size(200)
    //                 ->errorCorrection('H')
    //                 ->generate($qrData, $qrFilePath);
    
    //             // Langkah 6 — update kolom qr_code di barang
    //             $barang->update(['qr_code' => $qrFileName]);
    
    //             // Langkah 7 — simpan detail ke barang_masuk_detail
    //             Barang_masuk_detail::create([
    //                 'barang_masuk_id' => $barangMasuk->id,
    //                 'barang_id'       => $barang->id,
    //                 'nama_barang'     => $namaBarang,
    //                 'foto'            => $fotoPath,
    //                 'qty'             => $item['qty'],
    //                 'satuan'          => $item['satuan'],
    //                 'qty_satuan'      => $item['qty_satuan'],
    //                 'harga_jual'      => $item['harga_jual'],
    //                 'subtotal'        => $item['subtotal'],
    //             ]);
    //         }
    //     });
    
    //     return redirect()->route('barang-masuk.index')
    //         ->with('success', 'Data barang masuk berhasil disimpan.');
    // }
    
    public function store(Request $request)
{
    $request->validate([
        'jenis_masuk'           => 'required|in:nota,stok_awal,barang_bekas',
        'tanggal_masuk'         => 'required|date',
        'no_invoice'            => 'required_if:jenis_masuk,nota|nullable|string',
        'supplier'              => 'required_if:jenis_masuk,nota|nullable|string',
        'penerima'              => 'required_if:jenis_masuk,nota|nullable|string',
        'bukti_nota'            => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
        'kategori_nota'         => 'in:nota_bengkel,nota_jalan',
        // REVISI:NULLABLE JENIS PEMBELIAN — hanya wajib untuk nota
        'jenis_pembelian'       => 'required_if:jenis_masuk,nota|in:pembelian_toko,pembelian_langsung,null',
        'bus_id'                => 'required_if:jenis_masuk,barang_bekas|nullable|exists:bus,id',
        'keterangan'            => 'nullable|string|max:500',

        'items'                      => 'required|array|min:1',
        'items.*.jenis'              => 'required|in:barang_baru,barang_ada',
        'items.*.nama_barang'        => 'required_if:items.*.jenis,barang_baru|nullable|string',
        'items.*.barang_id'          => 'required_if:items.*.jenis,barang_ada|nullable|string',
        'items.*.kategori'           => 'required|string',
        'items.*.kode_barang'        => 'string',
        'items.*.foto'               => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
        'items.*.qty'                => 'required|numeric|min:0.01',
        'items.*.satuan'             => 'required|string',
        'items.*.qty_satuan'         => 'required|numeric|min:0.01',
        'items.*.harga_jual'         => 'required|numeric|min:0',
        'items.*.subtotal'           => 'required|numeric|min:0',
        'items.*.gudang'             => 'required|in:gudang_utama,gudang_2,gudang_3',
    ]);

    DB::transaction(function () use ($request) {
        $manager    = new ImageManager(new Driver());
        $jenisMasuk = $request->jenis_masuk;

        // Langkah 1 — upload & kompres bukti nota (hanya untuk mode 'nota')
        $buktiNotaPath = null;
        if ($jenisMasuk === 'nota' && $request->hasFile('bukti_nota')) {
            $ext      = $request->file('bukti_nota')->extension();
            $namaFile = 'nota-' . Str::slug($request->no_invoice) . '.' . $ext;
            $folder   = storage_path('app/public/bukti_nota');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $manager->decode($request->file('bukti_nota'))
                    ->scaleDown(width: 1200)
                    ->encode(new JpegEncoder(quality: 75))
                    ->save($folder . '/' . $namaFile);

            $buktiNotaPath = 'bukti_nota/' . $namaFile;
        }

        // Langkah 2 — simpan header nota ke barang_masuk
        $barangMasuk = Barang_masuk::create([
            'jenis_masuk'   => $jenisMasuk,
            'no_invoice'    => $jenisMasuk === 'nota' ? $request->no_invoice : null,
            'supplier'      => $jenisMasuk === 'nota' ? $request->supplier : null,
            'kategori_nota' => 'nota_bengkel',
            'jenis_pembelian' => $request->jenis_pembelian, // REVISI: JENIS PEMBELIAN
            'penerima'      => $jenisMasuk === 'nota' ? $request->penerima : null,
            'tanggal_masuk' => $request->tanggal_masuk,
            'bukti_nota'    => $buktiNotaPath,
            'bus_id'        => $jenisMasuk === 'barang_bekas' ? $request->bus_id : null,
            'keterangan'    => in_array($jenisMasuk, ['stok_awal', 'barang_bekas']) ? $request->keterangan : null,
        ]);

        // Tentukan kondisi barang berdasarkan jenis_masuk
        $kondisiBarang = $jenisMasuk === 'barang_bekas' ? 'bekas' : 'baru';

        // Langkah 3 — loop tiap item
        foreach ($request->items as $index => $item) {

            $barangReferensi = null;
            if ($item['jenis'] === 'barang_ada') {
                $barangReferensi = Barang::findOrFail($item['barang_id']);
            }

            $namaBarang = $item['jenis'] === 'barang_ada'
                ? $barangReferensi->nama_barang
                : $item['nama_barang'];

            // Upload & kompres foto barang
            $fotoPath = null;
            $fotoFile = $request->file("items.{$index}.foto");
            if ($fotoFile && $fotoFile->isValid()) {
                $ext      = $fotoFile->extension();
                $namaFoto = 'barang-' . Str::slug($namaBarang) . '-' . time() . '.' . $ext;
                $folder   = storage_path('app/public/barang');

                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                $manager->decode($fotoFile)
                        ->scaleDown(width: 800)
                        ->encode(new JpegEncoder(quality: 75))
                        ->save($folder . '/' . $namaFoto);

                $fotoPath = 'barang/' . $namaFoto;
            }

            if (!$fotoPath && $item['jenis'] === 'barang_ada' && $barangReferensi && $barangReferensi->foto) {
                $fotoPath = $barangReferensi->foto;
            }

            $kategoriInput = Str::slug($item['kategori'], '_');

            // Langkah 4 — buat baris baru di tabel barang
            // ============================================
            // REVISI: JENIS PEMBELIAN
            // Isi jenis_pembelian saat buat barang baru
            // ============================================
            $barang = Barang::create([
                'kode_barang'   => $item['kode_barang'],
                'nama_barang'   => $namaBarang,
                'kategori'      => $kategoriInput,
                'kondisi'       => $kondisiBarang,
                'gudang'        => $item['gudang'],
                'jenis_pembelian' => $request->jenis_pembelian, // REVISI: JENIS PEMBELIAN
                'foto'          => $fotoPath,
                'qty'           => $item['qty'],
                'satuan'        => $item['satuan'],
                'qty_satuan'    => $item['qty_satuan'],
                'stok_saat_ini' => $item['qty_satuan'],
                'harga_jual'    => $item['harga_jual'],
                'tanggal_masuk' => $request->tanggal_masuk,
                'qr_code'       => null,
            ]);

            // Langkah 5 — generate QR code
            $qrData = (string) $barang->id;

            $qrFolder = storage_path('app/public/qrcode');
            if (!file_exists($qrFolder)) {
                mkdir($qrFolder, 0755, true);
            }

            $namaQr     = 'barang-' . Str::slug($barang->nama_barang) . '-' . $barang->id . '.svg';
            $qrFileName = 'qrcode/' . $namaQr;
            $qrFilePath = storage_path('app/public/' . $qrFileName);

            QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate($qrData, $qrFilePath);

            $barang->update(['qr_code' => $qrFileName]);

            // Langkah 6 — simpan detail ke barang_masuk_detail
            Barang_masuk_detail::create([
                'barang_masuk_id' => $barangMasuk->id,
                'barang_id'       => $barang->id,
                'nama_barang'     => $namaBarang,
                'foto'            => $fotoPath,
                'qty'             => $item['qty'],
                'satuan'          => $item['satuan'],
                'qty_satuan'      => $item['qty_satuan'],
                'harga_jual'      => $item['harga_jual'],
                'subtotal'        => $item['subtotal'],
            ]);
        }
    });

    return redirect()->route('barang-masuk.index')
        ->with('success', 'Data barang masuk berhasil disimpan.');
}
    
    public function show($id)
    {
        $barangMasuk = Barang_masuk::with([
            'details.barang.transaksiKeluarDetails.transaksiKeluar'
        ])->findOrFail($id);

        return view('barang-masuk.show', compact('barangMasuk'));
    }
    
    // public function edit($id)
    // {
    //     $barangMasuk = Barang_masuk::with('details.barang')->findOrFail($id);
    
    //     $existingBarangs = Barang::orderByDesc('tanggal_masuk')
    //         ->get()
    //         ->groupBy('nama_barang')
    //         ->map(function ($group) {
    //             $latest = $group->first();
    //             return [
    //                 'nama_barang' => $latest->nama_barang,
    //                 'foto'        => $latest->foto ? asset('storage/' . $latest->foto) : null,
    //                 'kategori'    => $latest->kategori,
    //             ];
    //         })
    //         ->values();
    
    //     $barangs = Barang::orderBy('nama_barang')
    //         ->get()
    //         ->map(fn($b) => [
    //             'id'            => $b->id,
    //             'kode_barang'   => $b->kode_barang,
    //             'nama_barang'   => $b->nama_barang,
    //             'foto'          => $b->foto ? asset('storage/' . $b->foto) : null,
    //             'harga_jual'    => $b->harga_jual,
    //             'satuan'        => $b->satuan,
    //             'stok_saat_ini' => $b->stok_saat_ini,
    //             'gudang'        => $b->gudang,
    //             'tanggal_masuk' => $b->tanggal_masuk ? \Carbon\Carbon::parse($b->tanggal_masuk)->format('d-m-Y') : '-',
    //         ])
    //         ->keyBy('id');
    
    //     $kategoriList = \App\Models\Barang::select('kategori')
    //                     ->whereNotNull('kategori')
    //                     ->distinct()
    //                     ->pluck('kategori')
    //                     ->reject(fn($k) => $k === 'item_bebas')
    //                     ->values();
    
    //     return view('barang-masuk.edit', compact('barangMasuk', 'existingBarangs', 'barangs', 'kategoriList'));
    // }
    
    // public function edit($id)
    // {
    //     $barangMasuk = Barang_masuk::with('details.barang')->findOrFail($id);
    //     $busList     = Bus::orderBy('nama_bus')->get();
    
    //     // ... sisanya sama seperti sebelumnya (existingBarangs, barangs, kategoriList) ...
    
    //     return view('barang-masuk.edit', compact('barangMasuk', 'existingBarangs', 'barangs', 'kategoriList', 'busList'));
    // }
    
    public function edit($id)
{
    $barangMasuk = Barang_masuk::with('details.barang')->findOrFail($id);
    $busList     = Bus::orderBy('nama_bus')->get();

    $existingBarangs = Barang::orderByDesc('tanggal_masuk')
        ->get()
        ->groupBy('nama_barang')
        ->map(function ($group) {
            $latest = $group->first();
            return [
                'nama_barang' => $latest->nama_barang,
                'foto'        => $latest->foto ? asset('storage/' . $latest->foto) : null,
                'kategori'    => $latest->kategori,
            ];
        })
        ->values();

    $barangs = Barang::orderBy('nama_barang')
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
            'tanggal_masuk' => $b->tanggal_masuk ? \Carbon\Carbon::parse($b->tanggal_masuk)->format('d-m-Y') : '-',
        ])
        ->keyBy('id');

    $kategoriList = Barang::select('kategori')
        ->whereNotNull('kategori')
        ->distinct()
        ->pluck('kategori')
        ->reject(fn($k) => $k === 'item_bebas')
        ->values();

    return view('barang-masuk.edit', compact('barangMasuk', 'existingBarangs', 'barangs', 'kategoriList', 'busList'));
}
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_masuk' => 'required|in:nota,stok_awal,barang_bekas',
            'tanggal_masuk'         => 'required|date',
            'no_invoice'            => 'required_if:jenis_masuk,nota|nullable|string',
            'supplier'              => 'required_if:jenis_masuk,nota|nullable|string',
            'penerima'              => 'required_if:jenis_masuk,nota|nullable|string',
            'bukti_nota'            => 'nullable|image|mimes:jpg,jpeg,png',
            // REVISI:NULLABLE JENIS PEMBELIAN — hanya wajib untuk nota
            'jenis_pembelian'       => 'required_if:jenis_masuk,nota|in:pembelian_toko,pembelian_langsung,null',
    
            'items'                 => 'required|array|min:1',
            'items.*.jenis'         => 'nullable|in:barang_baru,barang_ada',
            'items.*.nama_barang'   => 'required_if:items.*.jenis,barang_baru|nullable|string',
            'items.*.barang_id'     => 'required_if:items.*.jenis,barang_ada|nullable|string',
            'items.*.kode_barang'   => 'string',
            'items.*.kategori'      => 'required|string',
            'items.*.gudang'        => 'required|in:gudang_utama,gudang_2,gudang_3',
            'items.*.foto'          => 'nullable|image|mimes:jpg,jpeg,png',
            'bus_id'     => 'required_if:jenis_masuk,barang_bekas|nullable|exists:bus,id',
            'keterangan' => 'nullable|string|max:500',
            'items.*.qty'           => 'required|numeric|min:0.01',
            'items.*.satuan'        => 'required|string',
            'items.*.qty_satuan'    => 'required|numeric|min:0.01',
            'items.*.harga_jual'    => 'required|numeric|min:0',
            'items.*.subtotal'      => 'required|numeric|min:0',
        ]);
    
        DB::transaction(function () use ($request, $id) {
    
            $barangMasuk = Barang_masuk::with('details.barang')->findOrFail($id);
    
            // jenis_masuk TIDAK BISA diubah saat edit — selalu ambil dari data existing di DB,
            // abaikan apapun yang dikirim dari request (walau hidden input dikirim, ini demi keamanan
            // supaya tidak bisa dimanipulasi lewat modifikasi HTML manual)
            $jenisMasuk = $barangMasuk->jenis_masuk;
    
            // Update bukti nota jika ada file baru (hanya relevan untuk mode 'nota')
            $buktiNotaPath = $barangMasuk->bukti_nota;
            if ($jenisMasuk === 'nota' && $request->hasFile('bukti_nota')) {
                if ($buktiNotaPath) {
                    Storage::disk('public')->delete($buktiNotaPath);
                }
                $ext           = $request->file('bukti_nota')->extension();
                $namaFile      = 'nota-' . Str::slug($request->no_invoice ?? 'nota') . '.' . $ext;
                $buktiNotaPath = $request->file('bukti_nota')->storeAs('bukti_nota', $namaFile, 'public');
            }
    
            // Update header nota
            $barangMasuk->update([
                'no_invoice'    => $jenisMasuk === 'nota' ? $request->no_invoice : null,
                'supplier'      => $jenisMasuk === 'nota' ? $request->supplier : null,
                'penerima'      => $jenisMasuk === 'nota' ? $request->penerima : null,
                'tanggal_masuk' => $request->tanggal_masuk,
                'bukti_nota'    => $buktiNotaPath,
                'jenis_pembelian' => $request->jenis_pembelian, // REVISI: JENIS PEMBELIAN
                'bus_id'        => $jenisMasuk === 'barang_bekas' ? $request->bus_id : null,
                'keterangan'    => in_array($jenisMasuk, ['stok_awal', 'barang_bekas']) ? $request->keterangan : null,
            ]);
    
            // Simpan foto lama sebelum hapus apapun
            $fotoLama = [];
            foreach ($barangMasuk->details as $index => $detail) {
                $fotoLama[$index] = $detail->foto;
            }
    
            // Hapus detail lama beserta barang dan qr code
            foreach ($barangMasuk->details as $detail) {
                if ($detail->barang) {
                    if ($detail->barang->qr_code) {
                        Storage::disk('public')->delete($detail->barang->qr_code);
                    }
                    $detail->barang->delete();
                }
                $detail->delete();
            }
            
            $kondisiBarang = $jenisMasuk === 'barang_bekas' ? 'bekas' : 'baru';
    
            // Insert detail baru
            foreach ($request->items as $index => $item) {
                $jenis = $item['jenis'] ?? 'bawaan';
    
                $barangReferensi = null;
                if ($jenis === 'barang_ada') {
                    $barangReferensi = Barang::findOrFail($item['barang_id']);
                    $namaBarang = $barangReferensi->nama_barang;
                } else {
                    $namaBarang = $item['nama_barang'];
                }
    
                $fotoFile = $request->file("items.{$index}.foto");
                $fotoPath = null;
    
                if ($fotoFile && $fotoFile->isValid()) {
                    if (isset($fotoLama[$index]) && $fotoLama[$index]) {
                        Storage::disk('public')->delete($fotoLama[$index]);
                    }
                    $ext      = $fotoFile->extension();
                    $namaFoto = 'barang-' . Str::slug($namaBarang) . '-' . time() . '.' . $ext;
                    $fotoPath = $fotoFile->storeAs('barang', $namaFoto, 'public');
                } else {
                    if ($jenis === 'barang_ada' && $barangReferensi && $barangReferensi->foto) {
                        $fotoPath = $barangReferensi->foto;
                    } else {
                        $fotoPath = $fotoLama[$index] ?? null;
                    }
                }
    
                $barang = Barang::create([
                    'kode_barang'   => $item['kode_barang'],
                    'nama_barang'   => $namaBarang,
                    'kategori'      => $item['kategori'],
                    'kondisi'       => $kondisiBarang,
                    'gudang'        => $item['gudang'],
                    'jenis_pembelian' => $request->jenis_pembelian, // REVISI: JENIS PEMBELIAN
                    'foto'          => $fotoPath,
                    'qty'           => $item['qty'],
                    'satuan'        => $item['satuan'],
                    'qty_satuan'    => $item['qty_satuan'],
                    'stok_saat_ini' => $item['qty_satuan'],
                    'harga_jual'    => $item['harga_jual'],
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'qr_code'       => null,
                ]);
    
                $qrData = (string) $barang->id;
    
                $qrFolder = storage_path('app/public/qrcode');
                if (!file_exists($qrFolder)) {
                    mkdir($qrFolder, 0755, true);
                }
    
                $namaQr     = 'barang-' . Str::slug($barang->nama_barang) . '-' . $barang->id . '.svg';
                $qrFileName = 'qrcode/' . $namaQr;
                $qrFilePath = storage_path('app/public/' . $qrFileName);
    
                QrCode::format('svg')
                    ->size(200)
                    ->errorCorrection('H')
                    ->generate($qrData, $qrFilePath);
    
                $barang->update(['qr_code' => $qrFileName]);
    
                Barang_masuk_detail::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'barang_id'       => $barang->id,
                    'nama_barang'     => $namaBarang,
                    'foto'            => $fotoPath,
                    'qty'             => $item['qty'],
                    'satuan'          => $item['satuan'],
                    'qty_satuan'      => $item['qty_satuan'],
                    'harga_jual'      => $item['harga_jual'],
                    'subtotal'        => $item['subtotal'],
                ]);
            }
        });
    
        return redirect()->route('barang-masuk.index')
                        ->with('success', 'Data barang masuk berhasil diupdate.');
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $barangMasuk = Barang_masuk::with('details.barang')->findOrFail($id);

            // Hapus detail beserta barang dan file terkait
            foreach ($barangMasuk->details as $detail) {

                // Hapus foto barang
                if ($detail->foto) {
                    Storage::disk('public')->delete($detail->foto);
                }

                // Hapus qr code dan barang
                if ($detail->barang) {
                    if ($detail->barang->qr_code) {
                        Storage::disk('public')->delete($detail->barang->qr_code);
                    }
                    $detail->barang->delete();
                }

                $detail->delete();
            }

            // Hapus bukti nota
            if ($barangMasuk->bukti_nota) {
                Storage::disk('public')->delete($barangMasuk->bukti_nota);
            }

            // Hapus header nota
            $barangMasuk->delete();
        });

        return redirect()->route('barang-masuk.index')
                        ->with('success', 'Data barang masuk berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $query = Barang_masuk::with('details')->orderBy('created_at', 'desc');

        if ($request->filled('no_invoice')) {
            $query->where('no_invoice', 'like', '%' . $request->no_invoice . '%');
        }

        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%' . $request->supplier . '%');
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_masuk', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_masuk', $request->tahun);
        }

        // ============================================
        // REVISI: FILTER JENIS MASUK
        // ============================================
        if ($request->filled('jenis_masuk')) {
            $query->where('jenis_masuk', $request->jenis_masuk);
        }

        // ============================================
        // REVISI: JENIS PEMBELIAN
        // Filter jenis_pembelian di export PDF
        // ============================================
        if ($request->filled('jenis_pembelian')) {
            $query->where('jenis_pembelian', $request->jenis_pembelian);
        }

        $barangMasuks = $query->get();

        $pdf = Pdf::loadView('barang-masuk.pdf-index', compact('barangMasuks'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('barang-masuk-' . now()->format('d-m-Y') . '.pdf');
    }

    // Export per nota
    public function exportPdfShow($id)
    {
        $barangMasuk = Barang_masuk::with('details')->findOrFail($id);
        $storagePath = storage_path('app/public/');

        $pdf = Pdf::loadView('barang-masuk.pdf-show', compact('barangMasuk', 'storagePath'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('nota-' . $barangMasuk->no_invoice . '.pdf');
    }
}