<!DOCTYPE html>
<html style="text-transform: uppercase;">
<head>
    <meta charset="utf-8">
    <title>Detail Bus {{ $bus->plat_nomor }}</title>
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
    </style>
</head>
<body>

    <h3>Detail Bus</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>Nama Bus</td>
            <td>: {{ $bus->nama_bus }}</td>
        </tr>
        <tr>
            <td>Plat Nomor</td>
            <td>: {{ $bus->plat_nomor }}</td>
        </tr>
        <tr>
            <td>Rute / Trayek</td>
            <td>: {{ $bus->rute_trayek ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Driver</td>
            <td>: {{ $bus->nama_driver }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <h4>Riwayat Pengeluaran</h4>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Item Transaksi</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bus->transaksiKeluar as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i') }}</td>
                <td>
                    @foreach ($transaksi->details as $detail)
                        - {{ $detail->nama_item }}<br>
                    @endforeach
                </td>
                <td class="text-right">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada riwayat pengeluaran</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total Pengeluaran</strong></td>
                <td class="text-right">
                    <strong>
                        Rp {{ number_format($bus->transaksiKeluar->sum('total_transaksi'), 0, ',', '.') }}
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>