@extends('adminlte::page')

@section('title', 'Tambah Transaksi Keluar')

@section('content_header')
    <h1>Tambah Transaksi Keluar</h1>
@stop

@section('content')

<x-adminlte-card title="Form Transaksi Keluar" theme="danger" icon="fas fa-plus-circle">

    {{-- <form action="{{ route('transaksi-keluar.store') }}" method="POST"> --}}
        <form action="{{ route('barang-keluar.store') }}" method="POST">
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
                    <label>Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           class="form-control"
                           required>
                </div>
            </div>

        </div>

        <hr>

        <h5><i class="fas fa-list"></i> Item Transaksi</h5>
        <small class="text-muted">Tambahkan item transaksi, bisa lebih dari satu dalam satu transaksi</small>

        <div class="mt-3" id="item-container">

            {{-- Item Row 1 --}}
            <div class="card card-outline card-secondary mb-3 item-row">
                <div class="card-body">

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
                                    <select name="items[0][paket_service_id]" class="form-control">
                                        <option value="">-- Pilih Paket Service --</option>
                                        <option value="1">Paket Service Ringan - Rp 150.000</option>
                                        <option value="2">Paket Service Berat - Rp 300.000</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="number"
                                           name="items[0][subtotal]"
                                           class="form-control"
                                           placeholder="Otomatis terisi"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Per Item --}}
                    <div class="section-per-item" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Barang</label>
                                    <select name="items[0][barang_id]" class="form-control">
                                        <option value="">-- Pilih Barang --</option>
                                        <option value="1">Oli Mesin Shell - Stok: 24</option>
                                        <option value="2">Oli Mesin Pertamina - Stok: 48</option>
                                        <option value="3">Filter Solar Sakura - Stok: 12</option>
                                        <option value="4">Filter Solar Mann - Stok: 6</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Item</label>
                                    <input type="text"
                                           name="items[0][nama_item]"
                                           class="form-control"
                                           placeholder="Masukkan nama item">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Qty</label>
                                    <input type="number"
                                           name="items[0][qty]"
                                           class="form-control"
                                           placeholder="Qty"
                                           min="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Harga Satuan</label>
                                    <input type="number"
                                           name="items[0][harga_satuan]"
                                           class="form-control"
                                           placeholder="Harga satuan">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="number"
                                           name="items[0][subtotal]"
                                           class="form-control"
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

    function buildItemRow(index) {
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
                                <select name="items[${index}][paket_service_id]" class="form-control">
                                    <option value="">-- Pilih Paket Service --</option>
                                    <option value="1">Paket Service Ringan - Rp 150.000</option>
                                    <option value="2">Paket Service Berat - Rp 300.000</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subtotal</label>
                                <input type="number" name="items[${index}][subtotal]" class="form-control" placeholder="Otomatis terisi" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-per-item" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Barang</label>
                                <select name="items[${index}][barang_id]" class="form-control">
                                    <option value="">-- Pilih Barang --</option>
                                    <option value="1">Oli Mesin Shell - Stok: 24</option>
                                    <option value="2">Oli Mesin Pertamina - Stok: 48</option>
                                    <option value="3">Filter Solar Sakura - Stok: 12</option>
                                    <option value="4">Filter Solar Mann - Stok: 6</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Item</label>
                                <input type="text" name="items[${index}][nama_item]" class="form-control" placeholder="Masukkan nama item">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="number" name="items[${index}][qty]" class="form-control" placeholder="Qty" min="1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Satuan</label>
                                <input type="number" name="items[${index}][harga_satuan]" class="form-control" placeholder="Harga satuan">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Subtotal</label>
                                <input type="number" name="items[${index}][subtotal]" class="form-control" placeholder="Otomatis terisi" readonly>
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

    // Tambah item
    document.getElementById('btn-add-item').addEventListener('click', function () {
        const container = document.getElementById('item-container');
        container.insertAdjacentHTML('beforeend', buildItemRow(itemIndex));
        itemIndex++;
        updateRemoveButtons();
        bindTipeRadio();
    });

    // Hapus item
    document.getElementById('item-container').addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-item')) {
            e.target.closest('.item-row').remove();
            updateRemoveButtons();
        }
    });

    // Toggle paket service / per item
    function bindTipeRadio() {
        document.querySelectorAll('.tipe-radio').forEach(function (radio) {
            radio.addEventListener('change', function () {
                const row = this.closest('.item-row');
                const sectionPaket = row.querySelector('.section-paket-service');
                const sectionItem = row.querySelector('.section-per-item');
                if (this.value === 'paket_service') {
                    sectionPaket.style.display = 'block';
                    sectionItem.style.display = 'none';
                } else {
                    sectionPaket.style.display = 'none';
                    sectionItem.style.display = 'block';
                }
            });
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.btn-remove-item');
            btn.disabled = rows.length === 1;
        });
    }

    bindTipeRadio();
</script>
@stop

@stop