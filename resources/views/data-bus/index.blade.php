@extends('adminlte::page')

@section('title', 'Data Bus')

@section('content_header')
    <h1 style="text-transform: uppercase;">Data Bus</h1>
@stop

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

{{-- ACTION BUTTON --}}
<div class="row mb-3" style="text-transform: uppercase;">
    <div class="col-md-12">
        <a href="{{ route('data-bus.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Bus
        </a>
        <a href="{{ route('data-bus.export-pdf', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
    
</div>

{{-- FILTER --}}
<x-adminlte-card title="Filter Data Bus" theme="light" icon="fas fa-filter" style="text-transform: uppercase;">

    <form action="{{ route('data-bus.index') }}" method="GET">

        <div class="row">

            <div class="col-md-6">
                <label>Nama Bus</label>
                <input type="text"
                       name="nama_bus"
                       class="form-control"
                       style="text-transform: uppercase;"
                       placeholder="Cari nama bus..."
                       value="{{ request('nama_bus') }}">
            </div>

            <div class="col-md-6">
                <label>Plat Nomor</label>
                <input type="text"
                       name="plat_nomor"
                       style="text-transform: uppercase;"
                       class="form-control"
                       placeholder="Cari plat nomor..."
                       value="{{ request('plat_nomor') }}">
            </div>

        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> FILTER
            </button>
            <a href="{{ route('data-bus.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </form>

</x-adminlte-card>

{{-- TABLE --}}
<x-adminlte-card title="Daftar Bus" theme="info" icon="fas fa-bus" style="text-transform: uppercase;">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Bus</th>
                    <th>Plat Nomor</th>
                    <th>Rute / Trayek</th>
                    <th>Nama Driver</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($buses as $index => $bus)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $bus->nama_bus }}</td>
                    <td>{{ $bus->plat_nomor }}</td>
                    <td>{{ $bus->rute_trayek ?? '-' }}</td>
                    <td>{{ $bus->nama_driver }}</td>
                    <td class="text-center">
                        <div class="d-flex flex-column" style="row-gap: 8px;">
                            <a href="{{ route('data-bus.show', $bus->id) }}"
                            class="btn btn-info btn-sm">
                                Detail
                            </a>
                            <a href="{{ route('data-bus.edit', $bus->id) }}"
                            class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            <form action="{{ route('data-bus.destroy', $bus->id) }}"
                                method="POST"
                                style="display:inline"
                                onsubmit="return confirm('Yakin ingin hapus data bus ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data bus</td>
                </tr>
                @endforelse

            </tbody>

        </table>
    </div>
    

</x-adminlte-card>

@stop