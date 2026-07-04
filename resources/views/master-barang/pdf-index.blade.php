<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Master Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #17a2b8; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: middle; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 10px; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #17a2b8; color: #fff; }
        .badge-secondary { background-color: #6c757d; color: #fff; }
        .stok-menipis { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h3>Laporan Master Barang</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Kode Barang</th>
                <th width="8%">Foto</th>
                <th>Nama Barang</th>
                <th width="10%">Kategori</th>
                <th width="6%">Qty</th>
                <th width="8%">Satuan</th>
                <th width="8%">Qty Satuan</th>
                <th width="10%">Stok Saat Ini</th>
                <th width="12%">Harga Jual</th>
                <th width="10%">Tanggal Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $index => $barang)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td class="text-center">
                    @if ($barang->foto)
                        <img src="{{ storage_path('app/public/' . $barang->foto) }}"
                            style="width:45px; height:45px; object-fit:cover; border-radius:3px;">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $barang->nama_barang }}</td>
                <td class="text-center">
                    @if ($barang->kategori == 'oli_mesin') Oli Mesin
                    @elseif ($barang->kategori == 'filter_solar') Filter Solar
                    @else Item Bebas
                    @endif
                </td>
                <td class="text-center">{{ $barang->qty }}</td>
                <td class="text-center">{{ $barang->satuan }}</td>
                <td class="text-center">{{ number_format($barang->qty_satuan) }} Pcs</td>
                <td class="text-center {{ $barang->stok_saat_ini <= 5 ? 'stok-menipis' : '' }}">
                    {{ number_format($barang->stok_saat_ini) }} Pcs
                    {{ $barang->stok_saat_ini <= 5 ? '(Menipis)' : '' }}
                </td>
                <td class="text-right">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($barang->tanggal_masuk)->format('d-m-Y H:i:s') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>