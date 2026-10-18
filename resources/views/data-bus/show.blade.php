@extends('adminlte::page')

@section('title', 'Detail Bus')

@section('content_header')
    <h1 style="text-transform: uppercase;">Detail Bus</h1>
@stop

@section('content')

<div class="mb-3 mt-3">
    <a href="{{ route('data-bus.export-pdf-show', $bus->id) }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-pdf"></i> EXPORT PDF
    </a>
</div>

{{-- INFORMASI BUS --}}
<x-adminlte-card title="Informasi Bus" theme="info" icon="fas fa-bus" style="text-transform: uppercase;">

    <table class="table table-borderless">
        <!--<tr>-->
        <!--    <td width="30%"><strong>Nama Bus</strong></td>-->
        <!--    <td>: {{ $bus->nama_bus }}</td>-->
        <!--</tr>-->
        <tr>
            <td><strong>Plat Nomor</strong></td>
            <td>: {{ $bus->plat_nomor }}</td>
        </tr>
        <tr>
            <td><strong>Rute / Trayek</strong></td>
            <td>: {{ $bus->rute_trayek ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Driver</strong></td>
            <td>: {{ $bus->nama_driver }}</td>
        </tr>
    </table>

</x-adminlte-card>

{{-- FILTER RIWAYAT PENGELUARAN --}}
<x-adminlte-card title="Filter Riwayat Pengeluaran" theme="light" icon="fas fa-filter" style="text-transform: uppercase;">

    <form action="{{ route('data-bus.show', $bus->id) }}" method="GET">

        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label>Nama Item</label>
                    <input type="text"
                           name="nama_item"
                           class="form-control"
                           placeholder="Cari nama item..."
                           style="text-transform: uppercase;"
                           value="{{ request('nama_item') }}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Kategori Nota</label>
                    <select name="kategori_nota" class="form-control" style="text-transform: uppercase;">
                        <option value="">-- Semua Nota --</option>
                        <option value="normal" {{ request('kategori_nota') == 'normal' ? 'selected' : '' }}>Nota Bengkel</option>
                        <option value="nota_jalan" {{ request('kategori_nota') == 'nota_jalan' ? 'selected' : '' }}>Nota Jalan</option>
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
                    <select name="bulan" class="form-control" style="text-transform: uppercase;">
                        <option value="">-- Pilih Bulan --</option>
                        <option value="1"  {{ request('bulan') == '1'  ? 'selected' : '' }}>Januari</option>
                        <option value="2"  {{ request('bulan') == '2'  ? 'selected' : '' }}>Februari</option>
                        <option value="3"  {{ request('bulan') == '3'  ? 'selected' : '' }}>Maret</option>
                        <option value="4"  {{ request('bulan') == '4'  ? 'selected' : '' }}>April</option>
                        <option value="5"  {{ request('bulan') == '5'  ? 'selected' : '' }}>Mei</option>
                        <option value="6"  {{ request('bulan') == '6'  ? 'selected' : '' }}>Juni</option>
                        <option value="7"  {{ request('bulan') == '7'  ? 'selected' : '' }}>Juli</option>
                        <option value="8"  {{ request('bulan') == '8'  ? 'selected' : '' }}>Agustus</option>
                        <option value="9"  {{ request('bulan') == '9'  ? 'selected' : '' }}>September</option>
                        <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Tahun</label>
                    <select name="tahun" class="form-control" style="text-transform: uppercase;">
                        <option value="">-- Pilih Tahun --</option>
                        @for ($i = now()->year; $i >= now()->year - 5; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> FILTER
            </button>
            <a href="{{ route('data-bus.show', $bus->id) }}" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </form>

</x-adminlte-card>

{{-- RIWAYAT TRANSAKSI KELUAR --}}
<x-adminlte-card title="Riwayat Pengeluaran" theme="danger" icon="fas fa-arrow-up" style="text-transform: uppercase;">

    <table class="table table-bordered table-striped">

        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Kategori Nota</th>
                <th>Item Transaksi</th>
                <th>Total</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transaksiKeluar as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $transaksiKeluar->firstItem() + $index }}</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i') }}</td>
                <td class="text-center">
                    <span class="badge badge-info">
                        {{ $transaksi->kategori === 'normal' ? 'Nota Bengkel' : str_replace('_', ' ', $transaksi->kategori) }}
                    </span>
                </td>
                <td>
                    @forelse ($transaksi->details as $detail)
                        <span class="badge badge-light border text-dark">
                            {{ $detail->nama_item }}
                        </span>
                    @empty
                        <span class="text-muted">-</span>
                    @endforelse
                </td>
                <td class="text-right">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
                <td class="text-center">
                    <a href="{{ route('barang-keluar.show', $transaksi->id) }}"
                       class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada riwayat pengeluaran pada periode/filter ini</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Total Pengeluaran (sesuai filter)</strong></td>
                <td class="text-right">
                    <strong class="text-danger">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </strong>
                </td>
                <td></td>
            </tr>
        </tfoot>

    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $transaksiKeluar->links() }}
    </div>

</x-adminlte-card>

<a href="{{ route('data-bus.index') }}" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>

@stop