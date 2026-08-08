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
                            <!-- DATA KOLOM KATEGORI BARU -->
                            <td class="align-middle">
                                @php
                                    // Ambil nilai kategori langsung, tangani jika berupa array atau string
                                    $catValue = is_array($item->kategori_buku)
                                        ? $item->kategori_buku[0] ?? null
                                        : $item->kategori_buku;

                                    // Jika masih kosong, coba cek apakah ada nama kolom lain (misal: category, kategori, dll)
                                    // Atau berikan fallback teks mentahnya langsung dari database

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
                                <span class="badge badge-stok-total">{{ $item->total_stok }} Total</span>
                                @if ($item->stok_tersedia > 0)
                                    <span class="badge badge-stok-ready">{{ $item->stok_tersedia }} Ready</span>
                                @else
                                    <span class="badge badge-stok-habis"><i class="bi bi-x-circle"></i> Habis</span>
                                @endif
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
                                        onclick="showDetail('{{ $item->judul }}', '{{ $item->penulis }}', '{{ $item->penerbit ?? '-' }}', '{{ $item->tahun_terbit }}', '{{ strtoupper($item->asal_buku) }}', '{{ $item->total_stok }}', '{{ $item->stok_tersedia }}', '{{ $item->foto ? asset('storage/' . $item->foto) : '' }}','{{ addslashes($item->rack->name ?? 'Belum Diatur') }}')">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2"
                                        data-bs-toggle="modal" data-bs-target="#editBookModal{{ $item->id }}">
                                        <i class="bi bi-pencil-square"></i>
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

                        <div class="modal fade" id="editBookModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content shadow-lg">
                                    <form action="{{ route('books.update', $item->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square"></i> Edit Data
                                                Buku</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start text-dark">
                                            <div class="mb-3">
                                                <label class="form-label">Judul Buku</label>
                                                <input type="text" name="judul" class="form-control"
                                                    value="{{ $item->judul }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kategori Buku</label>

                                                @php
                                                    $categories = [
                                                        'Buku Pelajaran' => 'Buku Pelajaran / Paket Sekolah',
                                                        'Buku Cerita / Novel' => 'Buku Cerita / Dongeng / Novel',
                                                        'Ensiklopedia / Pengetahuan' =>
                                                            'Buku Pengetahuan Umum / Ensiklopedia',
                                                        'Keagamaan' => 'Buku Keagamaan',
                                                        'Majalah / Komik Anak' => 'Majalah / Komik Anak',
                                                    ];

                                                    // Ambil nilai kategori yang lama dari database (bisa berupa string/array)
                                                    $currentCategory = is_array($item->kategori_buku)
                                                        ? $item->kategori_buku[0] ?? ''
                                                        : $item->kategori_buku;

                                                    $selectedCategory = old('kategori_buku', $currentCategory);
                                                @endphp

                                                <!-- Menggunakan Dropdown Select agar hanya bisa pilih 1 -->
                                                <select name="kategori_buku" class="form-select" required>
                                                    <option value="" disabled selected>-- Pilih Kategori Buku --
                                                    </option>
                                                    @foreach ($categories as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ $selectedCategory == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Penulis</label>
                                                <input type="text" name="penulis" class="form-control"
                                                    value="{{ $item->penulis }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Jumlah Eksemplar (Total Stok)</label>
                                                    <input type="number" name="jumlah" class="form-control"
                                                        value="{{ $item->total_stok }}" min="1" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Tahun Terbit</label>
                                                    <input type="number" name="tahun_terbit" class="form-control"
                                                        value="{{ $item->tahun_terbit }}" required>
                                                </div>
                                            </div>

                                            <!-- EDIT POSISI / RAK BUKU -->
                                            <div class="mb-3">
                                                <label class="form-label">Lokasi Rak Buku</label>
                                                <select name="rack_id" class="form-select" required>
                                                    @foreach ($raks as $rak)
                                                        <option value="{{ $rak->id }}"
                                                            {{ old('rack_id', $item->rack_id) == $rak->id ? 'selected' : '' }}>
                                                            {{ $rak->name }} ({{ $rak->code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Penerbit</label>
                                                <input type="text" name="penerbit" class="form-control"
                                                    value="{{ $item->penerbit ?? '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Asal Buku</label>
                                                <select name="asal_buku" class="form-select" required>
                                                    <option value="pengadaan"
                                                        {{ $item->asal_buku == 'pengadaan' ? 'selected' : '' }}>Pengadaan
                                                        Sekolah</option>
                                                    <option value="pembelian_dana_bos"
                                                        {{ $item->asal_buku == 'pembelian_dana_bos' ? 'selected' : '' }}>
                                                        Pembelian Dana Bos</option>
                                                    {{-- <option value="hibah"
                                                        {{ $item->asal_buku == 'hibah' ? 'selected' : '' }}>Hibah
                                                        Siswa/Alumni</option> --}}
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ganti Foto Sampul Buku</label>
                                                <input type="file" name="foto" class="form-control"
                                                    accept="image/*">
                                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah sampul.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-brand-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning fw-bold text-dark">Simpan
                                                Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addBookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Input Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" required
                                placeholder="Contoh: Buku Pintar Matematika">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori Buku</label>

                            @php
                                $categories = [
                                    'Buku Pelajaran' => 'Buku Pelajaran / Paket Sekolah',
                                    'Buku Cerita / Novel' => 'Buku Cerita / Dongeng / Novel',
                                    'Ensiklopedia / Pengetahuan' => 'Buku Pengetahuan Umum / Ensiklopedia',
                                    'Keagamaan' => 'Buku Keagamaan',
                                    'Majalah / Komik Anak' => 'Majalah / Komik Anak',
                                ];

                                // Ambil nilai kategori yang lama dari database (bisa berupa string/array)
                                $currentCategory = is_array($item->kategori_buku)
                                    ? $item->kategori_buku[0] ?? ''
                                    : $item->kategori_buku;

                                $selectedCategory = old('kategori_buku', $currentCategory);
                            @endphp

                            <!-- Menggunakan Dropdown Select agar hanya bisa pilih 1 -->
                            <select name="kategori_buku" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kategori Buku --</option>
                                @foreach ($categories as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ $selectedCategory == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="penulis" class="form-control" required
                                placeholder="Nama Penulis">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Eksemplar</label>
                                <input type="number" name="jumlah" class="form-control" value="1" min="1"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" required
                                    placeholder="2024">
                            </div>
                        </div>
                        <!-- INPUT POSISI / RAK BUKU -->
                        <div class="mb-3">
                            <label class="form-label">Lokasi Rak Buku</label>
                            <select name="rack_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Rak Buku --</option>
                                @foreach ($raks as $rak)
                                    <option value="{{ $rak->id }}">{{ $rak->name }} ({{ $rak->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control"
                                placeholder="Nama Penerbit (Opsional)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asal Buku</label>
                            <select name="asal_buku" class="form-select" required>
                                <option value="pengadaan">Pengadaan Sekolah</option>
                                <option value="pembelian_dana_bos">Pembelian Dana Bos</option>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Sampul Buku</label>
                            <input type="file" name="foto" class="form-control"
                                accept="image/png, image/jpeg, image/jpg">
                            <div class="form-text">Format: JPG, JPEG, PNG (Maks. 2MB). Opsional.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-brand-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand-primary">Simpan & Generate QR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="bi bi-book"></i> Detail & Inventaris Buku</h5>
                    <div class="ms-auto me-2">
                        <button type="button" class="btn btn-sm btn-success" onclick="printAllQR()">
                            <i class="bi bi-printer"></i> Cetak Semua QR
                        </button>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">

                    <!-- PANEL DETAIL INFORMASI BUKU -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img id="detailFoto" src="" alt="Sampul Buku"
                                class="img-fluid rounded shadow-sm border p-1"
                                style="max-height: 180px; object-fit: cover;">
                            <div id="detailNoFoto"
                                class="d-flex align-items-center justify-content-center bg-light border rounded text-muted mx-auto"
                                style="width: 120px; height: 160px; font-size: 12px;">No Image</div>
                        </div>
                        <div class="col-md-9 text-dark">
                            <h4 class="fw-bold mb-1" id="displayJudul"
                                style="font-family: 'Baloo 2', sans-serif; color: var(--teal-dark);"></h4>
                            <p class="text-muted mb-3" id="displayPenulisPenerbit"></p>

                            <div class="row g-2">
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">Tahun Terbit</small>
                                    <strong id="displayTahun">-</strong>
                                </div>

                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Lokasi Rak</small> <!-- TAMBAHAN -->
                                    <strong id="displayRak" class="text-teal">-</strong>
                                </div>
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">Asal Buku</small>
                                    <strong id="displayAsal">-</strong>
                                </div>
                                <div class="col-12 col-md-4">
                                    <small class="text-muted d-block">Status Stok</small>
                                    <span class="badge bg-dark" id="displayStokTotal">0 Total</span>
                                    <span class="badge bg-success" id="displayStokReady">0 Ready</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JUDUL SECTION TABEL QR -->
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-qr-code-scan text-teal"></i> Daftar Eksemplar & QR
                        Code</h6>

                    <!-- TABEL QR CODE -->
                    <div class="table-responsive"
                        style="max-height: 300px; border-radius: 8px; border: 1px solid var(--line);">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark text-center" style="position: sticky; top: 0; z-index: 1;">
                                <tr>
                                    <th style="background: var(--teal);">No</th>
                                    <th style="background: var(--teal);">Kode QR / ID Fisik</th>
                                    <th style="background: var(--teal);">Status</th>
                                    <th style="background: var(--teal);">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detailBody"></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="qrZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                    <h6 class="mb-0 fw-bold" id="zoomKodeLabel">KODE QR</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body p-4 text-center">
                    <img id="zoomQrImage" src="" alt="Zoom QR" class="img-fluid" style="max-width: 300px;">
                    <hr>
                    <p class="text-muted small">Klik tutup untuk kembali.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
    </script>
@endsection
