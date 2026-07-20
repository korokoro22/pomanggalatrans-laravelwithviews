<!DOCTYPE html>
<html style="text-transform: uppercase;">
<head>
    <meta charset="utf-8">
    <title>Nota {{ $barangMasuk->no_invoice }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 5px; }
        .info-table td:first-child { width: 30%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #28a745; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .divider { border-top: 1px solid #ddd; margin: 10px 0; }
    </style>
</head>
<body>

    <h3>Detail Nota Barang Masuk</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>No. Invoice</td>
            <td>: {{ $barangMasuk->no_invoice }}</td>
            <td>Tanggal Masuk</td>
            <td>: {{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td>: {{ $barangMasuk->supplier }}</td>
            <td>Penerima</td>
            <td>: {{ $barangMasuk->penerima }}</td>
        </tr>
        <tr>
            <td>Kategori Nota</td>
            <td colspan="3">
                :
                @if($barangMasuk->kategori_nota == 'nota_jalan')
                    Nota Jalan
                @else
                    Nota Bengkel
                @endif
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Kode Barang</th>
                <th width="8%">Foto</th>
                <th>Nama Barang</th>
                <th width="10%">Kategori</th>
                <th width="8%">Qty</th>
                <th width="8%">Satuan</th>
                <th width="10%">Total (Pcs)</th>
                <th width="12%">Harga Jual</th>
                <th width="12%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangMasuk->details as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $detail->barang->kode_barang ?? '-' }}</td>
                <td class="text-center">
                    @if($detail->barang && $detail->barang->foto)
                        <img src="{{ $storagePath . $detail->barang->foto }}"
                            style="width:45px; height:45px; object-fit:cover; border-radius:3px;">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $detail->nama_barang }}</td>
                <td class="text-center">
                    @if ($detail->kategori == 'oli_mesin') Oli Mesin
                    @elseif ($detail->kategori == 'filter_solar') Filter Solar
                    @else Item Bebas
                    @endif
                </td>
                <td class="text-center">{{ $detail->qty }}</td>
                <td class="text-center">{{ $detail->satuan }}</td>
                <td class="text-center">{{ number_format($detail->qty_satuan) }} Pcs</td>
                <td class="text-right">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada item</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="text-right"><strong>Total Keseluruhan</strong></td>
                <td class="text-right">
                    <strong>
                        Rp {{ number_format($barangMasuk->details->sum('subtotal'), 0, ',', '.') }}
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>