@extends('adminlte::page')

@section('title', 'Master Barang')

@section('content_header')
    <h1>Master Barang</h1>
@stop

@section('content')

{{-- ACTION BUTTONS --}}
<div class="row mb-3">
    <div class="col-md-12">

        {{-- <a href="#" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Barang
        </a> --}}

        <button class="btn btn-success mr-2">
            <i class="fas fa-file-export"></i> Export
        </button>

        <button class="btn btn-dark" data-toggle="modal" data-target="#modal-scan-qr">
            <i class="fas fa-qrcode"></i> Scan QR Code
        </button>

    </div>
</div>

{{-- FILTER SECTION --}}
<x-adminlte-card title="Filter Data Barang" theme="light" icon="fas fa-filter">

    <div class="row">

        {{-- Search Nama Barang --}}
        <div class="col-md-4">
            <label>Nama Barang</label>
            <input type="text" class="form-control" placeholder="Cari nama barang...">
        </div>

        {{-- Filter Bulan --}}
        <div class="col-md-4">
            <label>Bulan Masuk</label>
            <select class="form-control">
                <option>-- Pilih Bulan --</option>
                <option>Januari</option>
                <option>Februari</option>
                <option>Maret</option>
                <option>April</option>
                <option>Mei</option>
                <option>Juni</option>
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
<x-adminlte-card title="Daftar Master Barang" theme="lightblue" icon="fas fa-box">

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>ID Barang</th>
                    <th>Foto</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Qty Satuan</th>
                    <th>Stok saat ini</th>
                    <th>Tanggal Masuk</th>
                    <th>QR Code</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td class="text-center">1</td>
                    <td>BRG-001</td>
                    <td>
                        <img src="https://via.placeholder.com/50"
                             style="border-radius:8px">
                    </td>
                    <td>Ban Bus</td>
                    <td>4</td>
                    <td>dus</td>
                    <td>4 Pcs</td>
                    <td><b>3 Pcs</b></td>
                    <td>22-06-2026</td>
                    <td class="text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=BRG-001"
                             alt="QR BRG-001" style="border-radius:4px">
                    </td>
                </tr>

                <tr>
                    <td class="text-center">2</td>
                    <td>BRG-002</td>
                    <td>
                        <img src="https://via.placeholder.com/50"
                             style="border-radius:8px">
                    </td>
                    <td>Baut Roda</td>
                    <td>1</td>
                    <td>lusin</td>
                    <td>12 Pcs</td>
                    <td><b>8 Pcs</b></td>
                    <td>22-06-2026</td>
                    <td class="text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=BRG-002"
                             alt="QR BRG-002" style="border-radius:4px">
                    </td>
                </tr>

                <tr>
                    <td class="text-center">3</td>
                    <td>BRG-003</td>
                    <td>
                        <img src="https://via.placeholder.com/50"
                             style="border-radius:8px">
                    </td>
                    <td>Oli Mesin</td>
                    <td>1</td>
                    <td>Dus</td>
                    <td>6 Botol</td>
                    <td><b>3 Botol</b></td>
                    <td>22-06-2026</td>
                    <td class="text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=BRG-003"
                             alt="QR BRG-003" style="border-radius:4px">
                    </td>
                </tr>

            </tbody>

        </table>
    </div>

</x-adminlte-card>


{{-- MODAL: SCAN QR CODE (UI only, belum ada logic) --}}
<div class="modal fade" id="modal-scan-qr" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-qrcode mr-1"></i> Scan QR Code
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <p class="text-muted">Fitur scan QR Code akan segera tersedia.</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>

@stop