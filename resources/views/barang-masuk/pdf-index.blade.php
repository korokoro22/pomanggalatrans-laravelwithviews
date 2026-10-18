<!DOCTYPE html>
<html style="text-transform: uppercase;">
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
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-primary { background-color: #007bff; color: white; }
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
            <th width="8%">Jenis Masuk</th>
            <th width="10%">No Invoice</th>
            <th width="9%">Kategori Nota</th>
            <th width="11%">Supplier</th>
            {{-- REVISI: JENIS PEMBELIAN --}}
            <th width="11%">Jenis Pembelian</th>
            <th width="10%">Penerima</th>
            <th>Item Barang</th>
            <th width="11%">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($barangMasuks as $index => $barangMasuk)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('d-m-Y H:i') }}</td>
            <td class="text-center">
                @if(($barangMasuk->jenis_masuk ?? 'nota') == 'stok_awal')
                    <span class="badge badge-warning">Stok Lama</span>
                @else
                    <span class="badge badge-primary">Nota</span>
                @endif
            </td>
            <td>{{ $barangMasuk->no_invoice ?? '-' }}</td>
            <td class="text-center">
                @if($barangMasuk->kategori_nota == 'nota_jalan')
                    <span class="badge badge-warning">Nota Jalan</span>
                @else
                    <span class="badge badge-success">Nota Bengkel</span>
                @endif
            </td>
            <td>{{ $barangMasuk->supplier ?? '-' }}</td>
            {{-- REVISI: JENIS PEMBELIAN --}}
            <td class="text-center">
                @if($barangMasuk->jenis_pembelian === 'pembelian_langsung')
                    Pembelian Langsung
                @elseif($barangMasuk->jenis_pembelian === 'pembelian_toko')
                    Pembelian Toko
                @else
                    -
                @endif
            </td>
            <td>{{ $barangMasuk->penerima ?? '-' }}</td>
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
            <td colspan="10" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9" class="text-right"><strong>Total Keseluruhan</strong></td>
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