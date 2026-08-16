@extends('layouts.app')

@section('content')
    <style>
        /* Tema halaman ini mengikuti palet & font yang sudah didefinisikan di layouts.app */
        .page-title-block h1 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--ink);
        }

        .btn-brand-primary {
            background: var(--teal);
            border: none;
            color: var(--paper);
            font-weight: 700;
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 3px 3px 0 var(--teal-dark);
            transition: all .2s ease;
        }

        .btn-brand-primary:hover {
            color: var(--paper);
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0 var(--teal-dark);
        }

        #filterTahunWrapper .input-group {
            border: 1.5px dashed var(--line) !important;
            border-radius: 999px !important;
            overflow: hidden;
        }

        #filterTahunWrapper .input-group-text {
            background: var(--paper-alt) !important;
            color: var(--teal) !important;
        }

        #filterTahunWrapper select {
            background: #fff;
        }

        .card-collection {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0px 14px 30px -14px rgba(30, 42, 34, 0.18);
        }

        #bookTable thead th {
            background: var(--teal);
            color: var(--paper);
            font-family: 'Baloo 2', sans-serif;
            font-weight: 600;
            font-size: .85rem;
            border: none;
            white-space: nowrap;
        }

        #bookTable thead th:first-child {
            border-top-left-radius: 10px;
        }

        #bookTable thead th:last-child {
            border-top-right-radius: 10px;
        }

        #bookTable tbody tr:hover {
            background: var(--teal-light);
        }

        #bookTable img.img-thumbnail {
            border: 1.5px solid var(--line) !important;
            border-radius: 8px;
        }

        .badge-stok-total {
            background: var(--ink) !important;
            border-radius: 999px;
            font-weight: 600;
        }

        .badge-stok-ready {
            background: var(--sage) !important;
            border-radius: 999px;
            font-weight: 600;
        }

        .badge-stok-habis {
            background: var(--berry) !important;
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-detail-qr {
            background: transparent;
            border: 1.5px solid var(--teal);
            color: var(--teal);
            border-radius: 999px;
            font-weight: 700;
            transition: all .2s ease;
        }

        .btn-detail-qr:hover {
            background: var(--teal);
            color: var(--paper);
        }

        .badge-tahun {
            background: var(--paper-alt) !important;
            border: 1.5px dashed var(--gold) !important;
            color: var(--teal-dark) !important;
            border-radius: 999px;
        }

        /* Modal styling */
        .modal-content {
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .modal-header {
            background: var(--teal);
            color: var(--paper);
            border-bottom: none;
        }

        .modal-header .modal-title {
            font-family: 'Baloo 2', sans-serif;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body .form-label {
            font-weight: 600;
            color: var(--ink-soft);
            font-size: .88rem;
        }

        .modal-body .form-control,
        .modal-body .form-select {
            border: 1.5px solid var(--line);
            border-radius: 10px;
        }

        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 .2rem var(--teal-light);
        }

        .modal-footer {
            border-top: 1.5px dashed var(--line);
        }

        .btn-brand-secondary {
            background: transparent;
            border: 1.5px solid var(--ink-soft);
            color: var(--ink-soft);
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-brand-secondary:hover {
            background: var(--ink-soft);
            color: var(--paper);
        }

        #detailModal .table-dark {
            --bs-table-bg: var(--teal-dark);
        }

        #detailModal .modal-header.bg-light {
            background: var(--paper-alt) !important;
            color: var(--ink);
        }

        #qrZoomModal .card-header {
            border-bottom: 1.5px dashed var(--line);
        }
    </style>

    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom page-title-block">
        <h1 class="h2">Manajemen Koleksi Buku</h1>
        <button type="button" class="btn btn-brand-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
            <i class="bi bi-plus-circle"></i> Tambah Buku Baru
        </button>
    </div>

    <div id="filterTahunWrapper" class="d-none">
        <div class="input-group input-group-sm border rounded-3" style="width: 180px;">
            <span class="input-group-text bg-light text-muted border-0 py-0">
                <i class="bi bi-filter-square-fill"></i>
            </span>
            <select id="filterTahun" class="form-select border-0 shadow-none fw-semibold text-dark py-1">
                <option value="">Semua Tahun</option>
                @foreach ($listTahun as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card card-collection">
        <div class="card-body">
            <table id="bookTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width: 80px;">Sampul</th>
                        <th>Judul Buku</th>
                        <th>ISBN</th>
                        <th>Kategori</th>
                        <th>Lokasi Rak</th>
                        <th>Stok Fisik</th>
                        <th>Asal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $key => $item)
                        <tr data-tahun="{{ $item->tahun_terbit }}">
                            <td class="align-middle">{{ $key + 1 }}</td>
                            <td class="align-middle">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Sampul {{ $item->judul }}"
                                        class="img-thumbnail shadow-sm"
                                        style="width: 60px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light border rounded text-muted shadow-sm"
                                        style="width: 60px; height: 80px; font-size: 11px; text-align: center;">
                                        No Image
                                    </div>
                                @endif
                            </td>

                            <td class="align-middle"><strong>{{ $item->judul }}</strong></td>

                            <td class="align-middle">{{ $item->isbn ?? '-' }}</td>

                            <td class="align-middle">
                                @php
                                    $catValue = is_array($item->kategori_buku)
                                        ? $item->kategori_buku[0] ?? null
                                        : $item->kategori_buku;
                                @endphp

                                @if (!empty($catValue))
                                    {{ $catValue }}
                                @else
                                    <span class="text-muted" style="font-size: 11px;">Belum diatur</span>
                                @endif
                            </td>

                            <td class="align-middle">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    {{ $item->rack->name ?? 'Belum Diatur' }}
                                </span>
                            </td>

                            <td class="align-middle">
                                <div class="stock-control d-flex align-items-center gap-2">
                                    <div class="stock-display" data-judul="{{ addslashes($item->judul) }}"
                                        data-penulis="{{ addslashes($item->penulis) }}"
                                        data-penerbit="{{ addslashes($item->penerbit ?? '-') }}"
                                        data-tahun="{{ $item->tahun_terbit }}" data-isbn="{{ $item->isbn }}">
                                        <span class="badge badge-stok-total">{{ $item->total_stok }} Total</span>
                                        @if ($item->stok_tersedia > 0)
                                            <span class="badge badge-stok-ready">{{ $item->stok_tersedia }} Ready</span>
                                        @else
                                            <span class="badge badge-stok-habis"><i class="bi bi-x-circle"></i> Habis</span>
                                        @endif
                                    </div>
                                    <button class="btn btn-sm btn-outline-secondary btn-edit-stock" type="button"
                                        onclick="(typeof startEditStock === 'function' ? startEditStock(this) : (typeof startEditStockFallback === 'function' ? startEditStockFallback(this) : alert('Fungsi edit stok tidak tersedia')))"><i
                                            class="bi bi-pencil"></i></button>
                                </div>
                            </td>

                            <td class="align-middle">
                                @if ($item->asal_buku == 'pembelian_dana_bos')
                                    <span style="font-weight: 600;">Pembelian Dana BOS</span>
                                @elseif($item->asal_buku == 'pengadaan')
                                    <span style="font-weight: 600;">Pengadaan Sekolah</span>
                                @else
                                    <span style="font-weight: 600;">Hibah</span>
                                @endif
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <button type="button" class="btn btn-sm btn-detail-qr text-nowrap"
                                        onclick="showDetail('{{ addslashes($item->judul) }}', '{{ addslashes($item->penulis) }}', '{{ addslashes($item->penerbit ?? '-') }}', '{{ $item->tahun_terbit }}', '{{ strtoupper($item->asal_buku) }}', '{{ $item->total_stok }}', '{{ $item->stok_tersedia }}', '{{ $item->foto ? asset('storage/' . $item->foto) : '' }}', '{{ addslashes($item->rack->name ?? 'Belum Diatur') }}')">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2"
                                        data-bs-toggle="modal" data-bs-target="#editBookModal{{ $item->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2"
                                        title="Riwayat Stok" onclick="showStockHistory(this)"
                                        data-judul="{{ addslashes($item->judul) }}" data-tahun="{{ $item->tahun_terbit }}"
                                        data-isbn="{{ $item->isbn }}">
                                        <i class="bi bi-clock-history"></i>
                                    </button>

                                    <form action="{{ route('books.destroy', $item->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus seluruh koleksi buku ini beserta semua QR-nya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <span class="badge badge-tahun px-2 py-1.5 small text-nowrap user-select-none">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $item->tahun_terbit }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Buku -->
    @foreach ($books as $item)
        @include('books.partials._edit_book_modal', ['item' => $item, 'raks' => $raks])
    @endforeach

    <!-- Modal Tambah Buku -->
    @include('books.partials._add_book_modal', ['raks' => $raks])

    <!-- Modal Detail -->
    @include('books.partials._detail_modal')
    @include('books.partials._stock_history_modal')

    <script>
        window.addEventListener('load', function() {
            // Ekstensi kustom DataTables untuk menyaring baris berdasarkan atribut data-tahun
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var selectedYear = $('#filterTahun').val();
                    var row = settings.aoData[dataIndex].nTr;
                    var rowYear = $(row).attr('data-tahun');

                    if (!selectedYear || selectedYear === "") {
                        return true;
                    }

                    return rowYear === selectedYear;
                }
            );

            // Inisialisasi DataTables
            const table = $('#bookTable').DataTable({
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end align-items-center gap-2'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                "language": {
                    "search": ""
                }
            });

            $('.dataTables_filter input').attr('placeholder', 'Cari data...');

            const filterContainer = document.getElementById('filterTahunWrapper');
            const dtFilter = document.querySelector('.dataTables_filter');

            if (dtFilter && filterContainer) {
                filterContainer.classList.remove('d-none');
                dtFilter.classList.add('d-flex', 'align-items-center', 'gap-2');
                dtFilter.insertBefore(filterContainer, dtFilter.firstChild);
            }

            const filterTahunEl = document.getElementById('filterTahun');
            if (filterTahunEl) {
                filterTahunEl.addEventListener('change', function() {
                    // Refresh/redraw tabel saat dropdown tahun diganti
                    table.draw();
                });
            }

            // Pastikan elemen modal berada langsung di bawah <body>
            // ini mencegah modal ter-clipped oleh container dengan overflow/transform
            ['detailModal', 'qrZoomModal', 'addBookModal', 'stockHistoryModal'].forEach(id => {
                const el = document.getElementById(id);
                if (el && el.parentElement !== document.body) {
                    document.body.appendChild(el);
                }
            });
        });

        function showDetail(judul, penulis, penerbit, tahun, asal, totalStok, stokTersedia, fotoUrl, rak = '-') {
            // 1. Set informasi teks detail buku ke Modal
            document.getElementById('displayJudul').innerText = judul;
            document.getElementById('displayPenulisPenerbit').innerText = `Oleh: ${penulis} | Penerbit: ${penerbit}`;
            document.getElementById('displayTahun').innerText = tahun;
            document.getElementById('displayAsal').innerText = asal;
            document.getElementById('displayStokTotal').innerText = `${totalStok} Total`;
            document.getElementById('displayStokReady').innerText = `${stokTersedia} Ready`;

            // Isi lokasi rak jika elemen ada
            const displayRakEl = document.getElementById('displayRak');
            if (displayRakEl) {
                displayRakEl.innerText = rak;
            }

            // 2. Atur tampilan foto sampul
            const imgEl = document.getElementById('detailFoto');
            const noImgEl = document.getElementById('detailNoFoto');
            if (fotoUrl) {
                imgEl.src = fotoUrl;
                imgEl.classList.remove('d-none');
                noImgEl.classList.add('d-none');
            } else {
                imgEl.classList.add('d-none');
                noImgEl.classList.remove('d-none');
            }

            // 3. Reset isi tabel QR dan tampilkan modal
            document.getElementById('detailBody').innerHTML =
                '<tr><td colspan="4" class="text-center">Memuat data eksemplar...</td></tr>';

            const modalEl = document.getElementById('detailModal');
            const myModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            myModal.show();

            // 4. Tarik data QR dari server via JSON
            fetch(`/books/detail-json?judul=${encodeURIComponent(judul)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="4" class="text-center">Tidak ada eksemplar ditemukan.</td></tr>';
                    } else {
                        data.forEach((book, index) => {
                            let qrUrl =
                                `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${book.kode_qr}`;
                            html += `
                    <tr>
                        <td class="text-center align-middle">${index + 1}</td>
                        <td class="align-middle">
                            <img src="${qrUrl}" class="border p-1 me-2 rounded" style="width: 40px; cursor:pointer" onclick="zoomQR('${qrUrl}', '${book.kode_qr}')">
                            <strong>${book.kode_qr}</strong>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge ${book.status === 'tersedia' ? 'bg-success' : 'bg-warning'}">${book.status ? book.status.toUpperCase() : '-'}</span>
                        </td>
                        <td class="text-center align-middle">
                            <button class="btn btn-sm btn-outline-dark" onclick="printSingleQR('${book.kode_qr}')"><i class="bi bi-printer"></i></button>
                        </td>
                    </tr>`;
                        });
                    }
                    document.getElementById('detailBody').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching detail:', error);
                    document.getElementById('detailBody').innerHTML =
                        '<tr><td colspan="4" class="text-center text-danger">Gagal memuat data eksemplar.</td></tr>';
                });
        }

        function zoomQR(url, kode) {
            document.getElementById('zoomQrImage').src = url;
            document.getElementById('zoomKodeLabel').innerText = 'KODE QR: ' + kode;

            const zoomModalEl = document.getElementById('qrZoomModal');
            const zoomModal = bootstrap.Modal.getOrCreateInstance(zoomModalEl);
            zoomModal.show();
        }

        function printSingleQR(kode) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(
                `<div style="text-align:center"><img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${kode}"><br><b>${kode}</b></div><script>window.onload=function(){window.print();window.close();}<\/script>`
            );
            printWindow.document.close();
        }

        function printAllQR() {
            const rows = document.querySelectorAll('#detailBody tr');
            const printWindow = window.open('', '_blank');
            let content = '<div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:10px;">';

            let hasData = false;
            rows.forEach(row => {
                const strongEl = row.querySelector('strong');
                if (strongEl) {
                    hasData = true;
                    const kode = strongEl.innerText.trim();
                    content +=
                        `<div style="text-align:center; border:1px solid #ddd; padding:5px;"><img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=${kode}"><br><small>${kode}</small></div>`;
                }
            });

            if (!hasData) {
                printWindow.close();
                alert('Tidak ada data QR yang bisa dicetak.');
                return;
            }

            content += '</div><script>window.onload=function(){window.print();}<\/script>';
            printWindow.document.write(content);
            printWindow.document.close();
        }

        // ---------------------------
        // Inline stock edit handlers
        // ---------------------------
        function startEditStock(btn) {
            const wrapper = btn.closest('.stock-control');
            const display = wrapper.querySelector('.stock-display');
            const currentText = display.querySelector('.badge-stok-total').innerText || '';
            const currentNumber = parseInt(currentText.replace(/[^0-9]/g, '')) || 0;

            const editHtml = `
                <div class="stock-edit d-flex align-items-center gap-2">
                    <input type="number" min="0" class="form-control form-control-sm stock-input" value="${currentNumber}" style="width:100px">
                    <button class="btn btn-sm btn-success" onclick="submitStockEdit(this)">Simpan</button>
                    <button class="btn btn-sm btn-secondary" onclick="cancelEditStock(this)">Batal</button>
                </div>`;

            display.style.display = 'none';
            wrapper.insertAdjacentHTML('beforeend', editHtml);
            btn.disabled = true;
        }

        function cancelEditStock(btn) {
            const wrapper = btn.closest('.stock-control');
            const editEl = wrapper.querySelector('.stock-edit');
            const display = wrapper.querySelector('.stock-display');
            const editBtn = wrapper.querySelector('.btn-edit-stock');
            if (editEl) editEl.remove();
            if (display) display.style.display = '';
            if (editBtn) editBtn.disabled = false;
        }

        function submitStockEdit(btn) {
            const wrapper = btn.closest('.stock-control');
            const input = wrapper.querySelector('.stock-input');
            const display = wrapper.querySelector('.stock-display');
            const editBtn = wrapper.querySelector('.btn-edit-stock');
            const newTotal = parseInt(input.value);
            if (isNaN(newTotal) || newTotal < 0) {
                alert('Masukkan nilai stok yang valid (>=0)');
                return;
            }

            const judul = display.getAttribute('data-judul');
            const penulis = display.getAttribute('data-penulis');
            const penerbit = display.getAttribute('data-penerbit');
            const tahun = display.getAttribute('data-tahun');
            const isbn = display.getAttribute('data-isbn');

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.getAttribute('content') : null;

            fetch("/books/update-stock-group", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        judul: judul,
                        penulis: penulis,
                        penerbit: penerbit,
                        tahun_terbit: tahun,
                        isbn: isbn,
                        new_total: newTotal
                    })
                })
                .then(async (r) => {
                    const text = await r.text();
                    let res = null;
                    try {
                        res = text ? JSON.parse(text) : null;
                    } catch (e) {
                        res = null;
                    }

                    if (!r.ok) {
                        throw new Error((res && res.message) || 'Gagal memperbarui stok');
                    }

                    return res;
                })
                .then(res => {
                    if (res && res.status && res.status === 'ok') {
                        const totalBadge = display.querySelector('.badge-stok-total');
                        if (totalBadge) totalBadge.innerText = newTotal + ' Total';
                        const editEl = wrapper.querySelector('.stock-edit');
                        if (editEl) editEl.remove();
                        if (display) display.style.display = '';
                        if (editBtn) editBtn.disabled = false;
                        alert(res.message || 'Stok berhasil diperbarui');
                    } else {
                        alert((res && res.message) || 'Gagal memperbarui stok');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert(err.message || 'Terjadi kesalahan saat memperbarui stok');
                });
        }
    </script>

    <script>
        // Stock history fetcher & small UI helper
        function showStockHistory(btn) {
            const judul = btn.getAttribute('data-judul');
            const tahun = btn.getAttribute('data-tahun');
            const isbn = btn.getAttribute('data-isbn');

            const modalEl = document.getElementById('stockHistoryModal');
            if (!modalEl) return;

            const table = modalEl.querySelector('#stockHistoryTable');
            const loading = modalEl.querySelector('#stockHistoryLoading');
            const tbody = table.querySelector('tbody');

            tbody.innerHTML = '';
            table.classList.add('d-none');
            loading.classList.remove('d-none');

            const params = new URLSearchParams({
                judul: judul
            });
            if (tahun) params.append('tahun_terbit', tahun);
            if (isbn) params.append('isbn', isbn);

            fetch('/books/stock-history?' + params.toString())
                .then(r => r.json())
                .then(data => {
                    loading.classList.add('d-none');
                    if (data.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="3" class="text-center small text-muted">Belum ada riwayat perubahan stok.</td></tr>';
                    } else {
                        tbody.innerHTML = '';
                        data.forEach(row => {
                            const tanggal = new Date(row.created_at).toLocaleString();
                            tbody.innerHTML +=
                                `<tr><td>${tanggal}</td><td>${row.previous_total}</td><td>${row.new_total}</td></tr>`;
                        });
                    }
                    table.classList.remove('d-none');
                })
                .catch(err => {
                    loading.classList.add('d-none');
                    tbody.innerHTML =
                        '<tr><td colspan="3" class="text-center text-danger small">Gagal memuat riwayat.</td></tr>';
                    table.classList.remove('d-none');
                    console.error(err);
                });

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    </script>

    <script>
        // Override / safe implementations for inline stock edit (placed last to ensure availability)
        (function() {
            function startEditStockSafe(btn) {
                const wrapper = btn.closest('.stock-control');
                const display = wrapper.querySelector('.stock-display');
                if (!display) return;
                const totalBadge = display.querySelector('.badge-stok-total');
                const currentText = totalBadge ? totalBadge.innerText : '';
                const currentNumber = parseInt((currentText || '').replace(/[^0-9]/g, '')) || 0;

                if (wrapper.querySelector('.stock-edit')) return;

                const editEl = document.createElement('div');
                editEl.className = 'stock-edit d-flex align-items-center gap-2';

                const input = document.createElement('input');
                input.type = 'number';
                input.min = 0;
                input.className = 'form-control form-control-sm stock-input';
                input.style.width = '100px';
                input.value = currentNumber;

                const saveBtn = document.createElement('button');
                saveBtn.className = 'btn btn-sm btn-success';
                saveBtn.type = 'button';
                saveBtn.textContent = 'Simpan';
                saveBtn.addEventListener('click', function() {
                    submitStockEditSafe(this);
                });

                const cancelBtn = document.createElement('button');
                cancelBtn.className = 'btn btn-sm btn-secondary';
                cancelBtn.type = 'button';
                cancelBtn.textContent = 'Batal';
                cancelBtn.addEventListener('click', function() {
                    cancelEditStockSafe(this);
                });

                editEl.appendChild(input);
                editEl.appendChild(saveBtn);
                editEl.appendChild(cancelBtn);
                display.style.display = 'none';
                wrapper.appendChild(editEl);

                const editTrigger = wrapper.querySelector('.btn-edit-stock');
                if (editTrigger) editTrigger.disabled = true;
            }

            function cancelEditStockSafe(btn) {
                const wrapper = btn.closest('.stock-control');
                const editEl = wrapper.querySelector('.stock-edit');
                const display = wrapper.querySelector('.stock-display');
                if (editEl) editEl.remove();
                if (display) display.style.display = '';
                const editTrigger = wrapper.querySelector('.btn-edit-stock');
                if (editTrigger) editTrigger.disabled = false;
            }

            function submitStockEditSafe(btn) {
                const wrapper = btn.closest('.stock-control');
                const input = wrapper.querySelector('.stock-input');
                const display = wrapper.querySelector('.stock-display');
                const editTrigger = wrapper.querySelector('.btn-edit-stock');
                if (!input || !display) return;
                const newTotal = parseInt(input.value);
                if (isNaN(newTotal) || newTotal < 0) {
                    alert('Masukkan nilai stok yang valid (>=0)');
                    return;
                }

                const judul = display.getAttribute('data-judul');
                const penulis = display.getAttribute('data-penulis');
                const penerbit = display.getAttribute('data-penerbit');
                const tahun = display.getAttribute('data-tahun');
                const isbn = display.getAttribute('data-isbn');

                const meta = document.querySelector('meta[name="csrf-token"]');
                const token = meta ? meta.getAttribute('content') : null;
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };
                if (token) headers['X-CSRF-TOKEN'] = token;

                fetch('/books/update-stock-group', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            judul: judul,
                            penulis: penulis,
                            penerbit: penerbit,
                            tahun_terbit: tahun,
                            isbn: isbn,
                            new_total: newTotal
                        })
                    })
                    .then(async (r) => {
                        const text = await r.text();
                        let res = null;
                        try {
                            res = text ? JSON.parse(text) : null;
                        } catch (e) {
                            res = null;
                        }

                        if (!r.ok) {
                            throw new Error((res && res.message) || 'Gagal memperbarui stok');
                        }

                        return res;
                    })
                    .then(res => {
                        if (res && res.status === 'ok') {
                            const totalBadge = display.querySelector('.badge-stok-total');
                            if (totalBadge) totalBadge.innerText = newTotal + ' Total';
                            const editEl = wrapper.querySelector('.stock-edit');
                            if (editEl) editEl.remove();
                            if (display) display.style.display = '';
                            if (editTrigger) editTrigger.disabled = false;
                            if (res.message) console.info(res.message);
                            alert(res.message || 'Stok berhasil diperbarui');
                        } else {
                            alert((res && res.message) || 'Gagal memperbarui stok');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert(err.message || 'Terjadi kesalahan saat memperbarui stok');
                    });
            }

            // expose safe names so onclick attributes find them
            window.startEditStock = startEditStockSafe;
            window.cancelEditStock = cancelEditStockSafe;
            window.submitStockEdit = submitStockEditSafe;
        })();
    </script>
@endsection
