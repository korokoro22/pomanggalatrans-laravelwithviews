@extends('adminlte::page')

@section('title', 'Detail Nota Jalan')

@section('content_header')
    <h1 style="text-transform: uppercase;">Detail Nota Jalan</h1>
@stop

@section('content')

<div class="mb-3">
    <a href="{{ route('nota-jalan.export-pdf-show', $notaJalan->id) }}" class="btn btn-success">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
</div>

<x-adminlte-card title="Informasi Nota Jalan" theme="warning" icon="fas fa-route" style="text-transform: uppercase;">

    <table class="table table-borderless mb-0" style="text-transform: uppercase;">
        <tr>
            <td width="20%"><strong>Bus</strong></td>
            <td width="30%">: {{ $notaJalan->bus->nama_bus ?? '-' }} ({{ $notaJalan->bus->plat_nomor ?? '-' }})</td>
            <td width="20%"><strong>Tanggal</strong></td>
            <td width="30%">: {{ \Carbon\Carbon::parse($notaJalan->tanggal)->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>No. Invoice</strong></td>
            <td>: {{ $notaJalan->no_invoice }}</td>
            <td><strong>Supplier</strong></td>
            <td>: {{ $notaJalan->supplier }}</td>
        </tr>
        @if($notaJalan->bukti_nota)
        <tr>
            <td><strong>Bukti Nota</strong></td>
            <td colspan="3">
                :
                <br>
                <img src="{{ asset('storage/' . $notaJalan->bukti_nota) }}"
                     style="width:150px; border-radius:5px; margin-top:5px;">
            </td>
        </tr>
        @endif
    </table>

</x-adminlte-card>

<x-adminlte-card title="Item Dibeli" theme="warning" icon="fas fa-shopping-basket" style="text-transform: uppercase;">

    <table class="table table-bordered table-striped">
        <thead class="text-center">
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
            @forelse($notaJalan->details as $detail)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $detail->nama_item }}</td>
                <td class="text-center">{{ $detail->qty }}</td>
                <td class="text-center">{{ $detail->satuan }}</td>
                <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Tidak ada item</td>
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

    <div class="mt-3">
        <a href="{{ route('nota-jalan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('nota-jalan.edit', $notaJalan->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>

</x-adminlte-card>

@stop