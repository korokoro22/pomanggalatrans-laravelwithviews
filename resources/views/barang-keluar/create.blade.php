@extends('adminlte::page')

@section('title', 'Tambah Transaksi Keluar')

@section('content_header')
    <h1>Tambah Transaksi Keluar</h1>
@stop

@section('content')

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
    </div>
@endif

<x-adminlte-card title="Form Transaksi Keluar" theme="danger" icon="fas fa-plus-circle">

    <form action="{{ route('barang-keluar.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bus</label>
                    <select name="bus_id" id="bus-select" class="form-control" required>
                        <option value="">-- Pilih Bus --</option>
                        @foreach($busList as $bus)
                            <option value="{{ $bus->id }}">
                                {{ $bus->nama_bus }} - {{ $bus->plat_nomor }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
            </div>
        </div>

        <hr>

        <h5><i class="fas fa-list"></i> Item Transaksi</h5>
        <small class="text-muted">Tambahkan item transaksi, bisa lebih dari satu dalam satu transaksi</small>

        <div class="mt-3" id="item-container">

            <div class="card card-outline card-secondary mb-3 item-row">
                <div class="card-body">

                    {{-- Tipe --}}
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tipe Item</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input tipe-radio"
                                               type="radio"
                                               name="items[0][tipe]"
                                               value="paket_service"
                                               checked>
                                        <label class="form-check-label">Paket Service</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input tipe-radio"
                                               type="radio"
                                               name="items[0][tipe]"
                                               value="per_item">
                                        <label class="form-check-label">Per Item</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Paket Service --}}
                    <div class="section-paket-service">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Paket Service</label>
                                    <select name="items[0][paket_service_id]"
                                            class="form-control select-paket"
                                            disabled>
                                        <option value="">-- Pilih Bus dulu --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Harga Paket</label>
                                    <input type="number"
                                           class="form-control input-harga-paket"
                                           placeholder="Otomatis terisi"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="hidden" name="items[0][subtotal]" class="input-subtotal-value">
                                    <input type="number"
                                           class="form-control input-subtotal"
                                           placeholder="Otomatis terisi"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Per Item --}}
                    <div class="section-per-item" style="display:none">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Barang</label>
                                    <select name="items[0][barang_id]"
                                            class="form-control select-barang">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}"
                                                    data-harga="{{ $barang->harga_jual }}"
                                                    data-satuan="{{ $barang->satuan }}"
                                                    data-stok="{{ $barang->stok_saat_ini }}">
                                                {{ $barang->nama_barang }} — Stok: {{ $barang->stok_saat_ini }} {{ $barang->satuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Satuan</label>
                                    <input type="text"
                                           name="items[0][satuan]"
                                           class="form-control input-satuan"
                                           placeholder="Otomatis terisi"
                                           readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Harga Satuan</label>
                                    <input type="number"
                                           name="items[0][harga_satuan]"
                                           class="form-control input-harga-satuan"
                                           placeholder="Otomatis terisi"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Qty</label>
                                    <input type="number"
                                           name="items[0][qty]"
                                           class="form-control input-qty"
                                           placeholder="Masukkan qty"
                                           min="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="hidden" name="items[0][subtotal]" class="input-subtotal-value">
                                    <input type="number"
                                           class="form-control input-subtotal"
                                           placeholder="Otomatis terisi"
                                           readonly>
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

        <button type="button" class="btn btn-success btn-sm mb-3" id="btn-add-item">
            <i class="fas fa-plus"></i> Tambah Item
        </button>

        <hr>

        <div class="row">
            <div class="col-md-4 offset-md-8">
                <div class="form-group">
                    <label><strong>Total Transaksi</strong></label>
                    <input type="number"
                           name="total_transaksi"
                           id="total-transaksi"
                           class="form-control"
                           placeholder="Rp 0"
                           readonly>
                </div>
            </div>
        </div>

        <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
        </button>

    </form>

</x-adminlte-card>

@section('js')
<script>
    let itemIndex = 1;
    let paketList = [];

    const ajaxUrl = "{{ route('barang-keluar.paket-by-bus', ':busId') }}";
    const barangs = @json($barangs->keyBy('id'));

    // ========== AJAX: Load paket saat bus dipilih ==========
    document.getElementById('bus-select').addEventListener('change', function () {
        const busId = this.value;
        paketList = [];

        document.querySelectorAll('.select-paket').forEach(sel => {
            sel.innerHTML = '<option value="">-- Pilih Paket Service --</option>';
            sel.disabled = true;
        });

        if (!busId) return;

        fetch(ajaxUrl.replace(':busId', busId))
            .then(res => res.json())
            .then(data => {
                paketList = data;
                document.querySelectorAll('.select-paket').forEach(sel => {
                    sel.disabled = false;
                    data.forEach(paket => {
                        const opt = document.createElement('option');
                        opt.value         = paket.id;
                        opt.text          = paket.nama_paket + ' — Rp ' + Number(paket.harga).toLocaleString('id-ID');
                        opt.dataset.harga = paket.harga;
                        sel.appendChild(opt);
                    });
                });
            });
    });

    // ========== Build item row ==========
    function buildItemRow(index) {
        const barangOptions = Object.values(barangs).map(b =>
            `<option value="${b.id}" data-harga="${b.harga_jual}" data-satuan="${b.satuan}" data-stok="${b.stok_saat_ini}">
                ${b.nama_barang} — Stok: ${b.stok_saat_ini} ${b.satuan}
            </option>`
        ).join('');

        const paketOptions = paketList.length
            ? paketList.map(p => `<option value="${p.id}" data-harga="${p.harga}">${p.nama_paket} — Rp ${Number(p.harga).toLocaleString('id-ID')}</option>`).join('')
            : '';

        return `
        <div class="card card-outline card-secondary mb-3 item-row">
            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Tipe Item</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input tipe-radio" type="radio" name="items[${index}][tipe]" value="paket_service" checked>
                                    <label class="form-check-label">Paket Service</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input tipe-radio" type="radio" name="items[${index}][tipe]" value="per_item">
                                    <label class="form-check-label">Per Item</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-paket-service">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Paket Service</label>
                                <select name="items[${index}][paket_service_id]" class="form-control select-paket" ${paketList.length ? '' : 'disabled'}>
                                    <option value="">${paketList.length ? '-- Pilih Paket Service --' : '-- Pilih Bus dulu --'}</option>
                                    ${paketOptions}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Harga Paket</label>
                                <input type="number" class="form-control input-harga-paket" placeholder="Otomatis terisi" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Subtotal</label>
                                <input type="hidden" name="items[${index}][subtotal]" class="input-subtotal-value">
                                <input type="number" class="form-control input-subtotal" placeholder="Otomatis terisi" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-per-item" style="display:none">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Barang</label>
                                <select name="items[${index}][barang_id]" class="form-control select-barang">
                                    <option value="">-- Pilih Barang --</option>
                                    ${barangOptions}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Satuan</label>
                                <input type="text" name="items[${index}][satuan]" class="form-control input-satuan" placeholder="Otomatis terisi" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Satuan</label>
                                <input type="number" name="items[${index}][harga_satuan]" class="form-control input-harga-satuan" placeholder="Otomatis terisi" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="number" name="items[${index}][qty]" class="form-control input-qty" placeholder="Masukkan qty" min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Subtotal</label>
                                <input type="hidden" name="items[${index}][subtotal]" class="input-subtotal-value">
                                <input type="number" class="form-control input-subtotal" placeholder="Otomatis terisi" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-sm btn-remove-item">
                    <i class="fas fa-trash"></i> Hapus Item
                </button>

            </div>
        </div>`;
    }

    // ========== Event: change ==========
    document.getElementById('item-container').addEventListener('change', function (e) {
        const row = e.target.closest('.item-row');
        if (!row) return;

        // Toggle tipe
        if (e.target.classList.contains('tipe-radio')) {
            const sectionPaket = row.querySelector('.section-paket-service');
            const sectionItem  = row.querySelector('.section-per-item');
            if (e.target.value === 'paket_service') {
                sectionPaket.style.display = 'block';
                sectionItem.style.display  = 'none';
            } else {
                sectionPaket.style.display = 'none';
                sectionItem.style.display  = 'block';
            }
            resetSubtotal(row);
        }

        // Pilih paket → isi harga & subtotal
        if (e.target.classList.contains('select-paket')) {
            const harga = e.target.selectedOptions[0]?.dataset.harga || 0;
            row.querySelector('.input-harga-paket').value = harga;
            row.querySelector('.section-paket-service .input-subtotal').value       = harga;
            row.querySelector('.section-paket-service .input-subtotal-value').value = harga;
            hitungTotal();
        }

        // Pilih barang → isi harga satuan & satuan, reset qty & subtotal
        if (e.target.classList.contains('select-barang')) {
            const opt    = e.target.selectedOptions[0];
            const harga  = opt?.dataset.harga || 0;
            const satuan = opt?.dataset.satuan || '';
            row.querySelector('.input-harga-satuan').value = harga;
            row.querySelector('.input-satuan').value       = satuan;
            row.querySelector('.input-qty').value          = '';
            row.querySelector('.section-per-item .input-subtotal').value       = 0;
            row.querySelector('.section-per-item .input-subtotal-value').value = 0;
            hitungTotal();
        }
    });

    // ========== Event: input (qty) ==========
    document.getElementById('item-container').addEventListener('input', function (e) {
        const row = e.target.closest('.item-row');
        if (!row) return;

        if (e.target.classList.contains('input-qty')) {
            const qty      = parseInt(e.target.value) || 0;
            const harga    = parseFloat(row.querySelector('.input-harga-satuan').value) || 0;
            const subtotal = qty * harga;
            row.querySelector('.section-per-item .input-subtotal').value       = subtotal;
            row.querySelector('.section-per-item .input-subtotal-value').value = subtotal;
            hitungTotal();
        }
    });

    // ========== Event: hapus item ==========
    document.getElementById('item-container').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-item')) {
            e.target.closest('.item-row').remove();
            updateRemoveButtons();
            hitungTotal();
        }
    });

    // ========== Event: tambah item ==========
    document.getElementById('btn-add-item').addEventListener('click', function () {
        document.getElementById('item-container')
                .insertAdjacentHTML('beforeend', buildItemRow(itemIndex));
        itemIndex++;
        updateRemoveButtons();
    });

    // ========== Helpers ==========
    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const tipe = row.querySelector('.tipe-radio:checked')?.value;
            if (tipe === 'paket_service') {
                total += parseFloat(row.querySelector('.section-paket-service .input-subtotal')?.value) || 0;
            } else {
                total += parseFloat(row.querySelector('.section-per-item .input-subtotal')?.value) || 0;
            }
        });
        document.getElementById('total-transaksi').value = total;
    }

    function resetSubtotal(row) {
        row.querySelector('.section-paket-service .input-subtotal').value       = 0;
        row.querySelector('.section-paket-service .input-subtotal-value').value = 0;
        row.querySelector('.section-per-item .input-subtotal').value            = 0;
        row.querySelector('.section-per-item .input-subtotal-value').value      = 0;
        hitungTotal();
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(row => {
            row.querySelector('.btn-remove-item').disabled = rows.length === 1;
        });
    }
</script>
@stop

@stop