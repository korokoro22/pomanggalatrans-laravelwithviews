<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Barang_masuk;
use App\Models\Barang_masuk_detail;
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

        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%' . $request->supplier . '%');
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_masuk', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_masuk', $request->tahun);
        }

        if ($request->filled('kategori_nota')) {
            $query->where('kategori_nota', $request->kategori_nota);
        }

        $barangMasuks = $query->get();

        return view('barang-masuk.index', compact('barangMasuks'));
    }

    public function create() {
        return view('barang-masuk/create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'tanggal_masuk'         => 'required|date',
    //         'no_invoice'            => 'required|string',
    //         'supplier'              => 'required|string',
    //         'penerima'              => 'required|string',
    //         'bukti_nota'            => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
    //         'items'                 => 'required|array|min:1',
    //         'items.*.kategori'      => 'required|string',
    //         'items.*.kode_barang' => 'required|string|unique:barang,kode_barang',
    //         'items.*.nama_barang'   => 'required|string',
    //         'items.*.foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
    //         'items.*.qty'           => 'required|integer|min:1',
    //         'items.*.satuan'        => 'required|string',
    //         'items.*.qty_satuan'    => 'required|integer|min:1',
    //         'items.*.harga_jual'    => 'required|numeric|min:0',
    //         'items.*.subtotal'      => 'required|numeric|min:0',
    //     ]);

    //     DB::transaction(function () use ($request) {

    //         // Langkah 1 — upload bukti nota dengan nama deskriptif
    //         $buktiNotaPath = null;
    //         if ($request->hasFile('bukti_nota')) {
    //             $ext           = $request->file('bukti_nota')->extension();
    //             $namaFile      = 'nota-' . Str::slug($request->no_invoice) . '.' . $ext;
    //             $buktiNotaPath = $request->file('bukti_nota')
    //                                      ->storeAs('bukti_nota', $namaFile, 'public');
    //         }

    //         // Langkah 2 — simpan header nota ke barang_masuk
    //         $barangMasuk = Barang_masuk::create([
    //             'no_invoice'    => $request->no_invoice,
    //             'supplier'      => $request->supplier,
    //             'penerima'      => $request->penerima,
    //             'tanggal_masuk' => $request->tanggal_masuk,
    //             'bukti_nota'    => $buktiNotaPath,
    //         ]);

    //         // Langkah 3 — loop tiap item
    //         foreach ($request->items as $index => $item) {

    //             // Upload foto barang dengan nama deskriptif
    //             $fotoPath = null;
    //             $fotoFile = $request->file("items.{$index}.foto");
    //             if ($fotoFile && $fotoFile->isValid()) {
    //                 $ext      = $fotoFile->extension();
    //                 $namaFoto = 'barang-' . Str::slug($item['nama_barang']) . '-' . time() . '.' . $ext;
    //                 $fotoPath = $fotoFile->storeAs('barang', $namaFoto, 'public');
    //             }

    //             // Langkah 4 — buat baris baru di tabel barang
    //             $barang = Barang::create([
    //                 'kode_barang'   => $item['kode_barang'],
    //                 'nama_barang'   => $item['nama_barang'],
    //                 'kategori'      => $item['kategori'],
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
    //             $qrData = route('master-barang.show', $barang->id);

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
    //                 'nama_barang'     => $item['nama_barang'],
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
    //                      ->with('success', 'Data barang masuk berhasil disimpan.');
    // }

    //BarangMasukController
    public function store(Request $request)
{
    $request->validate([
        'tanggal_masuk'         => 'required|date',
        'no_invoice'            => 'required|string',
        'supplier'              => 'required|string',
        'penerima'              => 'required|string',
        'bukti_nota'            => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
        'items'                 => 'required|array|min:1',
        'items.*.kategori'      => 'required|string',
        'items.*.kode_barang'   => 'required|string|unique:barang,kode_barang',
        'items.*.nama_barang'   => 'required|string',
        'items.*.foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:6144',
        'items.*.qty'           => 'required|integer|min:1',
        'items.*.satuan'        => 'required|string',
        'items.*.qty_satuan'    => 'required|integer|min:1',
        'items.*.harga_jual'    => 'required|numeric|min:0',
        'items.*.subtotal'      => 'required|numeric|min:0',
        'items.*.gudang' => 'required|in:gudang_utama,gudang_2,gudang_3',
        'kategori_nota' => 'required|in:nota_bengkel,nota_jalan',
    ]);

    DB::transaction(function () use ($request) {
        $manager = new ImageManager(new Driver());

        // Langkah 1 — upload & kompres bukti nota
        $buktiNotaPath = null;
        if ($request->hasFile('bukti_nota')) {
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
            'no_invoice'    => $request->no_invoice,
            'supplier'      => $request->supplier,
            'kategori_nota'  => $request->kategori_nota,
            'penerima'      => $request->penerima,
            'tanggal_masuk' => $request->tanggal_masuk,
            'bukti_nota'    => $buktiNotaPath,
        ]);

        // Langkah 3 — loop tiap item
        foreach ($request->items as $index => $item) {

            // Upload & kompres foto barang
            $fotoPath = null;
            $fotoFile = $request->file("items.{$index}.foto");
            if ($fotoFile && $fotoFile->isValid()) {
                $ext      = $fotoFile->extension();
                $namaFoto = 'barang-' . Str::slug($item['nama_barang']) . '-' . time() . '.' . $ext;
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

            // Langkah 4 — buat baris baru di tabel barang
            $barang = Barang::create([
                'kode_barang'   => $item['kode_barang'],
                'nama_barang'   => $item['nama_barang'],
                'kategori'      => $item['kategori'],
                'gudang'        => $item['gudang'],
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
            // $qrData = route('master-barang.show', $barang->id); //jika mau isi qr code url ke halaman detail/show 
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

            // Langkah 6 — update kolom qr_code di barang
            $barang->update(['qr_code' => $qrFileName]);

            // Langkah 7 — simpan detail ke barang_masuk_detail
            Barang_masuk_detail::create([
                'barang_masuk_id' => $barangMasuk->id,
                'barang_id'       => $barang->id,
                'nama_barang'     => $item['nama_barang'],
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

    public function edit($id)
    {
        $barangMasuk = Barang_masuk::with('details.barang')->findOrFail($id);
        return view('barang-masuk.edit', compact('barangMasuk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_masuk'         => 'required|date',
            'no_invoice'            => 'required|string',
            'supplier'              => 'required|string',
            'penerima'              => 'required|string',
            'bukti_nota'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'items'                 => 'required|array|min:1',
            'items.*.kode_barang'   => 'required|string',
            'items.*.kategori'      => 'required|string',
            'items.*.nama_barang'   => 'required|string',
            'items.*.foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'items.*.qty'           => 'required|integer|min:1',
            'items.*.satuan'        => 'required|string',
            'items.*.qty_satuan'    => 'required|integer|min:1',
            'items.*.harga_jual'    => 'required|numeric|min:0',
            'items.*.subtotal'      => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {

            $barangMasuk = Barang_masuk::with('details.barang')->findOrFail($id);

            // Update bukti nota jika ada file baru
            $buktiNotaPath = $barangMasuk->bukti_nota;
            if ($request->hasFile('bukti_nota')) {
                if ($buktiNotaPath) {
                    Storage::disk('public')->delete($buktiNotaPath);
                }
                $ext           = $request->file('bukti_nota')->extension();
                $namaFile      = 'nota-' . Str::slug($request->no_invoice) . '.' . $ext;
                $buktiNotaPath = $request->file('bukti_nota')
                                        ->storeAs('bukti_nota', $namaFile, 'public');
            }

            // Update header nota
            $barangMasuk->update([
                'no_invoice'    => $request->no_invoice,
                'supplier'      => $request->supplier,
                'penerima'      => $request->penerima,
                'tanggal_masuk' => $request->tanggal_masuk,
                'bukti_nota'    => $buktiNotaPath,
            ]);

            // Simpan foto lama sebelum hapus apapun
            $fotoLama = [];
            foreach ($barangMasuk->details as $index => $detail) {
                $fotoLama[$index] = $detail->foto;
            }

            // Hapus detail lama beserta barang dan qr code
            // TIDAK hapus foto disini
            foreach ($barangMasuk->details as $detail) {
                if ($detail->barang) {
                    if ($detail->barang->qr_code) {
                        Storage::disk('public')->delete($detail->barang->qr_code);
                    }
                    $detail->barang->delete();
                }
                $detail->delete();
            }

            // Insert detail baru
            foreach ($request->items as $index => $item) {

                $fotoFile = $request->file("items.{$index}.foto");

                if ($fotoFile && $fotoFile->isValid()) {
                    // Ada foto baru — hapus foto lama dulu baru upload baru
                    if (isset($fotoLama[$index]) && $fotoLama[$index]) {
                        Storage::disk('public')->delete($fotoLama[$index]);
                    }
                    $ext      = $fotoFile->extension();
                    $namaFoto = 'barang-' . Str::slug($item['nama_barang']) . '-' . time() . '.' . $ext;
                    $fotoPath = $fotoFile->storeAs('barang', $namaFoto, 'public');
                } else {
                    // Tidak ada foto baru — pakai foto lama
                    $fotoPath = $fotoLama[$index] ?? null;
                }

                $barang = Barang::create([
                    'kode_barang'   => $item['kode_barang'],
                    'nama_barang'   => $item['nama_barang'],
                    'kategori'      => $item['kategori'],
                    'foto'          => $fotoPath,
                    'qty'           => $item['qty'],
                    'satuan'        => $item['satuan'],
                    'qty_satuan'    => $item['qty_satuan'],
                    'stok_saat_ini' => $item['qty_satuan'],
                    'harga_jual'    => $item['harga_jual'],
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'qr_code'       => null,
                ]);

                $qrData = route('master-barang.show', $barang->id);

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
                    'nama_barang'     => $item['nama_barang'],
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