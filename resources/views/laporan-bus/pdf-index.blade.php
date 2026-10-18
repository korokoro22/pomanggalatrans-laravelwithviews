<!DOCTYPE html>
<html style="text-transform: uppercase;">
<head>
    <meta charset="utf-8">
    <title>Laporan Bus</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #dc3545; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>

    <h3>Laporan Pengeluaran Bus</h3>
    <p class="subtitle">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
        @if(request()->filled('tipe') && request('tipe') !== 'semua')
            | Tipe: {{ request('tipe') === 'paket_service' ? 'Paket Service' : 'Barang Biasa' }}
        @endif
        @if(request()->filled('sub_tipe_paket') && request('sub_tipe_paket') !== 'semua')
            | Sub-tipe Paket: {{ request('sub_tipe_paket') === 'paket_reguler' ? 'Paket Reguler' : 'Paket Oli' }}
        @endif
        @if(request()->filled('sub_tipe_barang') && request('sub_tipe_barang') !== 'semua')
            | Sub-tipe Barang: {{ request('sub_tipe_barang') === 'pembelian_toko' ? 'Pembelian Toko' : 'Pembelian Langsung' }}
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tanggal</th>
                <th>Plat Nomor</th>
                <th>Driver</th>
                <th>Item Transaksi</th>
                <th width="12%">Total</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i') }}</td>
                <td>{{ $transaksi->bus->plat_nomor }}</td>
                <td>{{ $transaksi->bus->nama_driver }}</td>
                <td>
                    @foreach ($transaksi->details as $detail)
                        - {{ $detail->nama_item }}<br>
                    @endforeach
                </td>
                <td class="text-right text-danger">
                    Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}
                </td>
                <td>
                    @php
                        // Ambil keterangan yang terisi, lewati yang sama persis dengan nama item
                        // (tipe biaya pengerjaan menyimpan keterangan sebagai nama_item juga)
                        $keteranganList = $transaksi->details
                            ->filter(fn($d) => filled($d->keterangan) && $d->keterangan !== $d->nama_item);
                    @endphp
                    @forelse ($keteranganList as $detail)
                        <small><strong>{{ $detail->nama_item }}:</strong> {{ $detail->keterangan }}</small><br>
                    @empty
                        -
                    @endforelse
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Total Keseluruhan</strong></td>
                <td class="text-right text-danger">
                    <strong>Rp {{ number_format($transaksis->sum('total_transaksi'), 0, ',', '.') }}</strong>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>