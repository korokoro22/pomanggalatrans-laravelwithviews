@extends('adminlte::page')

@section('title', 'Nota Jalan')

@section('content_header')
    <h1>Daftar Nota Jalan</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- FILTER --}}
<x-adminlte-card title="Filter" theme="secondary" icon="fas fa-filter" collapsible style="text-transform: uppercase;">

    <form action="{{ route('nota-jalan.index') }}" method="GET">
        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label>Bus</label>
                    <select name="bus_id" class="form-control" style="text-transform: uppercase;">
                        <option value="">-- Semua Bus --</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                                {{ $bus->nama_bus }} ({{ $bus->plat_nomor }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Tanggal (spesifik)</label>
                    <input type="date"
                           name="tanggal"
                           class="form-control"
                           style="text-transform: uppercase;"
                           value="{{ request('tanggal') }}">
                    <small class="text-muted">Isi ini untuk cari per hari. Kosongkan untuk pakai filter bulan/tahun.</small>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Bulan</label>
                    <select name="bulan" style="text-transform: uppercase;" class="form-control" {{ request('tanggal') ? 'disabled' : '' }}>
                        <option value="">-- Semua Bulan --</option>
                        @php
                            $namaBulan = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ];
                        @endphp
                        @foreach($namaBulan as $angka => $nama)
                            <option value="{{ $angka }}" {{ request('bulan') == $angka ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Tahun</label>
                    <select name="tahun" class="form-control" {{ request('tanggal') ? 'disabled' : '' }} style="text-transform: uppercase;">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label>Supplier</label>
                    <input type="text"
                    style="text-transform: uppercase;"
                           name="supplier"
                           class="form-control"
                           placeholder="Cari nama supplier..."
                           value="{{ request('supplier') }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Nama Item</label>
                    <input type="text"
                            style="text-transform: uppercase;"
                           name="nama_item"
                           class="form-control"
                           placeholder="Cari nama item..."
                           value="{{ request('nama_item') }}">
                </div>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <div class="form-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search" ></i> TERAPKAN FILTER
                    </button>
                    <a href="{{ route('nota-jalan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>

        </div>
    </form>

</x-adminlte-card>

{{-- TABEL --}}
<x-adminlte-card theme="warning" icon="fas fa-route" title="Log Nota Jalan" style="text-transform: uppercase;">

    <div class="col-md-12 mb-3">
        <a href="{{ route('nota-jalan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Nota Jalan
        </a>
        <a href="{{ route('nota-jalan.export-pdf', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
    

    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th width="4%">No</th>
                <th width="9%">Tanggal</th>
                <th width="14%">Bus</th>
                <th width="11%">No Invoice</th>
                <th width="12%">Supplier</th>
                <th>Item</th>
                <th width="12%">Total</th>
                <th width="12%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notaJalans as $index => $notaJalan)
            <tr>
                <td class="text-center">{{ $notaJalans->firstItem() + $index }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($notaJalan->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $notaJalan->bus->nama_bus ?? '-' }} ({{ $notaJalan->bus->plat_nomor ?? '-' }})</td>
                <td>{{ $notaJalan->no_invoice }}</td>
                <td>{{ $notaJalan->supplier }}</td>
                <td>
                    @foreach($notaJalan->details as $detail)
                        - {{ $detail->nama_item }}<br>
                    @endforeach
                </td>
                <td class="text-right">Rp {{ number_format($notaJalan->total_transaksi, 0, ',', '.') }}</td>
                <td class="text-center">
                    <div class="d-flex flex-column" style="row-gap: 8px;">
                        <a href="{{ route('nota-jalan.show', $notaJalan->id) }}" class="btn btn-info btn-sm" title="Lihat">
                            Detail
                        </a>
                        <a href="{{ route('nota-jalan.edit', $notaJalan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            Edit
                        </a>
                        <form action="{{ route('nota-jalan.destroy', $notaJalan->id) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus nota jalan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada nota jalan yang cocok dengan filter</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $notaJalans->links() }}

</x-adminlte-card>

@section('js')
<script>
    // Nonaktifkan bulan/tahun secara live kalau tanggal spesifik diisi (biar user tidak bingung)
    document.addEventListener('DOMContentLoaded', function () {
        const tanggalInput = document.querySelector('input[name="tanggal"]');
        const bulanSelect  = document.querySelector('select[name="bulan"]');
        const tahunSelect  = document.querySelector('select[name="tahun"]');

        function toggleBulanTahun() {
            const isDisabled = tanggalInput.value !== '';
            bulanSelect.disabled = isDisabled;
            tahunSelect.disabled = isDisabled;
            if (isDisabled) {
                bulanSelect.value = '';
                tahunSelect.value = '';
            }
        }

        tanggalInput.addEventListener('input', toggleBulanTahun);
        toggleBulanTahun();
    });
</script>
@stop

@stop