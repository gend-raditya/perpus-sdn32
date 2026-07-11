@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Koleksi Buku</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
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
                @foreach ($books->pluck('tahun_terbit')->unique()->sortDesc() as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table id="bookTable" class="table table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th style="width: 80px;">Sampul</th>
                        <th>Judul Buku</th>
                        <th>Penulis / Penerbit</th>
                        <th>Stok Fisik</th>
                        <th>Asal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $key => $item)
                        <tr>
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
                            <td class="align-middle">{{ $item->penulis }} <br> <small
                                    class="text-muted">{{ $item->penerbit ?? '-' }}</small></td>
                            <td class="align-middle">
                                <span class="badge bg-dark">{{ $item->total_stok }} Total</span>
                                @if ($item->stok_tersedia > 0)
                                    <span class="badge bg-success">{{ $item->stok_tersedia }} Ready</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Habis</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ strtoupper($item->asal_buku) }}</td>
                            <td class="align-middle">
                                <button type="button" class="btn btn-sm btn-info text-white"
                                    onclick="showDetail('{{ $item->judul }}')">
                                    <i class="bi bi-eye"></i> Detail QR
                                </button>
                                <span class="badge bg-light text-secondary border px-2 py-1.5 small text-nowrap user-select-none">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $item->tahun_terbit }}
                                </span>
                            </td>
                        </tr>
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
                            <label class="form-label">Penulis</label>
                            <input type="text" name="penulis" class="form-control" required placeholder="Nama Penulis">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Eksemplar</label>
                                <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" required placeholder="2024">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" placeholder="Nama Penerbit (Opsional)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asal Buku</label>
                            <select name="asal_buku" class="form-select" required>
                                <option value="pengadaan">Pengadaan Sekolah</option>
                                <option value="hibah">Hibah Siswa/Alumni</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Sampul Buku</label>
                            <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                            <div class="form-text">Format: JPG, JPEG, PNG (Maks. 2MB). Opsional.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan & Generate QR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Detail Eksemplar: <span id="displayJudul"></span></h5>
                    <div class="ms-auto me-2">
                        <button type="button" class="btn btn-sm btn-success" onclick="printAllQR()">
                            <i class="bi bi-printer"></i> Cetak Semua QR
                        </button>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Kode QR / ID Fisik</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
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
        // SATU DOMContentLoaded UTAMA UNTUK DATATABLES DAN FILTER
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi DataTables dengan layouting bootstrap pendukung flexbox
            const table = $('#bookTable').DataTable({
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end align-items-center gap-2'f>>" +
                       "<'row'<'col-sm-12'tr>>" +
                       "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                "language": {
                    "search": "" // Hilangkan teks "Search:" bawaan agar hemat space
                }
            });

            // Beri placeholder ke search box bawaan DataTables
            $('.dataTables_filter input').attr('placeholder', 'Cari data...');

            // Seleksi elemen filter tahun dan container filter bawaan DataTables
            const filterContainer = document.getElementById('filterTahunWrapper');
            const dtFilter = document.querySelector('.dataTables_filter');

            // Pindahkan posisi dropdown ke samping kolom search
            if (dtFilter && filterContainer) {
                filterContainer.classList.remove('d-none');
                dtFilter.classList.add('d-flex', 'align-items-center', 'gap-2');
                dtFilter.insertBefore(filterContainer, dtFilter.firstChild);
            }

            // Aksi filter kolom ketika select option berubah nilai
            document.getElementById('filterTahun').addEventListener('change', function() {
                const val = this.value;
                // Index 6 mengacu pada kolom ke-7 (Kolom Aksi tempat teks tahun berada)
                table.column(6).search(val ? val : '', true, false).draw();
            });
        });

        // BERSURUT-TURUT FUNCTION LAIN UTUH TANPA PERUBAHAN
        function showDetail(judul) {
            document.getElementById('displayJudul').innerText = judul;
            document.getElementById('detailBody').innerHTML = '<tr><td colspan="4" class="text-center">Memuat...</td></tr>';

            var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
            myModal.show();

            fetch(`/books/detail-json?judul=${encodeURIComponent(judul)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    data.forEach((book, index) => {
                        let qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${book.kode_qr}`;
                        html += `
                            <tr>
                                <td class="text-center align-middle">${index + 1}</td>
                                <td class="align-middle">
                                    <img src="${qrUrl}" class="border p-1 me-2" style="width: 40px; cursor:pointer" onclick="zoomQR('${qrUrl}', '${book.kode_qr}')">
                                    <strong>${book.kode_qr}</strong>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge ${book.status === 'tersedia' ? 'bg-success' : 'bg-warning'}">${book.status.toUpperCase()}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-sm btn-outline-dark" onclick="printSingleQR('${book.kode_qr}')"><i class="bi bi-printer"></i></button>
                                </td>
                            </tr>`;
                    });
                    document.getElementById('detailBody').innerHTML = html;
                });
        }

        function zoomQR(url, kode) {
            document.getElementById('zoomQrImage').src = url;
            document.getElementById('zoomKodeLabel').innerText = 'KODE QR: ' + kode;
            var zoomModal = new bootstrap.Modal(document.getElementById('qrZoomModal'));
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
            rows.forEach(row => {
                const kode = row.querySelector('strong').innerText;
                content += `<div style="text-align:center; border:1px solid #ddd; padding:5px;"><img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=${kode}"><br><small>${kode}</small></div>`;
            });
            content += '</div><script>window.onload=function(){window.print();}<\/script>';
            printWindow.document.write(content);
            printWindow.document.close();
        }
    </script>
@endsection
