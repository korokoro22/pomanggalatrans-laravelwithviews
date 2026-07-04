<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bus {{ $bus->nama_bus }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h3 { text-align: center; margin-bottom: 5px; }
        h4 { margin-top: 15px; margin-bottom: 5px; background: #f0f0f0; padding: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        .info-table { width: 100%; margin-bottom: 10px; }
        .info-table td { padding: 3px 5px; }
        .info-table td:first-child { width: 30%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { background-color: #dc3545; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-danger { color: #dc3545; }
        .divider { border-top: 1px solid #ddd; margin: 10px 0; }
    </style>
</head>
<body>

    <h3>Laporan Pengeluaran Bus</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>Nama Bus</td>
            <td>: {{ $bus->nama_bus }}</td>
            <td>Driver</td>
            <td>: {{ $bus->nama_driver }}</td>
        </tr>
        <tr>
            <td>Plat Nomor</td>
            <td>: {{ $bus->plat_nomor }}</td>
            <td>Rute Trayek</td>
            <td>: {{ $bus->rute_trayek ?? '-' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    @forelse ($grouped as $periodeKey => $transaksis)

        @php
            $periode    = \Carbon\Carbon::createFromFormat('Y-m', $periodeKey);
            $totalBulan = $transaksis->sum('total_transaksi');
        @endphp

        <h4>Pengeluaran {{ $periode->format('F Y') }}</h4>

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th>Item Transaksi</th>
                    <th width="15%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksis as $index => $transaksi)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i') }}</td>
                    <td>
                        @foreach ($transaksi->details as $detail)
                            - {{ $detail->nama_item }}<br>
                        @endforeach
                    </td>
                    <td class="text-right text-danger">
                        Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total {{ $periode->format('F Y') }}</strong></td>
                    <td class="text-right text-danger">
                        <strong>Rp {{ number_format($totalBulan, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>

    @empty
        <p class="text-center">Tidak ada data pengeluaran</p>
    @endforelse

</body>
</html>