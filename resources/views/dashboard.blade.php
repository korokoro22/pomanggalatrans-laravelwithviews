@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

<div class="row">

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="{{ $totalBarang }}"
            text="Total Barang"
            icon="fas fa-box"
            theme="primary"/>
    </div>

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="{{ $totalBus }}"
            text="Total Bus"
            icon="fas fa-bus"
            theme="success"/>
    </div>

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="{{ $barangMasukHariIni }}"
            text="Barang Masuk Hari Ini"
            icon="fas fa-arrow-down"
            theme="info"/>
    </div>

</div>

<div class="row">

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="{{ $barangKeluarHariIni }}"
            text="Barang Keluar Hari Ini"
            icon="fas fa-arrow-up"
            theme="danger"/>
    </div>

</div>


<div class="row">

    <div class="col-md-8">

        <x-adminlte-card
            title="Aktivitas Terbaru"
            theme="lightblue"
            icon="fas fa-clock">
            <div class="table-responsive">
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
                        @forelse($aktivitasTerbaru as $aktivitas)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($aktivitas['tanggal'])->format('d/m/Y') }}</td>
                                <td>
                                    @if($aktivitas['jenis'] === 'masuk')
                                        <span class="badge badge-success">Masuk</span>
                                    @else
                                        <span class="badge badge-danger">Keluar</span>
                                    @endif
                                </td>
                                <td>{{ $aktivitas['nama'] }}</td>
                                <td>{{ $aktivitas['qty'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada aktivitas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            

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
                <strong>{{ $totalBarang }}</strong>
            </p>

            <p>
                Total Bus :
                <strong>{{ $totalBus }}</strong>
            </p>

            <p>
                Update terakhir :
                <strong>{{ now()->format('d M Y') }}</strong>
            </p>

        </x-adminlte-card>

    </div>

</div>

@stop