<!DOCTYPE html>
<html style="text-transform: uppercase;">
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Keluar</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #dc3545; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <h3>Laporan Transaksi Keluar</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal</th>
                <th width="18%">Bus</th>
                <th width="14%">Driver</th>
                <th>Item Transaksi</th>
                <th width="14%">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksiKeluars as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i') }}
                </td>
                <td>
                    {{ $transaksi->bus->nama_bus ?? '-' }}<br>
                    <small>{{ $transaksi->bus->plat_nomor ?? '' }}</small>
                </td>
                <td>{{ $transaksi->bus->nama_driver ?? '-' }}</td>
                <td>
                    @foreach($transaksi->details as $detail)
                        - {{ $detail->nama_item }}
                        <small>({{
                            match($detail->tipe) {
                                'paket_service'     => 'Paket',
                                'nota_jalan'        => 'Nota Jalan',
                                'biaya_pengerjaan'  => 'Biaya Pengerjaan',
                                default             => 'Item',
                            }
                        }})</small><br>
                    @endforeach
                </td>
                {{-- <td>
                    @foreach($transaksi->details as $detail)
                        <div style="margin-bottom: 4px;">
                            - {{ $detail->nama_item }}
                            <small>({{ $detail->tipe === 'paket_service' ? 'Paket' : 'Item' }})</small>
                            @if($detail->tipe === 'per_item' && $detail->barang && $detail->barang->foto)
                                <br>
                                <img src="{{ $storagePath . $detail->barang->foto }}"
                                    style="width:40px; height:40px; object-fit:cover; border-radius:3px; margin-top:2px;">
                            @endif
                        </div>
                    @endforeach
                </td> --}}
                <td class="text-right">
                    Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Total Keseluruhan</strong></td>
                <td class="text-right">
                    <strong>
                        Rp {{ number_format($transaksiKeluars->sum('total_transaksi'), 0, ',', '.') }}
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>