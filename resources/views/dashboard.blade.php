@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>DASHBOARD</h1>
@stop

@section('content')

<div class="row" style="text-transform: uppercase;">

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="{{ $totalBarang }}"
            text="TOTAL BARANG"
            icon="fas fa-box"
            theme="primary"/>
    </div>

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="{{ $totalBus }}"
            text="TOTAL BUS"
            icon="fas fa-bus"
            theme="success"/>
    </div>

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="{{ $barangMasukHariIni }}"
            text="BARANG MASUK HARI INI"
            icon="fas fa-arrow-down"
            theme="info"/>
    </div>

</div>

<div class="row">

    <div class="col-lg-4 col-6">
        <x-adminlte-small-box
            title="{{ $barangKeluarHariIni }}"
            text="BARANG KELUAR HARI INI"
            icon="fas fa-arrow-up"
            theme="danger"/>
    </div>

</div>


<div class="row" style="text-transform: uppercase;">

    <div class="col-md-8">

        <x-adminlte-card
            title="AKTIVITAS TERBARU"
            theme="lightblue"
            icon="fas fa-clock">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>TANGGAL</th>
                            <th>JENIS</th>
                            <th>BARANG</th>
                            <th>JUMLAH</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($aktivitasTerbaru as $aktivitas)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($aktivitas['tanggal'])->format('d/m/Y') }}</td>
                                <td>
                                    @if($aktivitas['jenis'] === 'masuk')
                                        <span class="badge badge-success">MASUK</span>
                                    @else
                                        <span class="badge badge-danger">KELUAR</span>
                                    @endif
                                </td>
                                <td>{{ $aktivitas['nama'] }}</td>
                                <td>{{ $aktivitas['qty'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">BELUM ADA AKTIVITAS</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            

        </x-adminlte-card>

    </div>


    <div class="col-md-4">

        <x-adminlte-card
            title="INFORMASI SISTEM"
            theme="primary"
            icon="fas fa-info-circle">

            <p>
                SELAMAT DATANG DI SISTEM INVENTORY BUS.
            </p>

            <hr>

            <p>
                TOTAL BARANG :
                <strong>{{ $totalBarang }}</strong>
            </p>

            <p>
                TOTAL BUS :
                <strong>{{ $totalBus }}</strong>
            </p>

            <p>
                UPDATE TERAKHIR :
                <strong>{{ now()->format('d M Y') }}</strong>
            </p>

        </x-adminlte-card>

    </div>

</div>

@stop