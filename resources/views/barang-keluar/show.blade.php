@extends('adminlte::page')

@section('title', 'Detail Transaksi Keluar')

@section('content_header')
    <h1>Detail Transaksi Keluar</h1>
@stop

@section('content')

<div class="mb-3">
    <a href="{{ route('barang-keluar.export-pdf-show', $transaksi->id) }}" class="btn btn-success">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
    <a href="{{ route('barang-keluar.edit', $transaksi->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>
</div>

{{-- INFO TRANSAKSI --}}
<x-adminlte-card title="Informasi Transaksi" theme="danger" icon="fas fa-file-invoice">
    <div class="row">

        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <td width="40%"><strong>Bus</strong></td>
                    <td>: {{ $transaksi->bus->nama_bus ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Plat Nomor</strong></td>
                    <td>: {{ $transaksi->bus->plat_nomor ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Rute Trayek</strong></td>
                    <td>: {{ $transaksi->bus->rute_trayek ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <td width="40%"><strong>Driver</strong></td>
                    <td>: {{ $transaksi->bus->nama_driver ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Total Transaksi</strong></td>
                    <td>: <strong class="text-danger">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

    </div>
</x-adminlte-card>

{{-- DETAIL ITEM --}}
<x-adminlte-card title="Detail Item Transaksi" theme="danger" icon="fas fa-boxes">

    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Tipe</th>
                <th>Nama Item</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @forelse($transaksi->details as $detail)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    @if($detail->tipe === 'paket_service')
                        <span class="badge badge-primary">Paket Service</span>
                    @else
                        <span class="badge badge-secondary">Per Item</span>
                    @endif
                </td>
                <td>
                    {{ $detail->nama_item }}
                    @if($detail->tipe === 'paket_service' && $detail->paketService)
                        <br>
                        <small class="text-muted">
                            Isi paket:
                            @foreach($detail->paketService->paketServiceItem as $psi)
                                {{ $psi->barang->nama_barang ?? '-' }} ({{ $psi->qty }})
                                @if(!$loop->last), @endif
                            @endforeach
                        </small>
                    @endif
                </td>
                <td class="text-center">{{ $detail->qty }}</td>
                <td class="text-center">{{ $detail->satuan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Tidak ada item</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>Total Transaksi</strong></td>
                <td class="text-right">
                    <strong class="text-danger">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-3">
        <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

</x-adminlte-card>

@stop