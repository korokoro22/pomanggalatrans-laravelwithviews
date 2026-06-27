@extends('adminlte::page')

@section('title', 'Barang Keluar')

@section('content_header')
    <h1>Barang Keluar</h1>
@stop

@section('content')

{{-- ACTION BUTTON --}}
<div class="row mb-3">
    <div class="col-md-12">

        <a href="barang-keluar/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Data
        </a>

        <button class="btn btn-success">
            <i class="fas fa-file-export"></i> Export
        </button>

    </div>
</div>


{{-- FILTER --}}
<x-adminlte-card title="Filter Barang Keluar" theme="light" icon="fas fa-filter">

    <div class="row">

        <div class="col-md-6">
            <label>Nama Barang</label>
            <input type="text" class="form-control" placeholder="Cari nama barang...">
        </div>

        <div class="col-md-6">
            <label>Bulan Transaksi</label>
            <select class="form-control">
                <option>-- Pilih Bulan --</option>
                <option>Januari</option>
                <option>Februari</option>
                <option>Maret</option>
                <option>April</option>
                <option>Mei</option>
                <option>Juni</option>
                <option>Juli</option>
                <option>Agustus</option>
                <option>September</option>
                <option>Oktober</option>
                <option>November</option>
                <option>Desember</option>
            </select>
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
<x-adminlte-card title="Log Barang Keluar" theme="danger" icon="fas fa-arrow-up">

    <table class="table table-bordered table-striped">

        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Plat Bus</th>
                <th>Driver</th>
                <th>Transaksi</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td>1</td>
                <td>Ban Bus</td>
                <td>DD 1234 AB</td>
                <td>Andi Pratama</td>

                {{-- MULTI TRANSAKSI --}}
                <td>
                    <span class="badge badge-light border text-dark">Paket Service</span>
                    <span class="badge badge-light border text-dark">Oli Garda</span>
                </td>

                <td>Rp3.500.000</td>
                <td>23-06-2026</td>

                <td>
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
                <td>Baut Roda</td>
                <td>DD 5678 XY</td>
                <td>Budi Santoso</td>

                <td>
                    <span class="badge badge-light border text-dark">Paket Rem</span>
                    <span class="badge badge-light border text-dark">Oli Mesin</span>
                </td>

                <td>Rp4.500.000</td>
                <td>22-06-2026</td>

                <td>
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