@extends('adminlte::page')

@section('title', 'Detail Paket Service')

@section('content_header')
    <h1 style="text-transform: uppercase;">Detail Paket Service</h1>
@stop

@section('content')

<div class="my-3" style="text-transform: uppercase;">
    <a href="{{ route('paket-service.export-pdf-show', $paketService->id) }}" class="btn btn-success">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
    <a href="{{ route('paket-service.edit', $paketService->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        Edit
    </a>
</div>

{{-- INFORMASI PAKET --}}
<x-adminlte-card title="Informasi Paket Service" theme="primary" icon="fas fa-list" style="text-transform: uppercase;">

    <table class="table table-borderless">
        <tr>
            <td width="30%"><strong>Nama Bus</strong></td>
            <td>: {{ $paketService->bus->nama_bus }}</td>
        </tr>
        <tr>
            <td><strong>Plat Nomor</strong></td>
            <td>: {{ $paketService->bus->plat_nomor }}</td>
        </tr>
        <tr>
            <td><strong>Nama Paket</strong></td>
            <td>: {{ $paketService->nama_paket }}</td>
        </tr>
        <tr>
            <td><strong>Harga</strong></td>
            <td>: Rp {{ number_format($paketService->harga, 0, ',', '.') }}</td>
        </tr>
    </table>

</x-adminlte-card>

{{-- ITEM BARANG DALAM PAKET --}}
<x-adminlte-card title="Item Barang dalam Paket" theme="primary" icon="fas fa-boxes" style="text-transform: uppercase;">

    <table class="table table-bordered table-striped">
        <thead class="text-center">
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
                    @if ($item->barang->kategori == 'oli_mesin')
                        <span class="badge badge-warning">Oli Mesin</span>
                    @elseif ($item->barang->kategori == 'filter_solar')
                        <span class="badge badge-info">Filter Solar</span>
                    @endif
                </td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-center">{{ $item->barang->satuan }}</td>
                <td class="text-center">
                    <span class="{{ $item->barang->stok_saat_ini <= 5 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold' }}">
                        {{ number_format($item->barang->stok_saat_ini) }} Pcs
                    </span>
                    @if ($item->barang->stok_saat_ini <= 5)
                        <span class="badge badge-danger">Menipis</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada item barang</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</x-adminlte-card>

<div class="mb-3" style="text-transform: uppercase;">
    <a href="{{ route('paket-service.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
</div>

@stop