<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Masuk</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #28a745; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-info { background-color: #17a2b8; color: white; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <h3>Laporan Barang Masuk</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="9%">Tanggal</th>
            <th width="11%">No Invoice</th>
            <th width="10%">Kategori Nota</th>
            <th width="12%">Supplier</th>
            <th width="11%">Penerima</th>
            <th>Item Barang</th>
            <th width="12%">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($barangMasuks as $index => $barangMasuk)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('d-m-Y H:i') }}</td>
            <td>{{ $barangMasuk->no_invoice }}</td>
            <td class="text-center">
                @if($barangMasuk->kategori_nota == 'nota_jalan')
                    <span class="badge badge-warning">Nota Jalan</span>
                @else
                    <span class="badge badge-success">Nota Bengkel</span>
                @endif
            </td>
            <td>{{ $barangMasuk->supplier }}</td>
            <td>{{ $barangMasuk->penerima }}</td>
            <td>
                @foreach ($barangMasuk->details as $detail)
                    - {{ $detail->nama_barang }}<br>
                @endforeach
            </td>
            <td class="text-right">
                Rp {{ number_format($barangMasuk->details->sum('subtotal'), 0, ',', '.') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" class="text-right"><strong>Total Keseluruhan</strong></td>
            <td class="text-right">
                <strong>
                    Rp {{ number_format($barangMasuks->sum(fn($b) => $b->details->sum('subtotal')), 0, ',', '.') }}
                </strong>
            </td>
        </tr>
    </tfoot>
</table>

</body>
</html>