@extends('adminlte::page')

@section('title', 'Nota Masuk')

@section('content_header')
    <h1 style="text-transform: uppercase;">Nota Masuk</h1>
@stop

@section('content')

{{-- ACTION BUTTON --}}
<div class="row mb-3" style="text-transform: uppercase;">
    <div class="col-md-12">

        <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Data
        </a>

        <a href="{{ route('barang-masuk.export-pdf', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>

    </div>
</div>


{{-- FILTER SECTION --}}
<x-adminlte-card title="Filter Nota Masuk" theme="light" icon="fas fa-filter" style="text-transform: uppercase;">

    <form action="{{ route('barang-masuk.index') }}" method="GET">

        <div class="row">

            <div class="col-md-3" style="text-transform: uppercase;">
                <label>Nama Barang</label>
                <input type="text"
                        style="text-transform: uppercase;"
                       name="nama_barang"
                       class="form-control"
                       placeholder="Cari nama barang..."
                       value="{{ request('nama_barang') }}">
            </div>

            <div class="col-md-3">
                <label>No. Invoice</label>
                <input type="text"
                       name="no_invoice"
                       class="form-control"
                       placeholder="Cari no invoice..."
                       style="text-transform: uppercase;"
                       value="{{ request('no_invoice') }}">
            </div>

            <div class="col-md-3">
                <label>Supplier</label>
                <input type="text"
                       name="supplier"
                       class="form-control"
                       placeholder="Cari nama supplier..."
                       style="text-transform: uppercase;"
                       value="{{ request('supplier') }}">
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
                <label>Bulan Masuk</label>
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

            <div class="col-md-3">
                <label>Tahun Masuk</label>
                <select name="tahun" class="form-control" style="text-transform: uppercase;">
                    <option value="">-- Pilih Tahun --</option>
                    @for ($i = now()->year; $i >= now()->year - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <label>Kategori Nota</label>
                <select name="kategori_nota" class="form-control" style="text-transform: uppercase;">
                    <option value="">-- Semua Nota --</option>
                    <option value="nota_bengkel" {{ request('kategori_nota') == 'nota_bengkel' ? 'selected' : '' }}>Nota Bengkel</option>
                    <option value="nota_jalan"   {{ request('kategori_nota') == 'nota_jalan'   ? 'selected' : '' }}>Nota Jalan</option>
                </select>
            </div>

            {{-- REVISI: FILTER JENIS MASUK --}}
            <div class="col-md-3">
                <label>Jenis Masuk</label>
                <select name="jenis_masuk" class="form-control" style="text-transform: uppercase;">
                    <option value="">-- Semua Jenis --</option>
                    <option value="nota" {{ request('jenis_masuk') == 'nota' ? 'selected' : '' }}>Nota</option>
                    <option value="stok_awal" {{ request('jenis_masuk') == 'stok_awal' ? 'selected' : '' }}>Stok Lama</option>
                    <option value="barang_bekas" {{ request('jenis_masuk') == 'barang_bekas' ? 'selected' : '' }}>Barang Bekas</option>
                </select>
            </div>

            {{-- ============================================ --}}
            {{-- REVISI: JENIS PEMBELIAN --}}
            {{-- ============================================ --}}
            <div class="col-md-3">
                <label>Jenis Pembelian</label>
                <select name="jenis_pembelian" class="form-control" style="text-transform: uppercase;">
                    <option value="">-- Semua Jenis --</option>
                    <option value="pembelian_toko" {{ request('jenis_pembelian') == 'pembelian_toko' ? 'selected' : '' }}>Pembelian Toko</option>
                    <option value="pembelian_langsung" {{ request('jenis_pembelian') == 'pembelian_langsung' ? 'selected' : '' }}>Pembelian Langsung</option>
                </select>
            </div>

        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> FILTER
            </button>
            <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </form>

</x-adminlte-card>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="text-transform: uppercase;">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
{{-- <x-adminlte-card>hai</x-adminlte-card> --}}

{{-- TABLE SECTION --}}
<x-adminlte-card title="Log Nota Masuk" theme="success" icon="fas fa-arrow-down" style="text-transform: uppercase;">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th width="4%">No</th>
                    <th width="10%">Tanggal Masuk</th>
                    <th width="9%">Jenis Masuk</th>
                    <th width="9%">No Invoice</th>
                    <th width="9%">Kategori</th>
                    <th width="10%">Supplier</th>
                    {{-- REVISI: JENIS PEMBELIAN --}}
                    <th width="10%">Jenis Pembelian</th>
                    <th width="7%">Bukti Nota</th>
                    <th width="9%">Penerima</th>
                    <th width="18%">Item Barang</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($barangMasuks as $index => $barangMasuk)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('d-m-Y H:i') }}</td>
                    <td class="text-center">
                        @if(($barangMasuk->jenis_masuk ?? 'nota') === 'stok_awal')
                            <span class="badge badge-warning">Stok Lama</span>
                        @elseif(($barangMasuk->jenis_masuk ?? 'nota') === 'barang_bekas')
                            <span class="badge badge-secondary"> Barang Bekas</span>
                        @else
                            <span class="badge badge-primary">Nota</span>
                        @endif
                    </td>
                    <td>{{ $barangMasuk->no_invoice ?? '-' }}</td>
                    <td class="text-center">
                        @if($barangMasuk->kategori_nota === 'nota_jalan')
                            <span class="badge badge-warning">Nota Jalan</span>
                        @else
                            <span class="badge badge-primary">Nota Bengkel</span>
                        @endif
                    </td>
                    <td>{{ $barangMasuk->supplier ?? '-' }}</td>
                    {{-- REVISI: JENIS PEMBELIAN --}}
                    <td class="text-center">
                        @if($barangMasuk->jenis_pembelian === 'pembelian_langsung')
                            <span class="badge badge-success">Pembelian Langsung</span>
                        @elseif($barangMasuk->jenis_pembelian === 'pembelian_toko')
                            <span class="badge badge-primary">Pembelian Toko</span>
                        @else
                            <span class="badge badge-secondary">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($barangMasuk->bukti_nota)
                            <img src="{{ asset('storage/' . $barangMasuk->bukti_nota) }}"
                                width="60"
                                style="border-radius:5px; cursor:pointer"
                                data-toggle="modal"
                                data-target="#modalNota{{ $barangMasuk->id }}">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $barangMasuk->penerima ?? '-' }}</td>
                    <td style="max-width: 220px; white-space: normal; word-break: break-word;">
                        @forelse ($barangMasuk->details as $detail)
                            <span class="badge badge-info" style="white-space: normal;">{{ $detail->nama_barang }}</span>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse
                    </td>
                    <td class="text-center">
                        <div class="d-flex flex-column" style="row-gap: 8px;">
                            <a href="{{ route('barang-masuk.show', $barangMasuk->id) }}"
                            class="btn btn-info btn-sm">
                                DETAIL
                            </a>
                            <a href="{{ route('barang-masuk.edit', $barangMasuk->id) }}"
                            class="btn btn-warning btn-sm">
                                EDIT
                            </a>
                            <form action="{{ route('barang-masuk.destroy', $barangMasuk->id) }}"
                                method="POST"
                                
                                onsubmit="return confirm('Yakin ingin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    HAPUS
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Modal Bukti Nota --}}
                @if ($barangMasuk->bukti_nota)
                <div class="modal fade" id="modalNota{{ $barangMasuk->id }}" tabindex="-1">
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

                @empty
                <tr>
                    <td colspan="11" class="text-center">Belum ada data barang masuk</td>
                </tr>
                @endforelse

            </tbody>

        </table>
    </div>
    

</x-adminlte-card>

@stop