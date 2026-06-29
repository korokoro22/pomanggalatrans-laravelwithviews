@extends('adminlte::page')

@section('title', 'Detail Keuangan Armada')

@section('content_header')
    <h1>Detail Keuangan Armada</h1>
@stop

@section('content')

<div class="mb-3">
    <a href="{{ route('keuangan-armada.export-pdf-show', $bus->id) . '?' . http_build_query(request()->query()) }}"
       class="btn btn-success btn-sm">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
</div>

{{-- PROFIL BUS --}}
<x-adminlte-card title="Profil Bus" theme="primary" icon="fas fa-bus">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <td width="40%"><strong>Nama Bus</strong></td>
                    <td>: {{ $bus->nama_bus }}</td>
                </tr>
                <tr>
                    <td><strong>Plat Nomor</strong></td>
                    <td>: {{ $bus->plat_nomor }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <td width="40%"><strong>Driver</strong></td>
                    <td>: {{ $bus->nama_driver }}</td>
                </tr>
                <tr>
                    <td><strong>Rute Trayek</strong></td>
                    <td>: {{ $bus->rute_trayek ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</x-adminlte-card>

{{-- FILTER RIWAYAT --}}
<x-adminlte-card title="Filter Riwayat Keuangan" theme="light" icon="fas fa-filter">
    <form method="GET" action="{{ route('keuangan-armada.show', $bus->id) }}">

        {{-- Pertahankan filter detail --}}
        <input type="hidden" name="bulan_detail" value="{{ $bulanDetail }}">
        <input type="hidden" name="tahun_detail" value="{{ $tahunDetail }}">

        <div class="row">
            <div class="col-md-5">
                <label>Bulan</label>
                <select name="bulan_riwayat" class="form-control">
                    <option value="">-- Semua Bulan --</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i + 1 }}" {{ $filterBulanRiwayat == $i + 1 ? 'selected' : '' }}>
                            {{ $bln }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label>Tahun</label>
                <select name="tahun_riwayat" class="form-control">
                    <option value="">-- Semua Tahun --</option>
                    @foreach(range(date('Y'), 2024) as $t)
                        <option value="{{ $t }}" {{ $filterTahunRiwayat == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <a href="{{ route('keuangan-armada.show', $bus->id) }}" class="btn btn-secondary btn-sm">
                Reset
            </a>
        </div>
    </form>
</x-adminlte-card>

{{-- RIWAYAT PER BULAN --}}
<x-adminlte-card title="Riwayat Keuangan per Bulan" theme="success" icon="fas fa-money-bill-wave">

    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Periode</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Pendapatan Bersih</th>
                <th width="100">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $row)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::createFromDate($row['tahun'], $row['bulan'], 1)->translatedFormat('F Y') }}
                </td>
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
                    @if($row['keuangan_id'])
                        <a href="{{ route('keuangan-armada.edit', $row['keuangan_id']) }}"
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    @else
                        <a href="{{ route('keuangan-armada.create') }}?bus_id={{ $bus->id }}"
                           class="btn btn-primary btn-sm"
                           title="Tambah pemasukan">
                            <i class="fas fa-plus"></i>
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada riwayat keuangan</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right"><strong>Total</strong></td>
                <td class="text-right text-success">
                    <strong>Rp {{ number_format(collect($riwayat)->sum('pemasukan'), 0, ',', '.') }}</strong>
                </td>
                <td class="text-right text-danger">
                    <strong>Rp {{ number_format(collect($riwayat)->sum('pengeluaran'), 0, ',', '.') }}</strong>
                </td>
                <td class="text-right">
                    <strong>Rp {{ number_format(collect($riwayat)->sum('bersih'), 0, ',', '.') }}</strong>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</x-adminlte-card>

{{-- FILTER DETAIL TRANSAKSI --}}
<x-adminlte-card title="Filter Detail Pengeluaran" theme="light" icon="fas fa-filter">
    <form method="GET" action="{{ route('keuangan-armada.show', $bus->id) }}">

        {{-- Pertahankan filter riwayat --}}
        <input type="hidden" name="bulan_riwayat" value="{{ $filterBulanRiwayat }}">
        <input type="hidden" name="tahun_riwayat" value="{{ $filterTahunRiwayat }}">

        <div class="row">
            <div class="col-md-5">
                <label>Bulan</label>
                <select name="bulan_detail" class="form-control">
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i + 1 }}" {{ $bulanDetail == $i + 1 ? 'selected' : '' }}>
                            {{ $bln }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label>Tahun</label>
                <select name="tahun_detail" class="form-control">
                    @foreach(range(date('Y'), 2024) as $t)
                        <option value="{{ $t }}" {{ $tahunDetail == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </div>

    </form>
</x-adminlte-card>

{{-- DETAIL TRANSAKSI KELUAR --}}
<x-adminlte-card title="Detail Pengeluaran — {{ \Carbon\Carbon::createFromDate($tahunDetail, $bulanDetail, 1)->translatedFormat('F Y') }}" theme="danger" icon="fas fa-arrow-up">

    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Item Transaksi</th>
                <th>Total</th>
                <th width="80">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksiTerbaru as $transaksi)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}
                </td>
                <td>
                    @foreach($transaksi->details as $detail)
                        <span class="badge badge-light border text-dark">{{ $detail->nama_item }}</span>
                    @endforeach
                </td>
                <td class="text-right text-danger">
                    Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    <a href="{{ route('barang-keluar.show', $transaksi->id) }}"
                       class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Tidak ada transaksi keluar di periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($transaksiTerbaru->count() > 0)
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total Pengeluaran</strong></td>
                <td class="text-right text-danger">
                    <strong>Rp {{ number_format($transaksiTerbaru->sum('total_transaksi'), 0, ',', '.') }}</strong>
                </td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="mt-3">
        <a href="{{ route('keuangan-armada.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

</x-adminlte-card>

@stop