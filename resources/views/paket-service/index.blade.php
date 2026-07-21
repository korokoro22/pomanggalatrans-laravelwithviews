@extends('adminlte::page')

@section('title', 'Paket Service')

@section('content_header')
<h1 style="text-transform: uppercase;">Paket Service</h1>
@stop

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="text-transform: uppercase;">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

{{-- ACTION BUTTON --}}
<div class="row mb-3" style="text-transform: uppercase;">
    <div class="col-md-12">
        <a href="{{ route('paket-service.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Paket Service
        </a>
        <a href="{{ route('paket-service.export-pdf', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

{{-- FILTER --}}
<x-adminlte-card title="Filter Paket Service" theme="light" icon="fas fa-filter" style="text-transform: uppercase;">

    <form action="{{ route('paket-service.index') }}" method="GET">

        <div class="row">

            <div class="col-md-6">
                <label>Nama Paket</label>
                <input type="text"
                       name="nama_paket" style="text-transform: uppercase;"
                       class="form-control"
                       placeholder="Cari nama paket..."
                       value="{{ request('nama_paket') }}">
            </div>

            <div class="col-md-6">
                <label>Bus</label>
                <select name="bus_id" class="form-control" style="text-transform: uppercase;">
                    <option value="">-- Pilih Bus --</option>
                    @foreach ($buses as $bus)
                        <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                            {{ $bus->nama_bus }} - {{ $bus->plat_nomor }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> FILTER
            </button>
            <a href="{{ route('paket-service.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </form>

</x-adminlte-card>

{{-- TABLE --}}
<x-adminlte-card title="Daftar Paket Service" theme="primary" icon="fas fa-list" style="text-transform: uppercase;">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bus</th>
                <th>Plat Nomor</th>
                <th>Nama Paket</th>
                <th>Harga</th>
                <th>Item Barang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($paketServices as $index => $paket)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $paket->bus->nama_bus }}</td>
                <td>{{ $paket->bus->plat_nomor }}</td>
                <td>{{ $paket->nama_paket }}</td>
                <td>Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                <td>
                    @forelse ($paket->paketServiceItem as $item)
                        <span class="badge badge-info">
                            {{ $item->barang->nama_barang }} ({{ $item->qty }} {{ $item->barang->satuan }})
                        </span>
                    @empty
                        <span class="text-muted">-</span>
                    @endforelse
                </td>
                <td>
                    
                        <div class="d-flex flex-column" style="row-gap: 8px;">
                            <a href="{{ route('paket-service.show', $paket->id) }}" class="btn btn-info btn-sm">
                                Detail
                            </a>
                            <a href="{{ route('paket-service.edit', $paket->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            <form action="{{ route('paket-service.destroy', $paket->id) }}"
                                method="POST"
                                style="display:inline"
                                onsubmit="return confirm('Yakin ingin hapus paket ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    HAPUS
                                </button>
                            </form>
                        </div>
                    
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada data paket service</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    

</x-adminlte-card>

@stop