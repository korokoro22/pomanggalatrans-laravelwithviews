<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Armada</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #28a745; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>

    <h3>Laporan Keuangan Armada</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Periode</th>
                <th>Nama Bus</th>
                <th>Plat Nomor</th>
                <th>Driver</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Pendapatan Bersih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::createFromDate($row['periode_tahun'], $row['periode_bulan'], 1)->format('F Y') }}
                </td>
                <td>{{ $row['bus']->nama_bus }}</td>
                <td>{{ $row['bus']->plat_nomor }}</td>
                <td>{{ $row['bus']->nama_driver }}</td>
                <td class="text-right text-success">
                    Rp {{ number_format($row['pemasukan'], 0, ',', '.') }}
                </td>
                <td class="text-right text-danger">
                    Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}
                </td>
                <td class="text-right">
                    Rp {{ number_format($row['bersih'], 0, ',', '.') }}
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
                <td colspan="5" class="text-right"><strong>Total</strong></td>
                <td class="text-right text-success">
                    <strong>Rp {{ number_format(collect($data)->sum('pemasukan'), 0, ',', '.') }}</strong>
                </td>
                <td class="text-right text-danger">
                    <strong>Rp {{ number_format(collect($data)->sum('pengeluaran'), 0, ',', '.') }}</strong>
                </td>
                <td class="text-right">
                    <strong>Rp {{ number_format(collect($data)->sum('bersih'), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>