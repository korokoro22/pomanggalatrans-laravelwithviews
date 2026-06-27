@extends('adminlte::page')

@section('title', 'Tambah Paket Service')

@section('content_header')
<h1>Tambah Paket Service</h1>
@stop

@section('content')

<x-adminlte-card title="Form Paket Service" theme="primary" icon="fas fa-plus-circle">

    {{-- <form action="{{ route('paket-service.store') }}" method="POST"> --}}
        <form action="" method="POST">
        @csrf

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Bus</label>
                    <select name="bus_id" class="form-control" required>
                        <option value="">-- Pilih Bus --</option>
                        <option value="1">Bus Scania A - AA 1234 BB</option>
                        <option value="2">Bus Mercedez B - BB 5678 CC</option>
                        <option value="3">Bus Hino C - CC 9012 DD</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Paket</label>
                    <input type="text"
                           name="nama_paket"
                           class="form-control"
                           placeholder="Masukkan nama paket"
                           required>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number"
                           name="harga"
                           class="form-control"
                           placeholder="Masukkan harga paket"
                           required>
                </div>
            </div>

        </div>

        <hr>

        <h5><i class="fas fa-boxes"></i> Item Barang dalam Paket</h5>
        <small class="text-muted">Barang yang stoknya akan berkurang otomatis ketika paket ini dipilih</small>

        <div class="mt-3" id="item-container">

            <div class="row item-row mb-2">
                <div class="col-md-7">
                    <select name="items[0][barang_id]" class="form-control" required>
                        <option value="">-- Pilih Barang --</option>
                        <option value="1">Oli Mesin Shell (oli_mesin) - Stok: 24</option>
                        <option value="2">Oli Mesin Pertamina (oli_mesin) - Stok: 48</option>
                        <option value="3">Filter Solar Sakura (filter_solar) - Stok: 12</option>
                        <option value="4">Filter Solar Mann (filter_solar) - Stok: 6</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number"
                           name="items[0][qty]"
                           class="form-control"
                           placeholder="Qty"
                           min="1"
                           required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-item" disabled>
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

        </div>

        <button type="button" class="btn btn-success btn-sm mt-2" id="btn-add-item">
            <i class="fas fa-plus"></i>
            Tambah Item
        </button>

        <hr>

        <a href="{{ route('paket-service.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            Simpan
        </button>

    </form>

</x-adminlte-card>

@section('js')
<script>
    let itemIndex = 1;

    document.getElementById('btn-add-item').addEventListener('click', function () {
        const container = document.getElementById('item-container');
        const row = document.createElement('div');
        row.classList.add('row', 'item-row', 'mb-2');
        row.innerHTML = `
            <div class="col-md-7">
                <select name="items[${itemIndex}][barang_id]" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    <option value="1">Oli Mesin Shell (oli_mesin) - Stok: 24</option>
                    <option value="2">Oli Mesin Pertamina (oli_mesin) - Stok: 48</option>
                    <option value="3">Filter Solar Sakura (filter_solar) - Stok: 12</option>
                    <option value="4">Filter Solar Mann (filter_solar) - Stok: 6</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[${itemIndex}][qty]" class="form-control" placeholder="Qty" min="1" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
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