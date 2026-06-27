@extends('adminlte::page')

@section('title', 'Data Bus')

@section('content_header')
    <h1>Data Bus</h1>
@stop

@section('content')

{{-- ACTION BUTTON --}}
<div class="row mb-3">
    <div class="col-md-12">

        <a href="{{ route('data-bus.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Bus
        </a>

    </div>
</div>


{{-- FILTER --}}
<x-adminlte-card title="Filter Data Bus" theme="light" icon="fas fa-filter">

    <div class="row">

        {{-- Nama Bus --}}
        <div class="col-md-6">
            <label>Nama Bus</label>
            <input type="text"
                   class="form-control"
                   placeholder="Cari nama bus atau nomor pintu...">
        </div>

        {{-- Plat Nomor --}}
        <div class="col-md-6">
            <label>Plat Nomor</label>
            <input type="text"
                   class="form-control"
                   placeholder="Cari plat nomor...">
        </div>

    </div>

    <div class="mt-3">

        <button class="btn btn-primary">
            <i class="fas fa-search"></i> Filter
        </button>

        <button class="btn btn-secondary">
            Reset
        </button>

    </div>

</x-adminlte-card>


{{-- TABLE --}}
<x-adminlte-card title="Daftar Bus" theme="info" icon="fas fa-bus">

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

            <tr>
                <td>1</td>
                <td>Bus 01</td>
                <td>DD 1234 AB</td>
                <td>Makassar - Parepare</td>
                <td>Andi Pratama</td>

                <td class="text-center">

                    <button class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>

                </td>
            </tr>

            <tr>
                <td>2</td>
                <td>Bus 07</td>
                <td>DD 5678 XY</td>
                <td>Makassar - Palopo</td>
                <td>Budi Santoso</td>

                <td class="text-center">

                    <button class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>

                </td>
            </tr>

            <tr>
                <td>3</td>
                <td>Bus 12</td>
                <td>DD 9012 ZZ</td>
                <td>Makassar - Toraja</td>
                <td>Rizky Maulana</td>

                <td class="text-center">

                    <button class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>

                </td>
            </tr>

        </tbody>

    </table>

</x-adminlte-card>

@stop