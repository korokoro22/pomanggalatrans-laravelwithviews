<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaksi Keluar - {{ $transaksi->bus->plat_nomor ?? '' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 5px; }
        .info-table td:first-child { width: 30%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #dc3545; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .divider { border-top: 1px solid #ddd; margin: 10px 0; }
        .badge-paket { background-color: #007bff; color: white; padding: 2px 5px; border-radius: 3px; font-size: 10px; }
        .badge-item  { background-color: #6c757d; color: white; padding: 2px 5px; border-radius: 3px; font-size: 10px; }
        .isi-paket { color: #666; font-size: 10px; margin-top: 2px; }
        .item-foto { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-top: 4px; }
    </style>
</head>
<body>

    <h3>Detail Transaksi Keluar</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>Bus</td>
            <td>: {{ $transaksi->bus->nama_bus ?? '-' }}</td>
            <td>Plat Nomor</td>
            <td>: {{ $transaksi->bus->plat_nomor ?? '-' }}</td>
        </tr>
        <tr>
            <td>Driver</td>
            <td>: {{ $transaksi->bus->nama_driver ?? '-' }}</td>
            <td>Rute Trayek</td>
            <td>: {{ $transaksi->bus->rute_trayek ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tipe</th>
                <th width="8%">Foto</th>
                <th>Nama Item</th>
                <th width="8%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="14%">Harga Satuan</th>
                <th width="14%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi->details as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    @if($detail->tipe === 'paket_service')
                        <span class="badge-paket">Paket</span>
                    @else
                        <span class="badge-item">Per Item</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($detail->tipe === 'per_item' && $detail->barang && $detail->barang->foto)
                        <img src="{{ $storagePath . $detail->barang->foto }}"
                            style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                    @else
                        -
                    @endif
                </td>
                <td>
                    {{ $detail->nama_item }}
                    @if($detail->tipe === 'paket_service' && $detail->paketService)
                        <div class="isi-paket">
                            Isi:
                            @foreach($detail->paketService->paketServiceItem as $psi)
                                {{ $psi->barang->nama_barang ?? '-' }} ({{ $psi->qty }})@if(!$loop->last), @endif
                            @endforeach
                        </div>
                    @endif
                </td>
                <td class="text-center">{{ $detail->qty }}</td>
                <td class="text-center">{{ $detail->satuan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada item</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><strong>Total Transaksi</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>