@extends('adminlte::page')

@section('title', 'Tambah Nota Jalan')

@section('content_header')
    <h1 style="text-transform: uppercase;">Tambah Nota Jalan</h1>
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Validasi Gagal!</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session('error') }}
    </div>
@endif

<form action="{{ route('nota-jalan.store') }}" method="POST" enctype="multipart/form-data" style="text-transform: uppercase;">
@csrf

{{-- HEADER NOTA --}}
<x-adminlte-card title="Informasi Nota Jalan" theme="warning" icon="fas fa-route">

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Bus</label>
                <select name="bus_id"
                        class="form-control @error('bus_id') is-invalid @enderror" style="text-transform: uppercase;"
                        required>
                    <option value="">-- Pilih Bus --</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
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
                       class="form-control @error('tanggal') is-invalid @enderror"
                       style="text-transform: uppercase;"
                       value="{{ old('tanggal') }}"
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
                       class="form-control @error('no_invoice') is-invalid @enderror"
                       placeholder="Masukkan nomor invoice"
                       style="text-transform: uppercase;"
                       value="{{ old('no_invoice') }}"
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
                       placeholder="Masukkan nama supplier/toko"
                       value="{{ old('supplier') }}"
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
                <input type="file"
                       name="bukti_nota"
                       class="form-control @error('bukti_nota') is-invalid @enderror"
                       style="text-transform: uppercase;"
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

        <div class="card card-outline card-secondary mb-3 item-row">
            <div class="card-header">
                <h6 class="card-title mb-0">Item #1</h6>
            </div>
            <div class="card-body">

                {{-- Tipe Item --}}
                <div class="form-group">
                    <label>Tipe Item</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input tipe-radio"
                                type="radio"
                                name="items[0][tipe]"
                                value="per_item"
                                checked>
                            <label class="form-check-label">Per Item</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input tipe-radio"
                                type="radio"
                                name="items[0][tipe]"
                                value="biaya_pengerjaan">
                            <label class="form-check-label">Biaya Pengerjaan</label>
                        </div>
                    </div>
                </div>

                {{-- Section Per Item --}}
                <div class="section-per-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Item</label>
                                <input type="text"
                                    name="items[0][nama_item]"
                                    class="form-control input-nama-item"
                                    placeholder="Masukkan nama item" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="number"
                                    name="items[0][qty]"
                                    class="form-control input-qty"
                                    placeholder="Contoh: 2"
                                    style="text-transform: uppercase;"
                                    min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Satuan</label>
                                <input type="text"
                                    name="items[0][satuan]"
                                    class="form-control input-satuan"
                                    style="text-transform: uppercase;"
                                    placeholder="Pcs, Botol, Liter">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Satuan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="hidden" name="items[0][harga_satuan]" class="input-harga-satuan-value">
                                    <input type="text"
                                        class="form-control input-harga-satuan input-rupiah"
                                        placeholder="Harga per satuan"
                                        inputmode="numeric"
                                        autocomplete="off"
                                        style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subtotal <small class="text-muted">(otomatis, bisa diedit)</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="hidden" name="items[0][subtotal]" class="input-subtotal-value">
                                    <input type="text"
                                        class="form-control input-subtotal input-rupiah"
                                        placeholder="Subtotal"
                                        inputmode="numeric"
                                        autocomplete="off"
                                        style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section Biaya Pengerjaan --}}
                <div class="section-biaya-pengerjaan" style="display:none">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Keterangan Pengerjaan</label>
                                <input type="text"
                                    name="items[0][keterangan]"
                                    class="form-control input-keterangan-pengerjaan"
                                    style="text-transform: uppercase;"
                                    placeholder="Contoh: Ongkos bongkar muat, servis darurat di jalan"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Biaya Pengerjaan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="hidden" name="items[0][subtotal]" class="input-biaya-pengerjaan-value" disabled>
                                    <input type="text"
                                        class="form-control input-biaya-pengerjaan input-rupiah"
                                        placeholder="Masukkan biaya"
                                        inputmode="numeric"
                                        autocomplete="off"
                                        style="text-transform: uppercase;"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-sm btn-remove-item" disabled>
                    <i class="fas fa-trash"></i> Hapus Item
                </button>

            </div>
        </div>

    </div>

    <button type="button" class="btn btn-success btn-sm" id="btn-add-item">
        <i class="fas fa-plus"></i> Tambah Item
    </button>

    <hr>

    <div class="row">
        <div class="col-md-4 offset-md-8">
            <div class="form-group">
                <label><strong>Total Keseluruhan</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="hidden" name="total" class="input-total-value">
                    <input type="text"
                           class="form-control input-total input-rupiah"
                           placeholder="Total keseluruhan"
                           inputmode="numeric"
                           autocomplete="off"
                           style="text-transform: uppercase;"
                           readonly>
                </div>
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
        Simpan
    </button>
