<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Keuangan_armada;
use App\Models\Transaksi_keluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KeuanganArmadaController extends Controller {
    // public function index(Request $request)
    // {
    //     $bulan = $request->bulan ?? now()->month;
    //     $tahun = $request->tahun ?? now()->year;

    //     // Ambil semua bus beserta pemasukan bulan ini
    //     $busList = Bus::orderBy('nama_bus')->get();

    //     $data = $busList->map(function ($bus) use ($bulan, $tahun) {

    //         // Pemasukan dari tabel keuangan_armada
    //         $keuangan = Keuangan_armada::where('bus_id', $bus->id)
    //             ->where('periode_bulan', $bulan)
    //             ->where('periode_tahun', $tahun)
    //             ->first();

    //         $pemasukan = $keuangan?->pemasukan ?? 0;

    //         // Pengeluaran otomatis dari transaksi keluar
    //         $pengeluaran = Transaksi_keluar::where('bus_id', $bus->id)
    //             ->whereMonth('tanggal', $bulan)
    //             ->whereYear('tanggal', $tahun)
    //             ->sum('total_transaksi');

    //         return [
    //             'bus'          => $bus,
    //             'keuangan_id'  => $keuangan?->id,
    //             'pemasukan'    => $pemasukan,
    //             'pengeluaran'  => $pengeluaran,
    //             'bersih'       => $pemasukan - $pengeluaran,
    //         ];
    //     });

    //     return view('keuangan-armada.index', compact('data', 'busList', 'bulan', 'tahun'));
    // }

    public function index(Request $request)
    {
        $busList = Bus::orderBy('nama_bus')->get();

        // Ambil semua keuangan yang pernah ada
        $query = Keuangan_armada::with('bus')->orderBy('periode_tahun', 'desc')
                                            ->orderBy('periode_bulan', 'desc');

        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        if ($request->filled('bulan')) {
            $query->where('periode_bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->where('periode_tahun', $request->tahun);
        }

        $keuangans = $query->get();

        // Hitung pengeluaran dan bersih per baris
        $data = $keuangans->map(function ($keuangan) {
            $pengeluaran = Transaksi_keluar::where('bus_id', $keuangan->bus_id)
                ->whereMonth('tanggal', $keuangan->periode_bulan)
                ->whereYear('tanggal', $keuangan->periode_tahun)
                ->sum('total_transaksi');

            return [
                'bus'         => $keuangan->bus,
                'keuangan_id' => $keuangan->id,
                'periode_bulan' => $keuangan->periode_bulan,
                'periode_tahun' => $keuangan->periode_tahun,
                'pemasukan'   => $keuangan->pemasukan,
                'pengeluaran' => $pengeluaran,
                'bersih'      => $keuangan->pemasukan - $pengeluaran,
            ];
        });

        return view('keuangan-armada.index', compact('data', 'busList'));
    }

    public function create()
    {
        $busList = Bus::orderBy('nama_bus')->get();
        return view('keuangan-armada.create', compact('busList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_id'        => 'required|exists:bus,id',
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer|min:2000',
            'pemasukan'     => 'required|numeric|min:0',
        ]);

        // Cek apakah sudah ada data untuk bus + periode ini
        $exists = Keuangan_armada::where('bus_id', $request->bus_id)
            ->where('periode_bulan', $request->periode_bulan)
            ->where('periode_tahun', $request->periode_tahun)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data pemasukan untuk bus dan periode ini sudah ada. Silakan edit data yang sudah ada.');
        }

        Keuangan_armada::create([
            'bus_id'        => $request->bus_id,
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
            'pemasukan'     => $request->pemasukan,
        ]);

        return redirect()->route('keuangan-armada.index')
            ->with('success', 'Data pemasukan berhasil ditambahkan.');
    }

    public function show(Request $request, $busId)
    {
        $bus = Bus::findOrFail($busId);

        // Ambil semua periode yang punya data keuangan atau transaksi keluar
        $periodeKeuangan = Keuangan_armada::where('bus_id', $busId)
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->get();

        $transaksiKeluar = Transaksi_keluar::with('details')
            ->where('bus_id', $busId)
            ->orderByDesc('tanggal')
            ->get();

        // Gabungkan periode dari keduanya
        $periodeList = collect();

        foreach ($periodeKeluar = $periodeKeuangan as $k) {
            $key = $k->periode_tahun . '-' . str_pad($k->periode_bulan, 2, '0', STR_PAD_LEFT);
            $periodeList->put($key, ['bulan' => $k->periode_bulan, 'tahun' => $k->periode_tahun]);
        }

        foreach ($transaksiKeluar as $t) {
            $bulan = \Carbon\Carbon::parse($t->tanggal)->month;
            $tahun = \Carbon\Carbon::parse($t->tanggal)->year;
            $key   = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $periodeList->put($key, ['bulan' => $bulan, 'tahun' => $tahun]);
        }

        $periodeList = $periodeList->sortKeysDesc();

        // Filter riwayat jika ada
        $filterBulanRiwayat = $request->bulan_riwayat;
        $filterTahunRiwayat = $request->tahun_riwayat;

        if ($filterBulanRiwayat) {
            $periodeList = $periodeList->filter(fn($p) => $p['bulan'] == $filterBulanRiwayat);
        }

        if ($filterTahunRiwayat) {
            $periodeList = $periodeList->filter(fn($p) => $p['tahun'] == $filterTahunRiwayat);
        }

        // Hitung pemasukan & pengeluaran per periode
        $riwayat = $periodeList->map(function ($periode) use ($busId, $transaksiKeluar) {
            $bulan = $periode['bulan'];
            $tahun = $periode['tahun'];

            $keuangan = Keuangan_armada::where('bus_id', $busId)
                ->where('periode_bulan', $bulan)
                ->where('periode_tahun', $tahun)
                ->first();

            $pemasukan   = $keuangan?->pemasukan ?? 0;
            $pengeluaran = $transaksiKeluar
                ->filter(fn($t) =>
                    \Carbon\Carbon::parse($t->tanggal)->month == $bulan &&
                    \Carbon\Carbon::parse($t->tanggal)->year  == $tahun
                )->sum('total_transaksi');

            return [
                'bulan'       => $bulan,
                'tahun'       => $tahun,
                'keuangan_id' => $keuangan?->id,
                'pemasukan'   => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'bersih'      => $pemasukan - $pengeluaran,
            ];
        });

        // Filter detail transaksi
        $bulanDetail = $request->bulan_detail ?? now()->month;
        $tahunDetail = $request->tahun_detail ?? now()->year;

        $transaksiTerbaru = $transaksiKeluar->filter(fn($t) =>
            \Carbon\Carbon::parse($t->tanggal)->month == $bulanDetail &&
            \Carbon\Carbon::parse($t->tanggal)->year  == $tahunDetail
        );

        return view('keuangan-armada.show', compact(
            'bus', 'riwayat', 'transaksiTerbaru',
            'bulanDetail', 'tahunDetail',
            'filterBulanRiwayat', 'filterTahunRiwayat'
        ));
    }

    public function edit($id)
    {
        $keuangan = Keuangan_armada::with('bus')->findOrFail($id);
        $busList  = Bus::orderBy('nama_bus')->get();
        return view('keuangan-armada.edit', compact('keuangan', 'busList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pemasukan' => 'required|numeric|min:0',
        ]);

        $keuangan = Keuangan_armada::findOrFail($id);
        $keuangan->update(['pemasukan' => $request->pemasukan]);

        return redirect()->route('keuangan-armada.index')
            ->with('success', 'Data pemasukan berhasil diupdate.');
    }

    public function destroy($id)
    {
        Keuangan_armada::findOrFail($id)->delete();

        return redirect()->route('keuangan-armada.index')
            ->with('success', 'Data pemasukan berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $busList = Bus::orderBy('nama_bus')->get();

        $query = Keuangan_armada::with('bus')->orderBy('periode_tahun', 'desc')
                                            ->orderBy('periode_bulan', 'desc');

        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        if ($request->filled('bulan')) {
            $query->where('periode_bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->where('periode_tahun', $request->tahun);
        }

        $keuangans = $query->get();

        $data = $keuangans->map(function ($keuangan) {
            $pengeluaran = Transaksi_keluar::where('bus_id', $keuangan->bus_id)
                ->whereMonth('tanggal', $keuangan->periode_bulan)
                ->whereYear('tanggal', $keuangan->periode_tahun)
                ->sum('total_transaksi');

            return [
                'bus'           => $keuangan->bus,
                'keuangan_id'   => $keuangan->id,
                'periode_bulan' => $keuangan->periode_bulan,
                'periode_tahun' => $keuangan->periode_tahun,
                'pemasukan'     => $keuangan->pemasukan,
                'pengeluaran'   => $pengeluaran,
                'bersih'        => $keuangan->pemasukan - $pengeluaran,
            ];
        });

        $pdf = Pdf::loadView('keuangan-armada.pdf-index', compact('data'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('keuangan-armada-' . now()->format('d-m-Y') . '.pdf');
    }

    public function exportPdfShow(Request $request, $busId)
    {
        $bus = Bus::findOrFail($busId);

        $semuaTransaksi = Transaksi_keluar::with('details')
            ->where('bus_id', $busId)
            ->orderByDesc('tanggal')
            ->get();

        $periodeKeuangan = Keuangan_armada::where('bus_id', $busId)
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->get();

        // Gabungkan periode dari keuangan dan transaksi keluar
        $periodeList = collect();

        foreach ($periodeKeuangan as $k) {
            $key = $k->periode_tahun . '-' . str_pad($k->periode_bulan, 2, '0', STR_PAD_LEFT);
            $periodeList->put($key, ['bulan' => $k->periode_bulan, 'tahun' => $k->periode_tahun]);
        }

        foreach ($semuaTransaksi as $t) {
            $bulan = \Carbon\Carbon::parse($t->tanggal)->month;
            $tahun = \Carbon\Carbon::parse($t->tanggal)->year;
            $key   = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $periodeList->put($key, ['bulan' => $bulan, 'tahun' => $tahun]);
        }

        $periodeList = $periodeList->sortKeysDesc();

        // Filter jika ada
        if ($request->filled('bulan_riwayat')) {
            $periodeList = $periodeList->filter(fn($p) => $p['bulan'] == $request->bulan_riwayat);
        }

        if ($request->filled('tahun_riwayat')) {
            $periodeList = $periodeList->filter(fn($p) => $p['tahun'] == $request->tahun_riwayat);
        }

        // Hitung riwayat
        $riwayat = $periodeList->map(function ($periode) use ($busId, $semuaTransaksi) {
            $bulan = $periode['bulan'];
            $tahun = $periode['tahun'];

            $keuangan = Keuangan_armada::where('bus_id', $busId)
                ->where('periode_bulan', $bulan)
                ->where('periode_tahun', $tahun)
                ->first();

            $pemasukan   = $keuangan?->pemasukan ?? 0;
            $pengeluaran = $semuaTransaksi
                ->filter(fn($t) =>
                    \Carbon\Carbon::parse($t->tanggal)->month == $bulan &&
                    \Carbon\Carbon::parse($t->tanggal)->year  == $tahun
                )->sum('total_transaksi');

            return [
                'bulan'       => $bulan,
                'tahun'       => $tahun,
                'keuangan_id' => $keuangan?->id,
                'pemasukan'   => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'bersih'      => $pemasukan - $pengeluaran,
            ];
        });

        $pdf = Pdf::loadView('keuangan-armada.pdf-show', compact(
            'bus', 'riwayat', 'semuaTransaksi'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('keuangan-armada-' . Str::slug($bus->nama_bus) . '.pdf');
    }
}