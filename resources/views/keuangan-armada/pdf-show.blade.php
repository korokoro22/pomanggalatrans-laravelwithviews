<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Keuangan Armada {{ $bus->nama_bus }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        h4 { margin-top: 15px; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        .info-table { width: 100%; margin-bottom: 10px; }
        .info-table td { padding: 3px 5px; }
        .info-table td:first-child { width: 30%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #28a745; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .divider { border-top: 1px solid #ddd; margin: 10px 0; }
        .periode-header { background-color: #f0f0f0; font-weight: bold; padding: 5px 6px; border: 1px solid #ddd; margin-top: 10px; }
    </style>
</head>
<body>

    <h3>Detail Keuangan Armada</h3>
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

    <h4>Riwayat Keuangan per Bulan</h4>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Periode</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Pendapatan Bersih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayat as $row)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::createFromDate($row['tahun'], $row['bulan'], 1)->format('F Y') }}
                </td>
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
                <td colspan="5" class="text-center">Tidak ada riwayat</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right"><strong>Total</strong></td>
                <td class="text-right text-success">
                    <strong>Rp {{ number_format(collect($riwayat)->sum('pemasukan'), 0, ',', '.') }}</strong>
                </td>
                <td class="text-right text-danger">
                    <strong>Rp {{ number_format(collect($riwayat)->sum('pengeluaran'), 0, ',', '.') }}</strong>
                </td>
                <td class="text-right">
                    <strong>Rp {{ number_format(collect($riwayat)->sum('bersih'), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="divider"></div>

    <h4>Detail Pengeluaran per Bulan</h4>

    {{-- Loop per periode dari riwayat --}}
    @foreach ($riwayat as $row)

        @php
            $transaksiPeriode = $semuaTransaksi->filter(fn($t) =>
                \Carbon\Carbon::parse($t->tanggal)->month == $row['bulan'] &&
                \Carbon\Carbon::parse($t->tanggal)->year  == $row['tahun']
            );
        @endphp

        @if ($transaksiPeriode->count() > 0)

        <div class="periode-header">
            {{ \Carbon\Carbon::createFromDate($row['tahun'], $row['bulan'], 1)->format('F Y') }}
        </div>

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
                @foreach ($transaksiPeriode as $transaksi)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}
                    </td>
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
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td class="text-right text-danger">
                        <strong>Rp {{ number_format($transaksiPeriode->sum('total_transaksi'), 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        @endif

    @endforeach

</body>
</html>