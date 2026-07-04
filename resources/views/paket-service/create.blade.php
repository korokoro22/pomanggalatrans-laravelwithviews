@extends('adminlte::page')

@section('title', 'Tambah Paket Service')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    .select2-container { width: 100% !important; }
    .select2-barang-option { 
        display: flex; 
        align-items: center; 
        gap: 12px; 
        padding: 6px 4px; 
    }
    .select2-barang-foto { 
        width: 60px; 
        height: 60px; 
        object-fit: cover; 
        border-radius: 5px; 
        flex-shrink: 0; 
    }
    .select2-barang-foto-placeholder { 
        width: 60px; 
        height: 60px; 
        background: #f0f0f0; 
        border-radius: 5px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        flex-shrink: 0; 
        color: #aaa; 
        font-size: 20px;
    }
    .select2-barang-info { flex: 1; }
    .select2-barang-info .nama { 
        font-weight: bold; 
        font-size: 13px; 
        margin-bottom: 2px;
    }
    .select2-barang-info .baris-dua {
        display: flex;
        gap: 12px;
        font-size: 11px;
        color: #555;
        margin-bottom: 2px;
    }
    .select2-barang-info .baris-tiga {
        display: flex;
        gap: 12px;
        font-size: 11px;
    }
    .stok-ada { color: #28a745; font-weight: bold; }
    .stok-habis { color: #dc3545; font-weight: bold; }
    .select2-results__option { padding: 4px 6px; }
    .select2-selection--single {
        height: 38px !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-selection__rendered {
        line-height: 38px !important;
    }
    .select2-selection__arrow {
        height: 38px !important;
    }
</style>
@stop

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
                    <select name="items[0][barang_id]" class="form-control select-barang" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang['id'] }}">
                                {{ $barang['nama_barang'] }} ({{ $barang['kode_barang'] }})
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const barangs = @json($barangs);
    let itemIndex = 1;

    function initSelect2Barang(row) {
    const select = $(row).find('.select-barang');

    select.select2({
        placeholder: '-- Pilih Barang --',
        allowClear: true,
        width: '100%',
        templateResult: function (option) {
            if (!option.id || option.id === '') return option.text;

            const b = barangs[option.id] || barangs[parseInt(option.id)];
            if (!b) return option.text;

            const foto = b.foto
                ? `<img src="${b.foto}" class="select2-barang-foto">`
                : `<div class="select2-barang-foto-placeholder"><i class="fas fa-image"></i></div>`;

            const stokClass = b.stok_saat_ini > 0 ? 'stok-ada' : 'stok-habis';
            const stokText  = b.stok_saat_ini > 0
                ? `Stok: ${b.stok_saat_ini} ${b.satuan}`
                : 'Stok Habis';

            const harga = Number(b.harga_jual).toLocaleString('id-ID');

            let gudangText = b.gudang ? b.gudang.replace(/_/g, ' ') : '-';
            gudangText = gudangText.replace(/\b\w/g, l => l.toUpperCase());

            return $(`
                <div class="select2-barang-option">
                    ${foto}
                    <div class="select2-barang-info">
                        <div class="nama">${b.nama_barang} (${b.kategori_label})</div>
                        <div class="baris-dua">
                            <span><i class="fas fa-barcode"></i> ${b.kode_barang}</span>
                            <span class="${stokClass}"><i class="fas fa-boxes"></i> ${stokText}</span>
                            <span><i class="fas fa-warehouse"></i> ${gudangText}</span>
                        </div>
                        <div class="baris-tiga">
                            <span><i class="fas fa-tag"></i> Rp ${harga} / pcs</span>
                        </div>
                    </div>
                </div>
            `);
        },
        templateSelection: function (option) {
            if (!option.id) return option.text;
            const b = barangs[option.id] || barangs[parseInt(option.id)];
            if (!b) return option.text;
            return `${b.nama_barang} (${b.kode_barang}) - ${b.kategori_label}`;
        }
    });
}

    $(document).ready(function () {
        initSelect2Barang(document.querySelector('.item-row'));
    });

    document.getElementById('btn-add-item').addEventListener('click', function () {
        const container = document.getElementById('item-container');
        
        const barangOptionsHtml = Object.values(barangs).map(b => 
            `<option value="${b.id}">${b.nama_barang} (${b.kode_barang})</option>`
        ).join('');

        const rowHtml = `
            <div class="row item-row mb-2">
                <div class="col-md-7">
                    <select name="items[${itemIndex}][barang_id]" class="form-control select-barang" required>
                        <option value="">-- Pilih Barang --</option>
                        ${barangOptionsHtml}
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
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rowHtml);
        
        const newRow = container.lastElementChild;
        initSelect2Barang(newRow);

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