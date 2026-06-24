@extends('adminlte::page')

@section('title', 'Keuangan Armada')

@section('content_header')
    <h1>Keuangan Armada</h1>
@stop

@section('content')

{{-- ACTION BUTTON --}}
<div class="row mb-3">
    <div class="col-md-12">

        <a href="#" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pemasukan Bus
        </a>

        <button class="btn btn-success">
            <i class="fas fa-file-export"></i> Export
        </button>

    </div>
</div>


{{-- FILTER --}}
<x-adminlte-card title="Filter Data Keuangan Armada" theme="light" icon="fas fa-filter">

    <div class="row">

        {{-- Plat Bus --}}
        <div class="col-md-4">
            <label>Plat Bus</label>

            <select class="form-control">
                <option>-- Semua Bus --</option>
                <option>DD 1234 AB</option>
                <option>DD 5678 XY</option>
                <option>DD 9012 ZZ</option>
            </select>
        </div>

        {{-- Bulan --}}
        <div class="col-md-4">
            <label>Bulan</label>

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

        {{-- Tahun --}}
        <div class="col-md-4">
            <label>Tahun</label>

            <select class="form-control">
                <option>2026</option>
                <option>2025</option>
                <option>2024</option>
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
<x-adminlte-card title="Keuangan Armada Bus" theme="success" icon="fas fa-money-bill-wave">

    <table class="table table-bordered table-striped">

        <thead class="text-center">
        <tr>
            <th>No</th>
            <th>Periode</th>
            <th>Nama Bus / Nomor Pintu</th>
            <th>Plat Nomor</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Pendapatan Bersih</th>
            <th width="170">Aksi</th>
        </tr>
        </thead>

        <tbody>

        <tr>
            <td>1</td>
            <td>Juni 2026</td>
            <td>Bus 01</td>
            <td>DD 1234 AB</td>
            <td>Rp 120.000.000</td>
            <td>Rp 35.000.000</td>
            <td><b>Rp 85.000.000</b></td>

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
            <td>Juni 2026</td>
            <td>Bus 07</td>
            <td>DD 5678 XY</td>
            <td>Rp 105.000.000</td>
            <td>Rp 28.500.000</td>
            <td><b>Rp 76.500.000</b></td>

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
            <td>Juni 2026</td>
            <td>Bus 12</td>
            <td>DD 9012 ZZ</td>
            <td>Rp 95.000.000</td>
            <td>Rp 31.000.000</td>
            <td><b>Rp 64.000.000</b></td>

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