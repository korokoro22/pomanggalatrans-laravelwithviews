<!DOCTYPE html>
<html style="text-transform: uppercase;">
<head>
    <meta charset="utf-8">
    <title>Laporan Data Bus</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #17a2b8; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <h3>Laporan Data Bus</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Bus</th>
                <th>Plat Nomor</th>
                <th>Rute / Trayek</th>
                <th>Nama Driver</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($buses as $index => $bus)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $bus->nama_bus }}</td>
                <td>{{ $bus->plat_nomor }}</td>
                <td>{{ $bus->rute_trayek ?? '-' }}</td>
                <td>{{ $bus->nama_driver }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>