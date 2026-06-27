@extends('adminlte::page')

@section('title', 'Detail Barang Masuk')

@section('content_header')
    <h1>Detail Barang Masuk</h1>
@stop

@section('content')

<div class="mb-3">
    <a href="{{ route('barang-masuk.export-pdf-show', $barangMasuk->id) }}" class="btn btn-success">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
    <a href="{{ route('barang-masuk.edit', $barangMasuk->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>
</div>

{{-- INFORMASI NOTA --}}
<x-adminlte-card title="Informasi Nota" theme="success" icon="fas fa-file-invoice">
    <div class="row">

        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <td width="40%"><strong>No. Invoice</strong></td>
                    <td>: {{ $barangMasuk->no_invoice }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Masuk</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Supplier</strong></td>
                    <td>: {{ $barangMasuk->supplier }}</td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <td width="40%"><strong>Penerima</strong></td>
                    <td>: {{ $barangMasuk->penerima }}</td>
                </tr>
                <tr>
                    <td><strong>Bukti Nota</strong></td>
                    <td>
                        @if($barangMasuk->bukti_nota)
                            :
                            <img src="{{ asset('storage/' . $barangMasuk->bukti_nota) }}"
                                 style="max-height:80px; border-radius:5px; cursor:pointer"
                                 data-toggle="modal"
                                 data-target="#modalNota">
                        @else
                            : <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

    </div>
</x-adminlte-card>


{{-- DETAIL ITEM --}}
<x-adminlte-card title="Detail Item Barang" theme="success" icon="fas fa-boxes">

    <table class="table table-bordered table-striped">

        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Foto</th>
                <th>Kategori</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Total (Pcs)</th>
                <th>Harga Jual</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @forelse($barangMasuk->details as $detail)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    @if($detail->foto)
                        <img src="{{ asset('storage/' . $detail->foto) }}"
                             style="width:60px; height:60px; object-fit:cover; border-radius:5px">
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-center">
                    @php
                        $badge = match($detail->kategori) {
                            'oli_mesin'    => 'warning',
                            'filter_solar' => 'info',
                            default        => 'secondary',
                        };
                        $label = match($detail->kategori) {
                            'oli_mesin'    => 'Oli Mesin',
                            'filter_solar' => 'Filter Solar',
                            default        => 'Item Bebas',
                        };
                    @endphp
                    <span class="badge badge-{{ $badge }}">{{ $label }}</span>
                </td>
                <td>{{ $detail->nama_barang }}</td>
                <td class="text-center">{{ $detail->qty }}</td>
                <td class="text-center">{{ $detail->satuan }}</td>
                <td class="text-center">{{ $detail->qty_satuan }} Pcs</td>
                <td class="text-right">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted">Tidak ada item barang</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="8" class="text-right"><strong>Total Keseluruhan</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format($barangMasuk->details->sum('subtotal'), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>

    </table>

    <div class="mt-3">
        <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

</x-adminlte-card>


{{-- MODAL BUKTI NOTA --}}
@if($barangMasuk->bukti_nota)
<div class="modal fade" id="modalNota" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Nota — {{ $barangMasuk->no_invoice }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $barangMasuk->bukti_nota) }}"
                     class="img-fluid"
                     style="border-radius:5px">
            </div>
        </div>
    </div>
</div>
@endif

@stop