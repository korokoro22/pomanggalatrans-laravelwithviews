@extends('adminlte::page')

@section('title', 'Edit Barang Masuk')

@section('content_header')
    <h1>Edit Barang Masuk</h1>
@stop

@section('content')

<form action="{{ route('barang-masuk.update', $barangMasuk->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- HEADER NOTA --}}
<x-adminlte-card title="Informasi Nota" theme="warning" icon="fas fa-file-invoice">

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date"
                       name="tanggal_masuk"
                       class="form-control @error('tanggal_masuk') is-invalid @enderror"
                       value="{{ old('tanggal_masuk', $barangMasuk->tanggal_masuk) }}"
                       required>
                @error('tanggal_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>No. Invoice</label>
                <input type="text"
                       name="no_invoice"
                       class="form-control @error('no_invoice') is-invalid @enderror"
                       placeholder="Masukkan nomor invoice"
                       value="{{ old('no_invoice', $barangMasuk->no_invoice) }}"
                       required>
                @error('no_invoice')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Supplier</label>
                <input type="text"
                       name="supplier"
                       class="form-control @error('supplier') is-invalid @enderror"
                       placeholder="Masukkan nama supplier"
                       value="{{ old('supplier', $barangMasuk->supplier) }}"
                       required>
                @error('supplier')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Penerima</label>
                <input type="text"
                       name="penerima"
                       class="form-control @error('penerima') is-invalid @enderror"
                       placeholder="Masukkan nama penerima"
                       value="{{ old('penerima', $barangMasuk->penerima) }}"
                       required>
                @error('penerima')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Bukti Nota</label>
                @if ($barangMasuk->bukti_nota)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $barangMasuk->bukti_nota) }}"
                             width="80"
                             style="border-radius:5px">
                        <small class="text-muted d-block mt-1">Upload baru untuk mengganti</small>
                    </div>
                @endif
                <input type="file"
                       name="bukti_nota"
                       class="form-control @error('bukti_nota') is-invalid @enderror"
                       accept="image/*">
                @error('bukti_nota')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

</x-adminlte-card>


{{-- ITEM BARANG --}}
<x-adminlte-card title="Item Barang" theme="warning" icon="fas fa-boxes">

    <small class="text-muted">Edit item barang dalam nota ini</small>

    <div class="mt-3" id="item-container">

        @foreach ($barangMasuk->details as $index => $detail)
        <div class="card card-outline card-secondary mb-3 item-row">
            <div class="card-header">
                <h6 class="card-title mb-0">Item #{{ $index + 1 }}</h6>
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text"
                                   name="items[{{ $index }}][kode_barang]"
                                   class="form-control"
                                   placeholder="Masukkan kode barang"
                                   value="{{ old('items.' . $index . '.kode_barang', $detail->barang->kode_barang ?? '') }}"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="items[{{ $index }}][kategori]" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="oli_mesin"    {{ old('items.' . $index . '.kategori', $detail->barang->kategori ?? '') == 'oli_mesin'    ? 'selected' : '' }}>Oli Mesin</option>
                                <option value="filter_solar" {{ old('items.' . $index . '.kategori', $detail->barang->kategori ?? '') == 'filter_solar' ? 'selected' : '' }}>Filter Solar</option>
                                <option value="item_bebas"   {{ old('items.' . $index . '.kategori', $detail->barang->kategori ?? '') == 'item_bebas'   ? 'selected' : '' }}>Item Bebas</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text"
                                   name="items[{{ $index }}][nama_barang]"
                                   class="form-control"
                                   placeholder="Masukkan nama barang"
                                   value="{{ old('items.' . $index . '.nama_barang', $detail->nama_barang) }}"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Foto Barang</label>
                            @if ($detail->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $detail->foto) }}"
                                         width="60"
                                         style="border-radius:5px">
                                    <small class="text-muted d-block mt-1">Upload baru untuk mengganti</small>
                                </div>
                            @endif
                            <input type="file"
                                   name="items[{{ $index }}][foto]"
                                   class="form-control"
                                   accept="image/*">
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number"
                                   name="items[{{ $index }}][qty]"
                                   class="form-control"
                                   placeholder="Contoh: 2"
                                   min="1"
                                   value="{{ old('items.' . $index . '.qty', $detail->qty) }}"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text"
                                   name="items[{{ $index }}][satuan]"
                                   class="form-control"
                                   placeholder="Dus, Box, Lusin"
                                   value="{{ old('items.' . $index . '.satuan', $detail->satuan) }}"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Total (Pcs)</label>
                            <input type="number"
                                   name="items[{{ $index }}][qty_satuan]"
                                   class="form-control"
                                   placeholder="Contoh: 24"
                                   min="1"
                                   value="{{ old('items.' . $index . '.qty_satuan', $detail->qty_satuan) }}"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Harga Jual <small class="text-muted">(per pcs)</small></label>
                            <input type="number"
                                   name="items[{{ $index }}][harga_jual]"
                                   class="form-control"
                                   placeholder="Harga jual per pcs"
                                   min="0"
                                   value="{{ old('items.' . $index . '.harga_jual', $detail->harga_jual) }}"
                                   required>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Subtotal <small class="text-muted">(isi manual)</small></label>
                            <input type="number"
                                   name="items[{{ $index }}][subtotal]"
                                   class="form-control"
                                   placeholder="Masukkan subtotal"
                                   min="0"
                                   value="{{ old('items.' . $index . '.subtotal', $detail->subtotal) }}"
                                   required>
                        </div>
                    </div>

                </div>

                <button type="button"
                        class="btn btn-danger btn-sm btn-remove-item"
                        {{ $barangMasuk->details->count() === 1 ? 'disabled' : '' }}>
                    <i class="fas fa-trash"></i> Hapus Item
                </button>

            </div>
        </div>
        @endforeach

    </div>

    <button type="button" class="btn btn-success btn-sm" id="btn-add-item">
        <i class="fas fa-plus"></i> Tambah Item
    </button>

    <hr>

    <div class="row">
        <div class="col-md-4 offset-md-8">
            <div class="form-group">
                <label><strong>Total Keseluruhan</strong></label>
                <input type="number"
                       name="total"
                       class="form-control"
                       placeholder="Masukkan total keseluruhan"
                       value="{{ $barangMasuk->details->sum('subtotal') }}"
                       min="0"
                       required>
            </div>
        </div>
    </div>