</div>

</form>

@section('js')
<script>
    let itemIndex = 1;

    // ========== Format & Unformat Rupiah ==========
    function formatRupiah(angka) {
        angka = angka.toString().replace(/\D/g, '');
        if (!angka) return '';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function unformatRupiah(str) {
        return str ? str.toString().replace(/\./g, '') : '';
    }

    // ========== Auto-format tampilan + sinkron ke hidden pasangannya ==========
    document.addEventListener('input', function (e) {
        if (!e.target.classList.contains('input-rupiah') || e.target.readOnly || e.target.disabled) return;

        const cursorPos = e.target.selectionStart;
        const oldLength = e.target.value.length;

        e.target.value = formatRupiah(e.target.value);

        const newLength = e.target.value.length;
        const diff = newLength - oldLength;
        const newPos = Math.max(0, cursorPos + diff);
        e.target.setSelectionRange(newPos, newPos);

        const rawValue = unformatRupiah(e.target.value);
        const row = e.target.closest('.item-row');

        if (e.target.classList.contains('input-harga-satuan')) {
            const hidden = row.querySelector('.input-harga-satuan-value');
            if (hidden) hidden.value = rawValue;
            hitungSubtotalItem(row);
        }

        if (e.target.classList.contains('input-subtotal')) {
            const hidden = row.querySelector('.input-subtotal-value');
            if (hidden) hidden.value = rawValue;
            hitungTotalKeseluruhan();
        }

        if (e.target.classList.contains('input-biaya-pengerjaan')) {
            const hidden = row.querySelector('.input-biaya-pengerjaan-value');
            if (hidden) hidden.value = rawValue;
            // Untuk tipe biaya_pengerjaan, subtotal_biaya_value INILAH yang jadi subtotal final.
            // Kita sinkronkan juga ke .input-subtotal-value (yang dibaca hitungTotalKeseluruhan)
            const subtotalHidden = row.querySelector('.input-subtotal-value');
            if (subtotalHidden) subtotalHidden.value = rawValue;
            hitungTotalKeseluruhan();
        }
    });

    function buildItemRow(index) {
        return `
        <div class="card card-outline card-secondary mb-3 item-row">
            <div class="card-header">
                <h6 class="card-title mb-0">Item #${index + 1}</h6>
            </div>
            <div class="card-body" style="text-transform: uppercase;">

                <div class="form-group">
                    <label>Tipe Item</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input tipe-radio" type="radio" name="items[${index}][tipe]" value="per_item" checked>
                            <label class="form-check-label">Per Item</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input tipe-radio" type="radio" name="items[${index}][tipe]" value="biaya_pengerjaan">
                            <label class="form-check-label">Biaya Pengerjaan</label>
                        </div>
                    </div>
                </div>

                <div class="section-per-item">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Item</label>
                                <input type="text" name="items[${index}][nama_item]" class="form-control input-nama-item" placeholder="Masukkan nama item" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="number" name="items[${index}][qty]" class="form-control input-qty" placeholder="Contoh: 2" min="1" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Satuan</label>
                                <input type="text" name="items[${index}][satuan]" class="form-control input-satuan" placeholder="Pcs, Botol, Liter" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Satuan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="hidden" name="items[${index}][harga_satuan]" class="input-harga-satuan-value">
                                    <input type="text" class="form-control input-harga-satuan input-rupiah" placeholder="Harga per satuan" inputmode="numeric" autocomplete="off" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subtotal <small class="text-muted">(otomatis, bisa diedit)</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="hidden" name="items[${index}][subtotal]" class="input-subtotal-value">
                                    <input type="text" class="form-control input-subtotal input-rupiah" placeholder="Subtotal" inputmode="numeric" autocomplete="off" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-biaya-pengerjaan" style="display:none">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Keterangan Pengerjaan</label>
                                <input type="text" name="items[${index}][keterangan]" class="form-control input-keterangan-pengerjaan" placeholder="Contoh: Ongkos bongkar muat, servis darurat di jalan" style="text-transform: uppercase;" required disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Biaya Pengerjaan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="hidden" name="items[${index}][subtotal]" class="input-biaya-pengerjaan-value" disabled>
                                    <input type="text" class="form-control input-biaya-pengerjaan input-rupiah" placeholder="Masukkan biaya" inputmode="numeric" autocomplete="off" style="text-transform: uppercase;" required disabled>
                                </div>
                            </div>
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
            hitungTotalKeseluruhan();
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.btn-remove-item');
            btn.disabled = rows.length === 1;
        });
    }

    // ========== Hitung subtotal per item (Per Item: qty x harga) ==========
    function hitungSubtotalItem(row) {
        const qty   = parseFloat(row.querySelector('.input-qty')?.value) || 0;
        const harga = parseFloat(row.querySelector('.input-harga-satuan-value')?.value) || 0;
        const subtotal = qty * harga;

        const subtotalDisplay = row.querySelector('.input-subtotal');
        const subtotalHidden  = row.querySelector('.input-subtotal-value');
        if (subtotalDisplay) subtotalDisplay.value = formatRupiah(subtotal);
        if (subtotalHidden)  subtotalHidden.value  = subtotal;

        hitungTotalKeseluruhan();
    }

    // ========== Hitung total keseluruhan (baca dari hidden, angka mentah) ==========
    function hitungTotalKeseluruhan() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(function (row) {
            const tipe = row.querySelector('.tipe-radio:checked')?.value;
            if (tipe === 'biaya_pengerjaan') {
                total += parseFloat(row.querySelector('.input-biaya-pengerjaan-value')?.value) || 0;
            } else {
                total += parseFloat(row.querySelector('.input-subtotal-value')?.value) || 0;
            }
        });
        document.querySelector('.input-total').value       = formatRupiah(total);
        document.querySelector('.input-total-value').value = total;
    }

    // ========== Event: ganti tipe item ==========
    document.getElementById('item-container').addEventListener('change', function (e) {
        if (e.target.classList.contains('tipe-radio')) {
            const row = e.target.closest('.item-row');
            const sectionPerItem = row.querySelector('.section-per-item');
            const sectionBiaya   = row.querySelector('.section-biaya-pengerjaan');

            if (e.target.value === 'biaya_pengerjaan') {
                sectionPerItem.style.display = 'none';
                // Matikan input per_item agar tidak dikirim
                sectionPerItem.querySelectorAll('input').forEach(i => i.disabled = true);

                sectionBiaya.style.display   = 'block';
                // Nyalakan input biaya_pengerjaan agar dikirim
                sectionBiaya.querySelectorAll('input').forEach(i => i.disabled = false);
            } else {
                sectionPerItem.style.display = 'block';
                // Nyalakan input per_item
                sectionPerItem.querySelectorAll('input').forEach(i => i.disabled = false);

                sectionBiaya.style.display   = 'none';
                // Matikan input biaya_pengerjaan agar tidak dikirim
                sectionBiaya.querySelectorAll('input').forEach(i => i.disabled = true);
            }

            // reset subtotal saat ganti tipe
            const subtotalDisplay = row.querySelector('.input-subtotal');
            const subtotalHidden  = row.querySelector('.input-subtotal-value');
            if (subtotalDisplay) subtotalDisplay.value = formatRupiah(0);
            if (subtotalHidden)  subtotalHidden.value  = 0;

            const biayaDisplay = row.querySelector('.input-biaya-pengerjaan');
            const biayaHidden  = row.querySelector('.input-biaya-pengerjaan-value');
            if (biayaDisplay) biayaDisplay.value = '';
            if (biayaHidden)  biayaHidden.value  = 0;

            hitungTotalKeseluruhan();
        }
    });

    // ========== Event: hitung subtotal per item saat qty berubah ==========
    document.getElementById('item-container').addEventListener('input', function (e) {
        if (e.target.classList.contains('input-qty')) {
            const row = e.target.closest('.item-row');
            hitungSubtotalItem(row);
        }
    });

    // ========== Pengaman terakhir sebelum submit ==========
    document.querySelector('form').addEventListener('submit', function () {
        document.querySelectorAll('.item-row').forEach(function (row) {
            const tipe = row.querySelector('.tipe-radio:checked')?.value;
            if (tipe === 'per_item') {
                hitungSubtotalItem(row);
            }
        });
        hitungTotalKeseluruhan();
    });
</script>
@stop

@stop