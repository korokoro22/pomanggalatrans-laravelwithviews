<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Paket Service {{ $paketService->nama_paket }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 4px 5px; }
        .info-table td:first-child { width: 35%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #007bff; color: white; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider { border-top: 1px solid #ddd; margin: 10px 0; }
        .stok-menipis { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h3>Detail Paket Service</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>Nama Bus</td>
            <td>: {{ $paketService->bus->nama_bus }}</td>
        </tr>
        <tr>
            <td>Plat Nomor</td>
            <td>: {{ $paketService->bus->plat_nomor }}</td>
        </tr>
        <tr>
            <td>Nama Paket</td>
            <td>: {{ $paketService->nama_paket }}</td>
        </tr>
        <tr>
            <td>Harga</td>
            <td>: Rp {{ number_format($paketService->harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <h4>Item Barang dalam Paket</h4>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Stok Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($paketService->paketServiceItem as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td class="text-center">
                    @if ($item->barang->kategori == 'oli_mesin') Oli Mesin
                    @elseif ($item->barang->kategori == 'filter_solar') Filter Solar
                    @endif
                </td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-center">{{ $item->barang->satuan }}</td>
                <td class="text-center {{ $item->barang->stok_saat_ini <= 5 ? 'stok-menipis' : '' }}">
                    {{ number_format($item->barang->stok_saat_ini) }} Pcs
                    {{ $item->barang->stok_saat_ini <= 5 ? '(Menipis)' : '' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada item barang</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>