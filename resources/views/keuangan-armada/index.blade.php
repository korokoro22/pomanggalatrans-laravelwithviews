@extends('adminlte::page')

@section('title', 'Keuangan Armada')

@section('content_header')
    <h1>Keuangan Armada</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- ACTION BUTTON --}}
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('keuangan-armada.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pemasukan Bus
        </a>
        <a href="{{ route('keuangan-armada.export-pdf', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

{{-- FILTER --}}
<x-adminlte-card title="Filter Data Keuangan Armada" theme="light" icon="fas fa-filter">
    <form method="GET" action="{{ route('keuangan-armada.index') }}">
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
                        @foreach(range(date('Y'), 2024) as $t)
                            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="mt-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('keuangan-armada.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</x-adminlte-card>

{{-- TABLE --}}
<x-adminlte-card title="Keuangan Armada Bus" theme="success" icon="fas fa-money-bill-wave">

    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Periode</th>
                <th>Nama Bus</th>
                <th>Plat Nomor</th>
                <th>Driver</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Pendapatan Bersih</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::createFromDate($row['periode_tahun'], $row['periode_bulan'], 1)->translatedFormat('F Y') }}
                </td>
                <td>{{ $row['bus']->nama_bus }}</td>
                <td>{{ $row['bus']->plat_nomor }}</td>
                <td>{{ $row['bus']->nama_driver }}</td>
                <td class="text-right text-success">
                    Rp {{ number_format($row['pemasukan'], 0, ',', '.') }}
                </td>
                <td class="text-right text-danger">
                    Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}
                </td>
                <td class="text-right">
                    <strong class="{{ $row['bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($row['bersih'], 0, ',', '.') }}
                    </strong>
                </td>
                <td class="text-center">
                    <a href="{{ route('keuangan-armada.show', $row['bus']->id) }}"
                       class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('keuangan-armada.edit', $row['keuangan_id']) }}"
                       class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('keuangan-armada.destroy', $row['keuangan_id']) }}"
                          method="POST"
                          style="display:inline"
                          onsubmit="return confirm('Yakin hapus data pemasukan ini?')">
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
                <td colspan="9" class="text-center text-muted">Belum ada data keuangan armada</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</x-adminlte-card>

@stop