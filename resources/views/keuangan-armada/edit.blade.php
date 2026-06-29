@extends('adminlte::page')

@section('title', 'Edit Pemasukan Bus')

@section('content_header')
    <h1>Edit Pemasukan Bus</h1>
@stop

@section('content')

<x-adminlte-card title="Form Edit Pemasukan" theme="success" icon="fas fa-edit">

    <form action="{{ route('keuangan-armada.update', $keuangan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bus</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $keuangan->bus->nama_bus }} - {{ $keuangan->bus->plat_nomor }}"
                           disabled>
                    <small class="text-muted">Bus tidak dapat diubah</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Periode</label>
                    <input type="text"
                           class="form-control"
                           value="{{ \Carbon\Carbon::createFromDate($keuangan->periode_tahun, $keuangan->periode_bulan, 1)->translatedFormat('F Y') }}"
                           disabled>
                    <small class="text-muted">Periode tidak dapat diubah</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pemasukan</label>
                    <input type="number"
                           name="pemasukan"
                           class="form-control"
                           value="{{ old('pemasukan', $keuangan->pemasukan) }}"
                           min="0"
                           required>
                    @error('pemasukan')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <a href="{{ route('keuangan-armada.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>

    </form>

</x-adminlte-card>

@stop