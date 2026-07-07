@extends('adminlte::page')

@section('title', 'Barang Keluar')

@section('content_header')
    <h1>Barang Keluar</h1>
@stop

@section('content')

{{-- ACTION BUTTON --}}
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('barang-keluar.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
        <a href="{{ route('barang-keluar.export-pdf', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-export"></i> Export PDF
        </a>
    </div>
</div>

{{-- FILTER --}}
<x-adminlte-card title="Filter Barang Keluar" theme="light" icon="fas fa-filter">
    <form method="GET" action="{{ route('barang-keluar.index') }}">
        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label>Bus</label>
                    <select name="bus_id" class="form-control">
                        <option value="">-- Semua Bus --</option>
                        @foreach($busList as $bus)
                            <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                                {{ $bus->nama_bus }} - {{ $bus->plat_nomor }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                            <option value="{{ $i + 1 }}" {{ request('bulan') == $i + 1 ? 'selected' : '' }}>
                                {{ $bln }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Tahun</label>
                    <select name="tahun" class="form-control">
                        <option value="">-- Semua Tahun --</option>
                        @foreach(range(date('Y'), 2024) as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>

        <div class="mt-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>
</x-adminlte-card>

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
    </div>
@endif

{{-- TABLE --}}
<x-adminlte-card title="Log Barang Keluar" theme="danger" icon="fas fa-arrow-up">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Bus</th>
                <th>Driver</th>
                <th>Item Transaksi</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($transaksiKeluars as $transaksi)
            <tr>
                <td class="text-center">{{ $loop->iteration + ($transaksiKeluars->currentPage() - 1) * $transaksiKeluars->perPage() }}</td>
                <td>
                    {{ $transaksi->bus->nama_bus ?? '-' }}<br>
                    <small class="text-muted">{{ $transaksi->bus->plat_nomor ?? '' }}</small>
                </td>
                <td>{{ $transaksi->bus->nama_driver ?? '-' }}</td>
                <td>
                    @foreach($transaksi->details as $detail)
                        <span class="badge badge-light border text-dark">
                            {{ $detail->nama_item }}
                        </span>
                    @endforeach
                </td>
                <td class="text-right">
                    Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i') }}
                </td>
                <td class="text-center">
                    <a href="{{ route('barang-keluar.show', $transaksi->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('barang-keluar.edit', $transaksi->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('barang-keluar.destroy', $transaksi->id) }}"
                          method="POST"
                          style="display:inline"
                          onsubmit="return confirm('Yakin hapus transaksi ini? Stok barang akan dikembalikan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Belum ada data transaksi keluar</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    

    <div class="mt-3">
        {{ $transaksiKeluars->links() }}
    </div>

</x-adminlte-card>

@stop