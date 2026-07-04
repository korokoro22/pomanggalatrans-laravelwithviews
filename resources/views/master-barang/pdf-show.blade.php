<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Barang {{ $barang->kode_barang }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 4px 5px; }
        .info-table td:first-child { width: 35%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #17a2b8; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider { border-top: 1px solid #ddd; margin: 10px 0; }
        .stok-menipis { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h3>Detail Barang</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>Kode Barang</td>
            <td>: {{ $barang->kode_barang }}</td>
        </tr>
        <tr>
            <td>Foto</td>
            <td>
                @if ($barang->foto)
                    <img src="{{ storage_path('app/public/' . $barang->foto) }}"
                        style="max-width:100px; max-height:100px; object-fit:cover; border-radius:5px;">
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>Nama Barang</td>
            <td>: {{ $barang->nama_barang }}</td>
        </tr>
        <tr>
            <td>Kategori</td>
            <td>:
                @if ($barang->kategori == 'oli_mesin') Oli Mesin
                @elseif ($barang->kategori == 'filter_solar') Filter Solar
                @else Item Bebas
                @endif
            </td>
        </tr>
        <tr>
            <td>Qty</td>
            <td>: {{ $barang->qty }} {{ $barang->satuan }}</td>
        </tr>
        <tr>
            <td>Isi per Satuan</td>
            <td>: {{ number_format($barang->qty_satuan) }} Pcs</td>
        </tr>
        <tr>
            <td>Stok Saat Ini</td>
            <td>: <span class="{{ $barang->stok_saat_ini <= 5 ? 'stok-menipis' : '' }}">
                {{ number_format($barang->stok_saat_ini) }} Pcs
                {{ $barang->stok_saat_ini <= 5 ? '(Menipis)' : '' }}
            </span></td>
        </tr>
        <tr>
            <td>Harga Jual</td>
            <td>: Rp {{ number_format($barang->harga_jual, 0, ',', '.') }} / pcs</td>
        </tr>
        <tr>
            <td>Tanggal Masuk</td>
            <td>: {{ \Carbon\Carbon::parse($barang->tanggal_masuk)->format('d-m-Y H:i:s') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <h4>Riwayat Keluar</h4>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Nama Bus</th>
                <th>Plat Nomor</th>
                <th>Qty Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barang->transaksiKeluarDetail as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($detail->transaksiKeluar->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $detail->transaksiKeluar->bus->nama_bus }}</td>
                <td class="text-center">{{ $detail->transaksiKeluar->bus->plat_nomor }}</td>
                <td class="text-center">{{ $detail->qty }} Pcs</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada riwayat keluar</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>