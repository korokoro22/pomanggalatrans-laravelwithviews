@extends('adminlte::page')

@section('title', 'Edit Transaksi Keluar')

@section('content_header')
    <h1>Edit Transaksi Keluar</h1>
@stop

@section('content')

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
    </div>
@endif

<x-adminlte-card title="Form Edit Transaksi Keluar" theme="danger" icon="fas fa-edit">

    <form action="{{ route('barang-keluar.update', $transaksi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bus</label>
                    <select name="bus_id" id="bus-select" class="form-control" required>
                        <option value="">-- Pilih Bus --</option>
                        @foreach($busList as $bus)
                            <option value="{{ $bus->id }}"
                                {{ $transaksi->bus_id == $bus->id ? 'selected' : '' }}>
                                {{ $bus->nama_bus }} - {{ $bus->plat_nomor }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           class="form-control"
                           value="{{ $transaksi->tanggal }}"
                           required>
                </div>
            </div>
        </div>

        <hr>

        <h5><i class="fas fa-list"></i> Item Transaksi</h5>
        <small class="text-muted">Edit item transaksi</small>

        <div class="mt-3" id="item-container">

            @foreach($transaksi->details as $index => $detail)
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
                                               name="items[{{ $index }}][tipe]"
                                               value="paket_service"
                                               {{ $detail->tipe === 'paket_service' ? 'checked' : '' }}>
                                        <label class="form-check-label">Paket Service</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input tipe-radio"
                                               type="radio"
                                               name="items[{{ $index }}][tipe]"
                                               value="per_item"
                                               {{ $detail->tipe === 'per_item' ? 'checked' : '' }}>
                                        <label class="form-check-label">Per Item</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Paket Service --}}
                    <div class="section-paket-service" style="{{ $detail->tipe === 'paket_service' ? '' : 'display:none' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Paket Service</label>
                                    <select name="items[{{ $index }}][paket_service_id]"
                                            class="form-control select-paket">
                                        <option value="">-- Pilih Paket Service --</option>
                                        @if($detail->tipe === 'paket_service' && $detail->paketService)
                                            <option value="{{ $detail->paketService->id }}" selected>
                                                {{ $detail->paketService->nama_paket }} — Rp {{ number_format($detail->paketService->harga, 0, ',', '.') }}
                                            </option>
                                        @endif
                                    </select>
                                    <small class="text-muted">Pilih ulang bus untuk memuat paket service lain</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Harga Paket</label>
                                    <input type="number"
                                           class="form-control input-harga-paket"
                                           value="{{ $detail->tipe === 'paket_service' ? $detail->harga_satuan : '' }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="hidden" name="items[{{ $index }}][subtotal]" class="input-subtotal-value" value="{{ $detail->tipe === 'paket_service' ? $detail->subtotal : '' }}">
                                    <input type="number"
                                           class="form-control input-subtotal"
                                           value="{{ $detail->tipe === 'paket_service' ? $detail->subtotal : '' }}"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Per Item --}}
                    <div class="section-per-item" style="{{ $detail->tipe === 'per_item' ? '' : 'display:none' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Barang</label>
                                    <select name="items[{{ $index }}][barang_id]"
                                            class="form-control select-barang">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}"
                                                    data-harga="{{ $barang->harga_jual }}"
                                                    data-satuan="{{ $barang->satuan }}"
                                                    data-stok="{{ $barang->stok_saat_ini }}"
                                                    {{ $detail->barang_id == $barang->id ? 'selected' : '' }}>
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
                                           name="items[{{ $index }}][satuan]"
                                           class="form-control input-satuan"
                                           value="{{ $detail->satuan }}"
                                           readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Harga Satuan</label>
                                    <input type="number"
                                           name="items[{{ $index }}][harga_satuan]"
                                           class="form-control input-harga-satuan"
                                           value="{{ $detail->harga_satuan }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Qty</label>
                                    <input type="number"
                                           name="items[{{ $index }}][qty]"
                                           class="form-control input-qty"
                                           value="{{ $detail->qty }}"
                                           min="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="hidden" name="items[{{ $index }}][subtotal]" class="input-subtotal-value" value="{{ $detail->tipe === 'per_item' ? $detail->subtotal : '' }}">
                                    <input type="number"
                                           class="form-control input-subtotal"
                                           value="{{ $detail->tipe === 'per_item' ? $detail->subtotal : '' }}"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger btn-sm btn-remove-item" {{ $transaksi->details->count() === 1 ? 'disabled' : '' }}>
                        <i class="fas fa-trash"></i> Hapus Item
                    </button>

                </div>
            </div>
            @endforeach

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
                           id="total-transaksi"
                           class="form-control"
                           value="{{ $transaksi->total_transaksi }}"
                           readonly>
                </div>
            </div>
        </div>

        <a href="{{ route('barang-keluar.show', $transaksi->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>

    </form>

</x-adminlte-card>

@section('js')
<script>
    let itemIndex = {{ $transaksi->details->count() }};
    let paketList = [];

    const ajaxUrl  = "{{ route('barang-keluar.paket-by-bus', ':busId') }}";
    const barangs  = @json($barangs->keyBy('id'));
    const currentBusId = {{ $transaksi->bus_id }};

    // Load paket untuk bus yang sudah dipilih saat halaman dibuka
    window.addEventListener('DOMContentLoaded', function () {
        loadPaket(currentBusId, false);
    });

    function loadPaket(busId, resetDropdown = true) {
        if (!busId) return;

        fetch(ajaxUrl.replace(':busId', busId))
            .then(res => res.json())
            .then(data => {
                paketList = data;
                if (resetDropdown) {
                    document.querySelectorAll('.select-paket').forEach(sel => {
                        const currentVal = sel.value;
                        sel.innerHTML = '<option value="">-- Pilih Paket Service --</option>';
                        data.forEach(p => {
                            const opt = document.createElement('option');
                            opt.value         = p.id;
                            opt.text          = p.nama_paket + ' — Rp ' + Number(p.harga).toLocaleString('id-ID');
                            opt.dataset.harga = p.harga;
                            if (p.id == currentVal) opt.selected = true;
                            sel.appendChild(opt);
                        });
                        sel.disabled = false;
                    });
                }
            });
    }

    // Ganti bus
    document.getElementById('bus-select').addEventListener('change', function () {
        loadPaket(this.value, true);
    });

    // Build item row baru
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

    // Event: change
    document.getElementById('item-container').addEventListener('change', function (e) {
        const row = e.target.closest('.item-row');
        if (!row) return;

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

        if (e.target.classList.contains('select-paket')) {
            const harga = e.target.selectedOptions[0]?.dataset.harga || 0;
            row.querySelector('.input-harga-paket').value = harga;
            row.querySelector('.section-paket-service .input-subtotal').value       = harga;
            row.querySelector('.section-paket-service .input-subtotal-value').value = harga;
            hitungTotal();
        }

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

    // Event: input qty
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

    // Event: hapus item
    document.getElementById('item-container').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-item')) {
            e.target.closest('.item-row').remove();
            updateRemoveButtons();
            hitungTotal();
        }
    });

    // Event: tambah item
    document.getElementById('btn-add-item').addEventListener('click', function () {
        document.getElementById('item-container')
                .insertAdjacentHTML('beforeend', buildItemRow(itemIndex));
        itemIndex++;
        updateRemoveButtons();
    });

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