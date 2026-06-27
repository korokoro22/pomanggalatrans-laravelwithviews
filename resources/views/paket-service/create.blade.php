@extends('adminlte::page')

@section('title', 'Tambah Paket Service')

@section('content_header')
<h1>Tambah Paket Service</h1>
@stop

@section('content')

<x-adminlte-card title="Form Paket Service" theme="primary" icon="fas fa-plus-circle">

    <form action="{{ route('paket-service.store') }}" method="POST">
        @csrf

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Bus</label>
                    <select name="bus_id"
                            class="form-control @error('bus_id') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih Bus --</option>
                        @foreach ($buses as $bus)
                            <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                {{ $bus->nama_bus }} - {{ $bus->plat_nomor }}
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
                    <label>Nama Paket</label>
                    <input type="text"
                           name="nama_paket"
                           class="form-control @error('nama_paket') is-invalid @enderror"
                           placeholder="Masukkan nama paket"
                           value="{{ old('nama_paket') }}"
                           required>
                    @error('nama_paket')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number"
                           name="harga"
                           class="form-control @error('harga') is-invalid @enderror"
                           placeholder="Masukkan harga paket"
                           value="{{ old('harga') }}"
                           required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}">
                                {{ $barang->nama_barang }} ({{ $barang->kategori == 'oli_mesin' ? 'Oli Mesin' : 'Filter Solar' }}) - Stok: {{ $barang->stok_saat_ini }}
                            </option>
                        @endforeach
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
    const barangOptions = `
        @foreach ($barangs as $barang)
            <option value="{{ $barang->id }}">{{ $barang->nama_barang }} ({{ $barang->kategori == 'oli_mesin' ? 'Oli Mesin' : 'Filter Solar' }}) - Stok: {{ $barang->stok_saat_ini }}</option>
        @endforeach
    `;

    document.getElementById('btn-add-item').addEventListener('click', function () {
        const container = document.getElementById('item-container');
        const row = document.createElement('div');
        row.classList.add('row', 'item-row', 'mb-2');
        row.innerHTML = `
            <div class="col-md-7">
                <select name="items[${itemIndex}][barang_id]" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    ${barangOptions}
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