@extends('adminlte::page')

@section('title', 'Edit Paket Service')

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
    .info-harga-jual {
        display: block;
        margin-top: 4px;
        font-weight: bold;
        color: #17a2b8;
    }
    .nama-barang-readonly {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>
@stop

@section('content_header')
<h1 style="text-transform: uppercase;">Edit Paket Service</h1>
@stop

@section('content')

<x-adminlte-card title="Form Edit Paket Service" theme="warning" icon="fas fa-edit" style="text-transform: uppercase;">

    <form action="{{ route('paket-service.update', $paketService->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bus</label>
                    <select name="bus_id"
                            style="text-transform: uppercase;"
                            class="form-control @error('bus_id') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih Bus --</option>
                        {{-- REVISI:SEMUA BUS --}}
                        <option value="0" {{ old('bus_id', $paketService->bus_id) == '0' ? 'selected' : '' }}>Semua Bus</option>
                        @foreach ($buses as $bus)
                            <option value="{{ $bus->id }}"
                                {{ old('bus_id', $paketService->bus_id) == $bus->id ? 'selected' : '' }}>
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
                           style="text-transform: uppercase;"
                           class="form-control @error('nama_paket') is-invalid @enderror"
                           placeholder="Masukkan nama paket"
                           value="{{ old('nama_paket', $paketService->nama_paket) }}"
                           required>
                    @error('nama_paket')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- REVISI:KATEGORI PAKET SERVICE --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kategori Paket</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="kategori" id="kategori-reguler" value="paket_reguler" {{ old('kategori', $paketService->kategori ?? 'paket_reguler') == 'paket_reguler' ? 'checked' : '' }}>
                            <label class="form-check-label" for="kategori-reguler">Paket Reguler</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="kategori" id="kategori-oli" value="paket_oli" {{ old('kategori', $paketService->kategori) == 'paket_oli' ? 'checked' : '' }}>
                            <label class="form-check-label" for="kategori-oli">Paket Oli</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Harga</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="hidden"
                               name="harga"
                               class="input-harga-value"
                               value="{{ old('harga', $paketService->harga) }}">
                        <input type="text"
                               class="form-control input-harga input-rupiah @error('harga') is-invalid @enderror"
                               placeholder="Masukkan harga paket"
                               inputmode="numeric"
                               autocomplete="off"
                               style="text-transform: uppercase;"
                               value="{{ number_format(old('harga', $paketService->harga), 0, '', '.') }}"
                               required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <hr>

        {{-- REVISI: PAKET NON-ITEM --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="paket-tanpa-item" name="paket_tanpa_item" value="1" {{ old('paket_tanpa_item', $paketService->paketServiceItem->isEmpty() ? '1' : '') ? 'checked' : '' }}>
            <label class="form-check-label font-weight-bold" for="paket-tanpa-item">
                Paket ini hanya mencatat harga saja (tanpa mengurangi stok barang)
            </label>
            <small class="text-muted d-block">Centang ini jika paket service hanya untuk data bus, tanpa pemakaian barang.</small>
        </div>

        <h5 id="judul-item-paket"><i class="fas fa-boxes"></i> Item Barang dalam Paket</h5>
        <small class="text-muted" id="subjudul-item-paket">Barang yang stoknya akan berkurang otomatis ketika paket ini dipilih. Item lama hanya qty yang bisa diubah — untuk mengganti barang, hapus item lalu tambah item baru.</small>

        <div class="mt-3" id="item-container">

            {{-- ITEM LAMA: nama barang readonly, hanya qty yang bisa diedit --}}
            @foreach ($paketService->paketServiceItem as $index => $item)
            <div class="row item-row mb-2">
                <div class="col-md-7">
                    <input type="text"
                           class="form-control nama-barang-readonly"
                           value="{{ $item->barang->nama_barang ?? '-' }} ({{ $item->barang->kode_barang ?? '-' }})"
                           readonly>
                    <input type="hidden" name="items[{{ $index }}][barang_id]" value="{{ $item->barang_id }}">
                </div>
                <div class="col-md-3">
                    <input type="text"
                           name="items[{{ $index }}][qty]"
                           class="form-control input-desimal"
                           placeholder="Contoh: 2 atau 13,5"
                           inputmode="decimal"
                           autocomplete="off"
                           value="{{ old('items.' . $index . '.qty', rtrim(rtrim(number_format($item->qty, 2, ',', ''), '0'), ',')) }}"
                           required>
                </div>
                <div class="col-md-2">
                    <button type="button"
                            class="btn btn-danger btn-remove-item"
                            {{ $paketService->paketServiceItem->count() === 1 ? 'disabled' : '' }}>
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @endforeach

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
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-save"></i>
            UPDATE
        </button>

    </form>

</x-adminlte-card>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const barangs = @json($barangs);
    let itemIndex = {{ $paketService->paketServiceItem->count() }};

    // ========== Format Rupiah ==========
    function formatRupiah(angka) {
        angka = angka.toString().replace(/\D/g, '');
        if (!angka) return '';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function unformatRupiah(str) {
        return str ? str.toString().replace(/\./g, '') : '';
    }

    // ========== Auto-format input Harga Paket + sinkron ke hidden ==========
    document.addEventListener('input', function (e) {
        if (!e.target.classList.contains('input-rupiah')) return;

        const cursorPos = e.target.selectionStart;
        const oldLength = e.target.value.length;

        e.target.value = formatRupiah(e.target.value);

        const newLength = e.target.value.length;
        const diff = newLength - oldLength;
        const newPos = Math.max(0, cursorPos + diff);
        e.target.setSelectionRange(newPos, newPos);

        if (e.target.classList.contains('input-harga')) {
            const container = e.target.closest('.input-group');
            const hidden = container.querySelector('.input-harga-value');
            if (hidden) hidden.value = unformatRupiah(e.target.value);
        }
    });

    // ========== Helper Desimal (koma sebagai pemisah desimal, untuk qty) ==========
    function parseDesimal(str) {
        if (!str) return 0;
        return parseFloat(str.toString().replace(',', '.')) || 0;
    }

    // Batasi input desimal: hanya digit dan satu koma
    document.addEventListener('input', function (e) {
        if (!e.target.classList.contains('input-desimal')) return;

        let value = e.target.value.replace(/[^0-9,]/g, '');
        const parts = value.split(',');
        if (parts.length > 2) {
            value = parts[0] + ',' + parts.slice(1).join('');
        }
        e.target.value = value;
    });

    // ========== Select2 untuk item BARU (barang belum dipilih sebelumnya) ==========
    function initSelect2Barang(row) {
        const select = $(row).find('.select-barang');
        if (!select.length) return;

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

                const harga = formatRupiah(Math.round(parseFloat(b.harga_jual)));

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

        // Tampilkan info harga jual saat barang dipilih
        select.on('change', function () {
            const r = $(this).closest('.item-row')[0];
            const infoHarga = r.querySelector('.info-harga-jual');
            const b = barangs[$(this).val()] || barangs[parseInt($(this).val())];

            if (b) {
                const hargaFormatted = formatRupiah(Math.round(parseFloat(b.harga_jual)));
                infoHarga.textContent = `Harga Jual: Rp ${hargaFormatted} / pcs`;
                infoHarga.style.display = 'block';
            } else {
                infoHarga.textContent = '';
                infoHarga.style.display = 'none';
            }
        });
    }

    // Item lama tidak pakai Select2 (nama readonly), jadi tidak perlu inisialisasi apapun saat load
    // kecuali nanti ada item baru yang ditambahkan lewat tombol "Tambah Item"

    document.getElementById('btn-add-item').addEventListener('click', function () {
        const container = document.getElementById('item-container');

        const barangOptionsHtml = Object.values(barangs).map(b => 
            `<option value="${b.id}">${b.nama_barang} (${b.kode_barang})</option>`
        ).join('');

        const rowHtml = `
            <div class="row item-row mb-2" style="text-transform: uppercase;">
                <div class="col-md-7">
                    <select name="items[${itemIndex}][barang_id]" class="form-control select-barang" required style="text-transform: uppercase;">
                        <option value="">-- Pilih Barang --</option>
                        ${barangOptionsHtml}
                    </select>
                    <small class="text-muted info-harga-jual" style="display:none;"></small>
                </div>
                <div class="col-md-3">
                    <input style="text-transform: uppercase;" type="text" name="items[${itemIndex}][qty]" class="form-control input-desimal" placeholder="Contoh: 2 atau 13,5" inputmode="decimal" autocomplete="off" required>
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

    // ============================================
    // REVISI: PAKET NON-ITEM — toggle section item berdasarkan checkbox
    // ============================================
    const checkboxTanpaItem = document.getElementById('paket-tanpa-item');
    const itemContainer = document.getElementById('item-container');
    const btnAddItem = document.getElementById('btn-add-item');
    const judulItem = document.getElementById('judul-item-paket');
    const subjudulItem = document.getElementById('subjudul-item-paket');

    function toggleItemSection() {
        const tanpaItem = checkboxTanpaItem.checked;
        itemContainer.style.display = tanpaItem ? 'none' : '';
        btnAddItem.style.display = tanpaItem ? 'none' : '';
        judulItem.style.display = tanpaItem ? 'none' : '';
        subjudulItem.style.display = tanpaItem ? 'none' : '';

        // Disable semua input item jika tanpa item, agar tidak terkirim ke server
        itemContainer.querySelectorAll('input, select, button').forEach(el => {
            el.disabled = tanpaItem;
        });
    }

    checkboxTanpaItem.addEventListener('change', toggleItemSection);
    toggleItemSection(); // init saat load

    // ========== Pengaman terakhir sebelum submit ==========
    document.querySelector('form').addEventListener('submit', function () {
        // Konversi input desimal (koma -> titik) supaya diterima backend sebagai numeric
        document.querySelectorAll('.input-desimal').forEach(function (input) {
            input.value = input.value.replace(',', '.');
        });
    });
</script>
@stop
@stop