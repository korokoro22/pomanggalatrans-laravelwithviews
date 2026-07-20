@extends('adminlte::page')

@section('title', 'Edit Nota Jalan')

@section('content_header')
    <h1 style="text-transform: uppercase;">Edit Nota Jalan</h1>
@stop

@section('content')

<form action="{{ route('nota-jalan.update', $notaJalan->id) }}" method="POST" enctype="multipart/form-data" style="text-transform: uppercase;">
@csrf
@method('PUT')

{{-- HEADER NOTA --}}
<x-adminlte-card title="Informasi Nota Jalan" theme="warning" icon="fas fa-route">

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Bus</label>
                <select name="bus_id"
                        class="form-control @error('bus_id') is-invalid @enderror"
                        style="text-transform: uppercase;"
                        required>
                    <option value="">-- Pilih Bus --</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->id }}" {{ old('bus_id', $notaJalan->bus_id) == $bus->id ? 'selected' : '' }}>
                            {{ $bus->nama_bus }} ({{ $bus->plat_nomor }})
                        </option>
                    @endforeach
                </select>
                @error('bus_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal</label>
                <input type="datetime-local"
                       name="tanggal"
                       style="text-transform: uppercase;"
                       class="form-control @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal', \Carbon\Carbon::parse($notaJalan->tanggal)->format('Y-m-d\TH:i')) }}"
                       required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>No. Invoice</label>
                <input type="text"
                       name="no_invoice"
                       style="text-transform: uppercase;"
                       class="form-control @error('no_invoice') is-invalid @enderror"
                       value="{{ old('no_invoice', $notaJalan->no_invoice) }}"
                       required>
                @error('no_invoice')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Supplier</label>
                <input type="text"
                       name="supplier"
                       style="text-transform: uppercase;"
                       class="form-control @error('supplier') is-invalid @enderror"
                       value="{{ old('supplier', $notaJalan->supplier) }}"
                       required>
                @error('supplier')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Bukti Nota</label>
                @if($notaJalan->bukti_nota)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $notaJalan->bukti_nota) }}"
                             style="width:100px; border-radius:5px;">
                        <p class="text-muted mb-0"><small>Kosongkan jika tidak ingin mengganti bukti nota</small></p>
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
<x-adminlte-card title="Item Dibeli" theme="warning" icon="fas fa-shopping-basket" style="text-transform: uppercase;">

    <small class="text-muted">Tambahkan item yang dibeli di perjalanan</small>

    <div class="mt-3" id="item-container">

        @foreach($notaJalan->details as $i => $detail)
        <div class="card card-outline card-secondary mb-3 item-row">
            <div class="card-header">
                <h6 class="card-title mb-0">Item #{{ $i + 1 }}</h6>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Item</label>
                            <input type="text"
                                   name="items[{{ $i }}][nama_item]"
                                   style="text-transform: uppercase;"
                                   class="form-control"
                                   value="{{ $detail->nama_item }}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number"
                                   name="items[{{ $i }}][qty]"
                                   style="text-transform: uppercase;"
                                   class="form-control"
                                   value="{{ $detail->qty }}"
                                   min="1"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text"
                                   name="items[{{ $i }}][satuan]"
                                   style="text-transform: uppercase;"
                                   class="form-control"
                                   value="{{ $detail->satuan }}"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Harga Satuan</label>
                            <input type="number"
                                   name="items[{{ $i }}][harga_satuan]"
                                   style="text-transform: uppercase;"
                                   class="form-control"
                                   value="{{ $detail->harga_satuan }}"
                                   min="0"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Subtotal <small class="text-muted">(otomatis, bisa diedit)</small></label>
                            <input type="number"
                                   name="items[{{ $i }}][subtotal]"
                                   style="text-transform: uppercase;"
                                   class="form-control"
                                   value="{{ $detail->subtotal }}"
                                   min="0"
                                   required>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-sm btn-remove-item" {{ $notaJalan->details->count() === 1 ? 'disabled' : '' }}>
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
                <input  type="number"
                        name="total"
                        class="form-control"
                        value="{{ old('total', $notaJalan->total_transaksi) }}"
                        min="0"
                        required>
            </div>
        </div>
    </div>

</x-adminlte-card>

<div class="mb-3">
    <a href="{{ route('nota-jalan.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i>
        Update
    </button>
</div>

</form>

@section('js')
<script>
    let itemIndex = {{ $notaJalan->details->count() }};

    function buildItemRow(index) {
        return `
        <div class="card card-outline card-secondary mb-3 item-row">
            <div class="card-header">
                <h6 class="card-title mb-0">Item #${index + 1}</h6>
            </div>
            <div class="card-body" style="text-transform: uppercase;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Item</label>
                            <input type="text" name="items[${index}][nama_item]" class="form-control" placeholder="Masukkan nama item" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number" name="items[${index}][qty]" class="form-control" placeholder="Contoh: 2" style="text-transform: uppercase;" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text" name="items[${index}][satuan]" class="form-control" placeholder="Pcs, Botol, Liter" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Harga Satuan</label>
                            <input type="number" name="items[${index}][harga_satuan]" class="form-control" placeholder="Harga per satuan" min="0" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Subtotal <small class="text-muted">(otomatis, bisa diedit)</small></label>
                            <input type="number" name="items[${index}][subtotal]" class="form-control" placeholder="Subtotal" min="0" style="text-transform: uppercase;" required>
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

    function hitungSubtotalItem(row) {
        const qtyInput   = row.querySelector('input[name$="[qty]"]');
        const hargaInput = row.querySelector('input[name$="[harga_satuan]"]');
        const subtotalInput = row.querySelector('input[name$="[subtotal]"]');

        const qty   = parseFloat(qtyInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;

        subtotalInput.value = qty * harga;
    }

    function hitungTotalKeseluruhan() {
        let total = 0;
        document.querySelectorAll('.item-row input[name$="[subtotal]"]').forEach(function (input) {
            total += parseFloat(input.value) || 0;
        });
        document.querySelector('input[name="total"]').value = total;
    }

    document.getElementById('item-container').addEventListener('input', function (e) {
        if (e.target.matches('input[name$="[qty]"]') || e.target.matches('input[name$="[harga_satuan]"]')) {
            const row = e.target.closest('.item-row');
            hitungSubtotalItem(row);
        }

        if (e.target.matches('input[name$="[subtotal]"]') ||
            e.target.matches('input[name$="[qty]"]') ||
            e.target.matches('input[name$="[harga_satuan]"]')) {
            hitungTotalKeseluruhan();
        }
    });
</script>
@stop

@stop