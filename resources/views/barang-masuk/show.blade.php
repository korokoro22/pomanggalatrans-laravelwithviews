@extends('adminlte::page')

@section('title', 'Detail Barang Masuk')

@section('content_header')
    <h1>Detail Barang Masuk</h1>
@stop

@section('content')
<a href="{{ route('barang-masuk.export-pdf-show', $barangMasuk->id) }}" class="btn btn-success">
    <i class="fas fa-file-pdf"></i> Export PDF
</a>
{{-- INFORMASI NOTA --}}
<x-adminlte-card title="Informasi Nota" theme="success" icon="fas fa-file-invoice">
    <div class="row">

        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <td width="40%"><strong>No. Invoice</strong></td>
                    <td>: INV-001</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Masuk</strong></td>
                    <td>: 23-06-2026</td>
                </tr>
                <tr>
                    <td><strong>Supplier</strong></td>
                    <td>: Toko ABC</td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <td width="40%"><strong>Penerima</strong></td>
                    <td>: Andi</td>
                </tr>
                <tr>
                    <td><strong>Bukti Nota</strong></td>
                    <td>
                        :
                        <img src="https://via.placeholder.com/80"
                             style="border-radius:5px; cursor:pointer"
                             data-toggle="modal"
                             data-target="#modalNota">
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

            <tr>
                <td>1</td>
                <td>
                    <img src="https://via.placeholder.com/60"
                         style="border-radius:5px">
                </td>
                <td>
                    <span class="badge badge-warning">Oli Mesin</span>
                </td>
                <td>Oli Mesin Shell</td>
                <td>2</td>
                <td>Dus</td>
                <td>24 Pcs</td>
                <td>Rp 50.000</td>
                <td>Rp 100.000</td>
            </tr>

            <tr>
                <td>2</td>
                <td>
                    <img src="https://via.placeholder.com/60"
                         style="border-radius:5px">
                </td>
                <td>
                    <span class="badge badge-info">Filter Solar</span>
                </td>
                <td>Filter Solar Sakura</td>
                <td>1</td>
                <td>Box</td>
                <td>12 Pcs</td>
                <td>Rp 30.000</td>
                <td>Rp 30.000</td>
            </tr>

            <tr>
                <td>3</td>
                <td>
                    <img src="https://via.placeholder.com/60"
                         style="border-radius:5px">
                </td>
                <td>
                    <span class="badge badge-secondary">Item Bebas</span>
                </td>
                <td>Baut Roda</td>
                <td>3</td>
                <td>Lusin</td>
                <td>36 Pcs</td>
                <td>Rp 5.000</td>
                <td>Rp 15.000</td>
            </tr>

        </tbody>

        <tfoot>
            <tr>
                <td colspan="8" class="text-right"><strong>Total Keseluruhan</strong></td>
                <td><strong>Rp 145.000</strong></td>
            </tr>
        </tfoot>

    </table>

    <div class="mt-3">
        <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

</x-adminlte-card>


{{-- MODAL BUKTI NOTA --}}
<div class="modal fade" id="modalNota" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Nota</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="https://via.placeholder.com/400"
                     class="img-fluid"
                     style="border-radius:5px">
            </div>
        </div>
    </div>
</div>

@stop