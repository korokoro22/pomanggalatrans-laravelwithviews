@extends('adminlte::page')

@section('title', 'Tambah Pemasukan Bus')

@section('content_header')
    <h1>Tambah Pemasukan Bus</h1>
@stop

@section('content')

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<x-adminlte-card title="Form Pemasukan Bus" theme="success" icon="fas fa-plus-circle">

    <form action="{{ route('keuangan-armada.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bus</label>
                    <select name="bus_id" class="form-control" required>
                        <option value="">-- Pilih Bus --</option>
                        @foreach($busList as $bus)
                            <option value="{{ $bus->id }}"
                                {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                                {{ $bus->nama_bus }} - {{ $bus->plat_nomor }}
                            </option>
                        @endforeach
                    </select>
                    @error('bus_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pemasukan</label>
                    <input type="number"
                           name="pemasukan"
                           class="form-control"
                           placeholder="Masukkan jumlah pemasukan"
                           min="0"
                           value="{{ old('pemasukan') }}"
                           required>
                    @error('pemasukan')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bulan</label>
                    <select name="periode_bulan" class="form-control" required>
                        <option value="">-- Pilih Bulan --</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                            <option value="{{ $i + 1 }}"
                                {{ old('periode_bulan', now()->month) == $i + 1 ? 'selected' : '' }}>
                                {{ $bln }}
                            </option>
                        @endforeach
                    </select>
                    @error('periode_bulan')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tahun</label>
                    <select name="periode_tahun" class="form-control" required>
                        @foreach(range(date('Y'), 2024) as $t)
                            <option value="{{ $t }}"
                                {{ old('periode_tahun', now()->year) == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                    @error('periode_tahun')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <a href="{{ route('keuangan-armada.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
        </button>

    </form>

</x-adminlte-card>

@stop