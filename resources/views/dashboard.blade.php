{{-- @extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div>

    </div>
    <x-adminlte-info-box title="Downloads" text="1205" icon="fas fa-lg fa-download" icon-theme="purple"/>
@stop --}}


{{-- ////////////////////////////////////////////////////////// --}}


{{-- @extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

<div class="row">

    <div class="col-md-4">
        <x-adminlte-small-box
            title="Master Barang"
            text="Kelola data barang"
            icon="fas fa-box"
            theme="primary"
            url="/barang"
            url-text="Buka Menu"/>
    </div>

    <div class="col-md-4">
        <x-adminlte-small-box
            title="Data Bus"
            text="Kelola data bus"
            icon="fas fa-bus"
            theme="success"
            url="/bus"
            url-text="Buka Menu"/>
    </div>

    <div class="col-md-4">
        <x-adminlte-small-box
            title="Barang Masuk"
            text="Transaksi barang masuk"
            icon="fas fa-arrow-down"
            theme="info"
            url="/barang-masuk"
            url-text="Buka Menu"/>
    </div>

</div>

<div class="row">

    <div class="col-md-4">
        <x-adminlte-small-box
            title="Barang Keluar"
            text="Transaksi barang keluar"
            icon="fas fa-arrow-up"
            theme="danger"
            url="/barang-keluar"
            url-text="Buka Menu"/>
    </div>

    <div class="col-md-4">
        <x-adminlte-small-box
            title="Laporan"
            text="Lihat laporan"
            icon="fas fa-chart-bar"
            theme="warning"
            url="/laporan"
            url-text="Buka Menu"/>
    </div>

    <div class="col-md-4">
        <x-adminlte-small-box
            title="Riwayat & Dokumentasi"
            text="Riwayat aktivitas"
            icon="fas fa-history"
            theme="secondary"
            url="/riwayat"
            url-text="Buka Menu"/>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <x-adminlte-card title="Riwayat Transaksi Terbaru" theme="light">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>23/06/2026</td>
                        <td>Masuk</td>
                        <td>Ban Bus</td>
                        <td>10</td>
                    </tr>
                </tbody>

            </table>

        </x-adminlte-card>
    </div>
</div>

@stop --}}

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

<div class="row">

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="127"
            text="Total Barang"
            icon="fas fa-box"
            theme="primary"/>
    </div>

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="8"
            text="Total Bus"
            icon="fas fa-bus"
            theme="success"/>
    </div>

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="5"
            text="Barang Masuk Hari Ini"
            icon="fas fa-arrow-down"
            theme="info"/>
    </div>

</div>

<div class="row">

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="5"
            text="Barang Keluar Hari Ini"
            icon="fas fa-arrow-up"
            theme="danger"/>
    </div>

    {{-- <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="4"
            text="Total Laporan"
            icon="fas fa-chart-bar"
            theme="warning"/>
    </div> --}}

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="12"
            text="Riwayat & Dokumentasi"
            icon="fas fa-history"
            theme="secondary"/>
    </div>

</div>


<div class="row">

    <div class="col-md-8">

        <x-adminlte-card
            title="Aktivitas Terbaru"
            theme="lightblue"
            icon="fas fa-clock">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>23/06/2026</td>
                        <td><span class="badge badge-success">Masuk</span></td>
                        <td>Ban Bus</td>
                        <td>10</td>
                    </tr>

                    <tr>
                        <td>23/06/2026</td>
                        <td><span class="badge badge-danger">Keluar</span></td>
                        <td>Oli Mesin</td>
                        <td>5</td>
                    </tr>

                    <tr>
                        <td>22/06/2026</td>
                        <td><span class="badge badge-success">Masuk</span></td>
                        <td>Filter Udara</td>
                        <td>20</td>
                    </tr>

                </tbody>
            </table>

        </x-adminlte-card>

    </div>


    <div class="col-md-4">

        <x-adminlte-card
            title="Informasi Sistem"
            theme="primary"
            icon="fas fa-info-circle">

            <p>
                Selamat datang di Sistem Inventory Bus.
            </p>

            <hr>

            <p>
                Total Barang :
                <strong>127</strong>
            </p>

            <p>
                Total Bus :
                <strong>8</strong>
            </p>

            <p>
                Update terakhir :
                <strong>{{ now()->format('d M Y') }}</strong>
            </p>

        </x-adminlte-card>

    </div>

</div>

@stop