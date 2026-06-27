@extends('adminlte::page')

@section('title', 'Edit Bus')

@section('content_header')
    <h1>Edit Bus</h1>
@stop

@section('content')

<x-adminlte-card title="Form Edit Bus" theme="warning" icon="fas fa-edit">

    <form action="{{ route('data-bus.update', $bus->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Bus</label>
                    <input type="text"
                           name="nama_bus"
                           class="form-control @error('nama_bus') is-invalid @enderror"
                           placeholder="Contoh: Bus 01"
                           value="{{ old('nama_bus', $bus->nama_bus) }}"
                           required>
                    @error('nama_bus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Plat Nomor</label>
                    <input type="text"
                           name="plat_nomor"
                           class="form-control @error('plat_nomor') is-invalid @enderror"
                           placeholder="Contoh: DD 1234 AB"
                           value="{{ old('plat_nomor', $bus->plat_nomor) }}"
                           required>
                    @error('plat_nomor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Rute / Trayek</label>
                    <input type="text"
                           name="rute_trayek"
                           class="form-control @error('rute_trayek') is-invalid @enderror"
                           placeholder="Contoh: Makassar - Parepare"
                           value="{{ old('rute_trayek', $bus->rute_trayek) }}">
                    @error('rute_trayek')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Driver</label>
                    <input type="text"
                           name="nama_driver"
                           class="form-control @error('nama_driver') is-invalid @enderror"
                           placeholder="Contoh: Andi Pratama"
                           value="{{ old('nama_driver', $bus->nama_driver) }}"
                           required>
                    @error('nama_driver')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>

        <hr>

        <a href="{{ route('data-bus.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

        <button type="submit" class="btn btn-warning">
            <i class="fas fa-save"></i>
            Update
        </button>

    </form>

</x-adminlte-card>

@stop