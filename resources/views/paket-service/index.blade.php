@extends('adminlte::page')

@section('title', 'Paket Service')

@section('content_header')
<h1>Paket Service</h1>
@stop

@section('content')

<x-adminlte-card title="Daftar Paket Service" theme="primary" icon="fas fa-list">

    <div class="mb-3">
        <a href="{{ route('paket-service.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Paket Service
        </a>
    </div>

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
            <tr>
                <td>1</td>
                <td>Bus Scania A</td>
                <td>AA 1234 BB</td>
                <td>Paket Service Ringan</td>
                <td>Rp 150.000</td>
                <td>
                    <span class="badge badge-info">Oli Mesin Shell (1 liter)</span>
                    <span class="badge badge-info">Filter Solar (1 pcs)</span>
                </td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Bus Scania A</td>
                <td>AA 1234 BB</td>
                <td>Paket Service Berat</td>
                <td>Rp 300.000</td>
                <td>
                    <span class="badge badge-info">Oli Mesin Pertamina (2 liter)</span>
                    <span class="badge badge-info">Filter Solar (2 pcs)</span>
                </td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Bus Mercedez B</td>
                <td>BB 5678 CC</td>
                <td>Paket Service Ringan</td>
                <td>Rp 175.000</td>
                <td>
                    <span class="badge badge-info">Oli Mesin Shell (1 liter)</span>
                </td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

</x-adminlte-card>

@stop