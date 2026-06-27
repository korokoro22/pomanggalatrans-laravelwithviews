<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Paket Service</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #007bff; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <h3>Laporan Paket Service</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Nama Bus</th>
                <th>Plat Nomor</th>
                <th>Nama Paket</th>
                <th>Harga</th>
                <th>Item Barang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($paketServices as $index => $paket)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $paket->bus->nama_bus }}</td>
                <td>{{ $paket->bus->plat_nomor }}</td>
                <td>{{ $paket->nama_paket }}</td>
                <td class="text-right">Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                <td>
                    @foreach ($paket->paketServiceItem as $item)
                        - {{ $item->barang->nama_barang }} ({{ $item->qty }} {{ $item->barang->satuan }})<br>
                    @endforeach
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>