</x-adminlte-card>

<div class="mb-3">
    <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>

    <button type="submit" class="btn btn-warning">
        <i class="fas fa-save"></i>
        Update
    </button>
</div>

</form>

@section('js')
<script>
    let itemIndex = {{ $barangMasuk->details->count() }};

    function buildItemRow(index) {
        return `
        <div class="card card-outline card-secondary mb-3 item-row">
            <div class="card-header">
                <h6 class="card-title mb-0">Item #${index + 1}</h6>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text" name="items[${index}][kode_barang]" class="form-control" placeholder="Masukkan kode barang" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="items[${index}][kategori]" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="oli_mesin">Oli Mesin</option>
                                <option value="filter_solar">Filter Solar</option>
                                <option value="item_bebas">Item Bebas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" name="items[${index}][nama_barang]" class="form-control" placeholder="Masukkan nama barang" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Foto Barang</label>
                            <input type="file" name="items[${index}][foto]" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number" name="items[${index}][qty]" class="form-control" placeholder="Contoh: 2" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text" name="items[${index}][satuan]" class="form-control" placeholder="Dus, Box, Lusin" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Total (Pcs)</label>
                            <input type="number" name="items[${index}][qty_satuan]" class="form-control" placeholder="Contoh: 24" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Harga Jual <small class="text-muted">(per pcs)</small></label>
                            <input type="number" name="items[${index}][harga_jual]" class="form-control" placeholder="Harga jual per pcs" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Subtotal <small class="text-muted">(isi manual)</small></label>
                            <input type="number" name="items[${index}][subtotal]" class="form-control" placeholder="Masukkan subtotal" min="0" required>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-sm btn-remove-item">
                    <i class="fas fa-trash"></i> Hapus Item
                </button>

            </div>
        </div>
        `;
    }

    document.getElementById('btn-add-item').addEventListener('click', function () {
        const container = document.getElementById('item-container');
        container.insertAdjacentHTML('beforeend', buildItemRow(itemIndex));
        itemIndex++;
        updateRemoveButtons();
    });

    document.getElementById('item-container').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-item')) {
            e.target.closest('.item-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.btn-remove-item');
            btn.disabled = rows.length === 1;
        });
    }
</script>
@stop

@stop