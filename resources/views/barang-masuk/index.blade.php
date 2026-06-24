@extends('adminlte::page')

@section('title', 'Barang Masuk')

@section('content_header')
    <h1>Barang Masuk</h1>
@stop

@section('content')

{{-- ACTION BUTTON --}}
<div class="row mb-3">
    <div class="col-md-12">

        <a href="#" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Data
        </a>

        <button class="btn btn-success mr-2">
            <i class="fas fa-file-export"></i> Export
        </button>

    </div>
</div>


{{-- FILTER SECTION --}}
<x-adminlte-card title="Filter Barang Masuk" theme="light" icon="fas fa-filter">

    <div class="row">

        {{-- Filter Nama Barang --}}
        <div class="col-md-6">
            <label>Nama Barang</label>
            <input type="text" class="form-control" placeholder="Cari nama barang...">
        </div>

        {{-- Filter Bulan --}}
        <div class="col-md-6">
            <label>Bulan Masuk</label>
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


{{-- TABLE SECTION --}}
<x-adminlte-card title="Log Barang Masuk" theme="success" icon="fas fa-arrow-down">

    <table class="table table-bordered table-striped">

        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Tanggal Masuk</th>
                <th>No Invoice</th>
                <th>Supplier</th>
                <th>Nama Barang</th>
                <th>Bukti Nota</th>
                <th>Qty</th>
                <th>Total (Pcs)</th>
                <th>Nominal</th>
                <th>Penerima</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td>1</td>
                <td>23-06-2026</td>
                <td>INV-001</td>
                <td>Toko ABC</td>
                <td>Ban Bus</td>

                <td>
                    <img src="https://via.placeholder.com/60"
                         style="border-radius:5px">
                </td>

                <td>2 Dus</td>
                <td><b>8 Pcs</b></td>
                <td>Rp 2.000.000</td>
                <td>Andi</td>
            </tr>

            <tr>
                <td>2</td>
                <td>22-06-2026</td>
                <td>INV-002</td>
                <td>Toko XYZ</td>
                <td>Baut Roda</td>

                <td>
                    <img src="https://via.placeholder.com/60"
                         style="border-radius:5px">
                </td>

                <td>1 Lusin</td>
                <td><b>12 Pcs</b></td>
                <td>Rp 150.000</td>
                <td>Budi</td>
            </tr>

            <tr>
                <td>3</td>
                <td>21-06-2026</td>
                <td>INV-003</td>
                <td>Toko DEF</td>
                <td>Oli Mesin</td>

                <td>
                    <img src="https://via.placeholder.com/60"
                         style="border-radius:5px">
                </td>

                <td>3 Dus</td>
                <td><b>18 Botol</b></td>
                <td>Rp 1.500.000</td>
                <td>Rizky</td>
            </tr>

        </tbody>

    </table>

</x-adminlte-card>

@stop