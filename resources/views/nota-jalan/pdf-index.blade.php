<!DOCTYPE html>
<html style="text-transform: uppercase;">
<head>
    <meta charset="utf-8">
    <title>Laporan Nota Jalan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #ffc107; color: #212529; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .divider { border-top: 1px solid #ddd; margin: 10px 0; }
    </style>
</head>
<body>

    <h3>Laporan Nota Jalan</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="8%">Tanggal</th>
                <th width="16%">Bus</th>
                <th width="10%">No Invoice</th>
                <th width="12%">Supplier</th>
                <th>Item</th>
                <th width="12%">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notaJalans as $index => $notaJalan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($notaJalan->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $notaJalan->bus->nama_bus ?? '-' }} ({{ $notaJalan->bus->plat_nomor ?? '-' }})</td>
                <td>{{ $notaJalan->no_invoice }}</td>
                <td>{{ $notaJalan->supplier }}</td>
                <td>
                    @foreach($notaJalan->details as $detail)
                        - {{ $detail->nama_item }} ({{ $detail->qty }} {{ $detail->satuan }})<br>
                    @endforeach
                </td>
                <td class="text-right">Rp {{ number_format($notaJalan->total_transaksi, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>Total Keseluruhan</strong></td>
                <td class="text-right">
                    <strong>
                        Rp {{ number_format($notaJalans->sum('total_transaksi'), 0, ',', '.') }}
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>