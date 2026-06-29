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

        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h5 class="mb-0"><i class="fas fa-list"></i> Item Transaksi</h5>
                <small class="text-muted">Tambahkan item transaksi, bisa lebih dari satu dalam satu transaksi</small>
            </div>
            <button type="button" class="btn btn-info btn-sm" id="btn-scan-qr">
                <i class="fas fa-qrcode"></i> Scan QR Barang
            </button>
        </div>

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

{{-- Modal Scanner --}}
<div class="modal fade" id="modal-scanner" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-qrcode"></i> Scan QR Code Barang</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="qr-reader" style="width:100%"></div>
                <div id="qr-scan-status" class="mt-2 text-muted small">
                    Arahkan kamera ke QR code barang...
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Barang --}}
<div class="modal fade" id="modal-konfirmasi-barang" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white"><i class="fas fa-box"></i> Hasil Scan Barang</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img id="konfirmasi-foto" src="" alt="Foto Barang"
                         style="max-height:150px; object-fit:contain; display:none"
                         class="img-thumbnail">
                    <div id="konfirmasi-no-foto" class="text-muted small" style="display:none">
                        <i class="fas fa-image fa-2x"></i><br>Tidak ada foto
                    </div>
                </div>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th width="40%">Kode Barang</th>
                        <td id="konfirmasi-kode"></td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td id="konfirmasi-nama"></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td id="konfirmasi-kategori"></td>
                    </tr>
                    <tr>
                        <th>Harga Satuan</th>
                        <td id="konfirmasi-harga"></td>
                    </tr>
                    <tr>
                        <th>Stok Saat Ini</th>
                        <td id="konfirmasi-stok"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="btn-lihat-detail" class="btn btn-info" target="_blank">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
                <button type="button" class="btn btn-primary" id="btn-tambah-ke-form">
                    <i class="fas fa-plus"></i> Tambahkan ke Form
                </button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let itemIndex = 1;
    let paketList = [];

    const ajaxUrl   = "{{ route('barang-keluar.paket-by-bus', ':busId') }}";
    const jsonUrl   = "{{ route('master-barang.json', ':id') }}";
    const detailUrl = "{{ route('master-barang.show', ':id') }}";
    const barangs   = @json($barangs->keyBy('id'));

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

    // ========== QR SCANNER ==========
    let html5QrCode  = null;
    let scannedBarang = null;

    document.getElementById('btn-scan-qr').addEventListener('click', function () {
        $('#modal-scanner').modal('show');
    });

    $('#modal-scanner').on('shown.bs.modal', function () {
        html5QrCode = new Html5Qrcode("qr-reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            function (decodedText) {
                // Stop scanner
                html5QrCode.stop().then(() => {
                    $('#modal-scanner').modal('hide');
                });

                // Handle QR lama (URL) dan baru (angka murni)
                const id = decodedText.includes('/')
                    ? decodedText.split('/').pop()
                    : decodedText;

                // Fetch data barang
                fetch(jsonUrl.replace(':id', id))
                    .then(res => {
                        if (!res.ok) throw new Error('Barang tidak ditemukan');
                        return res.json();
                    })
                    .then(data => {
                        scannedBarang = data;

                        // Isi modal konfirmasi
                        document.getElementById('konfirmasi-kode').textContent     = data.kode_barang;
                        document.getElementById('konfirmasi-nama').textContent     = data.nama_barang;
                        document.getElementById('konfirmasi-kategori').textContent = data.kategori;
                        document.getElementById('konfirmasi-harga').textContent    = 'Rp ' + Number(data.harga_jual).toLocaleString('id-ID');
                        document.getElementById('konfirmasi-stok').textContent     = data.stok_saat_ini + ' ' + data.satuan;

                        // Foto
                        const fotoEl    = document.getElementById('konfirmasi-foto');
                        const noFotoEl  = document.getElementById('konfirmasi-no-foto');
                        if (data.foto) {
                            fotoEl.src           = data.foto;
                            fotoEl.style.display  = 'block';
                            noFotoEl.style.display = 'none';
                        } else {
                            fotoEl.style.display  = 'none';
                            noFotoEl.style.display = 'block';
                        }

                        // Link detail
                        document.getElementById('btn-lihat-detail').href =
                            detailUrl.replace(':id', data.id);

                        $('#modal-konfirmasi-barang').modal('show');
                    })
                    .catch(err => {
                        alert('Gagal memuat data: ' + err.message);
                    });
            },
            function (error) {
                // scan error diabaikan
            }
        ).catch(err => {
            document.getElementById('qr-scan-status').textContent = 'Kamera tidak bisa diakses: ' + err;
        });
    });

    $('#modal-scanner').on('hidden.bs.modal', function () {
        if (html5QrCode) {
            html5QrCode.stop().catch(() => {});
        }
    });

    // ========== TAMBAHKAN KE FORM ==========
    document.getElementById('btn-tambah-ke-form').addEventListener('click', function () {
        if (!scannedBarang) return;

        // Cari row per item yang kosong (belum pilih barang)
        let targetRow = null;
        document.querySelectorAll('.item-row').forEach(row => {
            if (targetRow) return;
            const tipeChecked = row.querySelector('.tipe-radio:checked')?.value;
            const barangId    = row.querySelector('.select-barang')?.value;
            if (tipeChecked === 'per_item' && !barangId) {
                targetRow = row;
            }
        });

        // Kalau tidak ada row per item kosong, buat row baru
        if (!targetRow) {
            document.getElementById('item-container')
                    .insertAdjacentHTML('beforeend', buildItemRow(itemIndex));
            itemIndex++;
            updateRemoveButtons();
            const rows = document.querySelectorAll('.item-row');
            targetRow  = rows[rows.length - 1];
        }

        // Switch ke tipe per_item
        const radioPerItem       = targetRow.querySelector('.tipe-radio[value="per_item"]');
        radioPerItem.checked     = true;
        targetRow.querySelector('.section-paket-service').style.display = 'none';
        targetRow.querySelector('.section-per-item').style.display      = 'block';

        // Isi select barang
        const selectBarang   = targetRow.querySelector('.select-barang');
        selectBarang.value   = scannedBarang.id;

        // Isi field lainnya
        targetRow.querySelector('.input-harga-satuan').value = scannedBarang.harga_jual;
        targetRow.querySelector('.input-satuan').value       = scannedBarang.satuan;
        targetRow.querySelector('.input-qty').value          = '';

        $('#modal-konfirmasi-barang').modal('hide');
        scannedBarang = null;
    });
</script>
@stop

@stop