<!DOCTYPE html>
<html style="text-transform: uppercase;">
<head>
    <meta charset="utf-8">
    <title>Nota Jalan {{ $notaJalan->no_invoice }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 5px; }
        p.subtitle { text-align: center; margin-top: 0; color: #666; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 5px; }
        .info-table td:first-child { width: 30%; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #ffc107; color: #212529; padding: 6px; text-align: center; }
        td { padding: 5px 6px; border: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .divider { border-top: 1px solid #ddd; margin: 10px 0; }
        .bukti-nota { width: 150px; border-radius: 5px; margin-top: 5px; }
    </style>
</head>
<body>

    <h3>Detail Nota Jalan</h3>
    <p class="subtitle">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>Bus</td>
            <td>: {{ $notaJalan->bus->nama_bus ?? '-' }} ({{ $notaJalan->bus->plat_nomor ?? '-' }})</td>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($notaJalan->tanggal)->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td>No. Invoice</td>
            <td>: {{ $notaJalan->no_invoice }}</td>
            <td>Supplier</td>
            <td>: {{ $notaJalan->supplier }}</td>
        </tr>
        @if($notaJalan->bukti_nota)
        <tr>
            <td>Bukti Nota</td>
            <td colspan="3">
                :
                <br>
                <img src="{{ storage_path('app/public/' . $notaJalan->bukti_nota) }}" class="bukti-nota">
            </td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Item</th>
                <th width="10%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga Satuan</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notaJalan->details as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $detail->nama_item }}</td>
                <td class="text-center">{{ $detail->qty }}</td>
                <td class="text-center">{{ $detail->satuan }}</td>
                <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada item</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Total Keseluruhan</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format($notaJalan->total_transaksi, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>