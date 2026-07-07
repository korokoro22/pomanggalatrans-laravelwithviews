@extends('adminlte::page')

@section('title', 'Detail Bus')

@section('content_header')
    <h1>Detail Bus</h1>
@stop

@section('content')

<div class="mb-3 mt-3">
    <a href="{{ route('data-bus.export-pdf-show', $bus->id) }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
</div>

{{-- INFORMASI BUS --}}
<x-adminlte-card title="Informasi Bus" theme="info" icon="fas fa-bus">

    <table class="table table-borderless">
        <tr>
            <td width="30%"><strong>Nama Bus</strong></td>
            <td>: {{ $bus->nama_bus }}</td>
        </tr>
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

{{-- RIWAYAT TRANSAKSI KELUAR --}}
<x-adminlte-card title="Riwayat Pengeluaran" theme="danger" icon="fas fa-arrow-up">

    <table class="table table-bordered table-striped">

        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Item Transaksi</th>
                <th>Total</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bus->transaksiKeluar as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i') }}</td>
                <td>
                    @forelse ($transaksi->details as $detail)
                        <span class="badge badge-light border text-dark">
                            {{ $detail->nama_item }}
                        </span>
                    @empty
                        <span class="text-muted">-</span>
                    @endforelse
                </td>
                <td>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
                <td class="text-center">
                    <a href="{{ route('barang-keluar.show', $transaksi->id) }}"
                       class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada riwayat pengeluaran</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total Pengeluaran</strong></td>
                <td>
                    <strong>
                        Rp {{ number_format($bus->transaksiKeluar->sum('total_transaksi'), 0, ',', '.') }}
                    </strong>
                </td>
                <td></td>
            </tr>
        </tfoot>

    </table>

</x-adminlte-card>

<a href="{{ route('data-bus.index') }}" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>

@stop