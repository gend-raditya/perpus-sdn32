@extends('layouts.app')

@section('content')
    <style>
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

        .alert-success {
            background: rgba(110, 146, 104, 0.12) !important;
            border: 1.5px dashed var(--sage) !important;
            border-left: 5px solid var(--sage) !important;
            color: var(--ink) !important;
            border-radius: 14px !important;
        }

        .card-grants {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0px 14px 30px -14px rgba(30, 42, 34, 0.18);
        }

        .table-grants thead th {
            background: var(--teal);
            color: var(--paper);
            font-family: 'Baloo 2', sans-serif;
            font-weight: 600;
            font-size: .85rem;
            border: none;
            white-space: nowrap;
        }

        .table-grants thead th:first-child {
            border-top-left-radius: 10px;
        }

        .table-grants thead th:last-child {
            border-top-right-radius: 10px;
        }

        .table-grants tbody tr:hover {
            background: var(--teal-light);
        }

        .badge-eks {
            background: var(--paper-alt) !important;
            border: 1.5px dashed var(--gold) !important;
            color: var(--teal-dark) !important;
            border-radius: 999px;
            font-weight: 600;
        }

        .grant-info-compact {
            min-width: 260px;
            max-width: 360px;
        }

        .grant-info-wrap {
            min-width: 0;
            width: 100%;
        }

        .grant-info-title {
            display: block;
            max-width: 100%;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.3;
        }

        .grant-info-title.truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .grant-detail-btn {
            color: var(--teal);
            font-weight: 600;
            text-decoration: none;
            padding: 0;
            font-size: 0.74rem;
        }

        .grant-detail-btn:hover {
            text-decoration: underline;
        }

        .grant-detail-modal .modal-header {
            background: var(--teal);
            color: var(--paper);
        }

        .grant-detail-modal .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .grant-detail-card {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--paper-alt);
        }

        .grant-detail-img {
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--line);
        }

        .badge-status-pending {
            background: rgba(239, 165, 59, 0.15) !important;
            border: 1.5px dashed var(--gold) !important;
            color: #8a5c14 !important;
            border-radius: 999px;
            font-weight: 700;
        }

        .badge-status-approved {
            background: var(--sage) !important;
            color: #fff !important;
            border-radius: 999px;
            font-weight: 700;
        }

        .badge-status-rejected {
            background: var(--berry) !important;
            color: #fff !important;
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-approve {
            background: var(--teal);
            border: none;
            color: var(--paper);
            font-weight: 700;
            border-radius: 999px;
        }

        .btn-approve:hover {
            background: var(--teal-dark);
            color: var(--paper);
        }

        .btn-reject {
            background: transparent;
            border: 1.5px solid var(--berry);
            color: var(--berry);
            font-weight: 700;
            border-radius: 999px;
        }

        .btn-reject:hover {
            background: var(--berry);
            color: #fff;
        }

        .btn-done {
            background: var(--paper-alt);
            border: 1.5px solid var(--line);
            color: var(--ink-soft);
            font-weight: 600;
            border-radius: 999px;
            opacity: 1 !important;
        }

        /* Stylisatinc tambahan untuk Details & Summary Sinopsis */
        .sinopsis-details summary {
            cursor: pointer;
            font-size: 0.8rem;
            color: var(--teal);
            font-weight: 600;
            user-select: none;
        }

        .sinopsis-details summary:hover {
            text-decoration: underline;
        }

        .sinopsis-text {
            font-size: 0.8rem;
            background: var(--paper-alt, #f8f9fa);
            border: 1px solid var(--line, #e9ecef);
            padding: 8px 10px;
            border-radius: 8px;
            max-width: 280px;
            white-space: normal;
            word-wrap: break-word;
        }

        /* Pagination styling */
        .pagination-wrapper .pagination {
            margin-bottom: 0;
            gap: 5px;
        }

        .pagination-wrapper .page-item .page-link {
            border-radius: 8px;
            color: var(--teal);
            border-color: var(--line);
        }

        .pagination-wrapper .page-item.active .page-link {
            background-color: var(--teal);
            border-color: var(--teal);
            color: #fff;
        }
    </style>

    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom page-title-block">
        <h1 class="h2">Verifikasi Hibah Buku</h1>
        <a href="{{ route('grants.create') }}" class="btn btn-brand-primary">
            <i class="bi bi-plus-circle"></i> Catat Hibah Baru
        </a>
    </div>

    <div class="card card-grants">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-grants">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">ID Hibah</th>
                            <th scope="col">Donatur (Pemberi)</th>
                            <th scope="col">No. Telepon</th>
                            <th scope="col">Info Buku</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group grants by donor (use phone as key to avoid duplicate donors)
                            $grouped = $grants->groupBy(function($g) {
                                return ($g->kontak_pemberi ?? '') . '|' . ($g->nama_pemberi ?? '');
                            });
                        @endphp

                        @forelse($grouped as $groupKey => $items)
                            @php $first = $items->first(); $donorId = md5($groupKey); @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                        #{{ $first->id }}
                                    </span>
                                </td>

                                <td>
                                    <strong>{{ $first->nama_pemberi }}</strong>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">
                                        <i class="bi bi-telephone me-1 text-muted"></i>
                                        {{ $first->kontak_pemberi ?? ($first->no_hp ?? '-') }}
                                    </span>
                                </td>

                                <td>
                                    @php
                                        $fotoTampil = null;
                                        if (!empty($first->judul_buku)) {
                                            $matchingBook = \App\Models\Book::where('judul', $first->judul_buku)->first();
                                            if ($matchingBook && !empty($matchingBook->foto)) {
                                                $fotoTampil = $matchingBook->foto;
                                            }
                                        }
                                        if (empty($fotoTampil)) {
                                            $fotoTampil = $first->foto_buku;
                                        }
                                    @endphp

                                    <div class="grant-info-compact">
                                        <div class="d-flex align-items-start gap-2">
                                            @if ($fotoTampil)
                                                <img src="{{ asset('storage/' . $fotoTampil) }}"
                                                    alt="Sampul {{ $first->judul_buku ?? 'buku' }}"
                                                    class="img-thumbnail shadow-sm flex-shrink-0"
                                                    style="width: 52px; height: 72px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light border rounded text-muted shadow-sm flex-shrink-0"
                                                    style="width: 52px; height: 72px; font-size: 10px; text-align: center; line-height: 1.2;">
                                                    Tidak Ada<br>Foto
                                                </div>
                                            @endif

                                            <div class="grant-info-wrap">
                                                <span class="grant-info-title truncate">{{ $first->judul_buku ?? 'Judul belum diisi' }}</span>
                                                <div class="small text-muted mt-1">
                                                    @if ($items->count() > 1)
                                                        <span class="text-muted">+ {{ $items->count() - 1 }} judul lain</span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted mt-1">
                                                    {{ $items->sum('jumlah_eksemplar') }} eks • {{ !empty($first->kategori_buku) ? (is_array($first->kategori_buku) ? implode(', ', $first->kategori_buku) : $first->kategori_buku) : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @php
                                        $total = $items->count();
                                        $pending = $items->where('status_hibah', 'pending')->count();
                                        $approved = $items->where('status_hibah', 'disetujui')->count();
                                        $rejected = $items->where('status_hibah', 'ditolak')->count();
                                    @endphp

                                    @if ($pending == $total)
                                        <span class="badge badge-status-pending">Menunggu Verifikasi</span>
                                    @elseif ($approved == $total)
                                        <span class="badge badge-status-approved">Disetujui</span>
                                    @elseif ($rejected == $total)
                                        <span class="badge badge-status-rejected">Ditolak</span>
                                    @else
                                        <span class="badge bg-light text-dark border">Campuran ({{ $pending }} pending)</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <button type="button" class="btn btn-link grant-detail-btn p-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#donorDetailModal{{ $donorId }}">
                                            Lihat detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4" style="color: var(--ink-soft);">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 opacity-40"></i>
                                    Belum ada pengajuan hibah buku.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($grants->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3 px-2 pagination-wrapper">
                    <div class="text-muted small">
                        Menampilkan {{ $grants->firstItem() }} sampai {{ $grants->lastItem() }} dari
                        {{ $grants->total() }} data hibah
                    </div>
                    <div>
                        {{ $grants->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    @php
        $grouped = $grants->groupBy(function($g) {
            return ($g->kontak_pemberi ?? '') . '|' . ($g->nama_pemberi ?? '');
        });
    @endphp

    @foreach ($grouped as $groupKey => $items)
        @php $donorId = md5($groupKey); $first = $items->first(); @endphp
        <div class="modal fade grant-detail-modal" id="donorDetailModal{{ $donorId }}" tabindex="-1"
            aria-labelledby="donorDetailModalLabel{{ $donorId }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="donorDetailModalLabel{{ $donorId }}">
                            <i class="bi bi-journal-text me-1"></i> Detail Hibah — {{ $first->nama_pemberi }} ({{ $items->count() }} judul)
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-dark">

                        @foreach ($items as $idx => $grant)
                            @php
                                $detailFoto = null;
                                if (!empty($grant->judul_buku)) {
                                    $detailMatch = \App\Models\Book::where('judul', $grant->judul_buku)->first();
                                    if ($detailMatch && !empty($detailMatch->foto)) {
                                        $detailFoto = $detailMatch->foto;
                                    }
                                }
                                if (empty($detailFoto)) {
                                    $detailFoto = $grant->foto_buku;
                                }
                                $detailKategori = !empty($grant->kategori_buku)
                                    ? (is_array($grant->kategori_buku) ? implode(', ', $grant->kategori_buku) : $grant->kategori_buku)
                                    : '-';
                            @endphp

                            <div class="grant-detail-card p-3 mb-3">
                                <div class="row g-3 align-items-start">
                                    <div class="col-md-3 text-center">
                                        @if ($detailFoto)
                                            <img src="{{ asset('storage/' . $detailFoto) }}"
                                                alt="Sampul {{ $grant->judul_buku ?? 'buku' }}"
                                                class="grant-detail-img w-100"
                                                style="max-width: 140px; height: 180px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light border rounded text-muted"
                                                style="width: 100%; max-width: 140px; height: 180px; margin: 0 auto; font-size: 12px;">
                                                Tidak Ada Foto
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <div class="small text-muted text-uppercase fw-semibold">Judul Buku</div>
                                                <div class="fs-5 fw-bold text-dark">{{ $grant->judul_buku ?? 'Judul belum diisi' }}</div>
                                            </div>
                                            <div class="text-end">
                                                @if ($grant->status_hibah == 'pending')
                                                    <span class="badge badge-status-pending">Menunggu Verifikasi</span>
                                                @elseif($grant->status_hibah == 'disetujui')
                                                    <span class="badge badge-status-approved">Disetujui</span>
                                                @else
                                                    <span class="badge badge-status-rejected">Ditolak</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-sm-6">
                                                <div class="small text-muted text-uppercase fw-semibold">Penulis</div>
                                                <div class="fw-semibold">{{ $grant->penulis_buku ?? '-' }}</div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="small text-muted text-uppercase fw-semibold">Penerbit</div>
                                                <div class="fw-semibold">{{ $grant->penerbit_buku ?? '-' }}</div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="small text-muted text-uppercase fw-semibold">Tahun Terbit</div>
                                                <div class="fw-semibold">{{ $grant->tahun_terbit ?? '-' }}</div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="small text-muted text-uppercase fw-semibold">ISBN</div>
                                                <div class="fw-semibold">{{ $grant->isbn ?? '-' }}</div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="small text-muted text-uppercase fw-semibold">Jumlah Halaman</div>
                                                <div class="fw-semibold">{{ $grant->jumlah_halaman ? $grant->jumlah_halaman . ' halaman' : '-' }}</div>
                                            </div>

                                            <div class="col-sm-4 mt-2">
                                                <div class="small text-muted text-uppercase fw-semibold">Jumlah Eksemplar</div>
                                                <div class="fw-semibold">{{ $grant->jumlah_eksemplar }}</div>
                                            </div>

                                            <div class="col-sm-4 mt-2">
                                                <div class="small text-muted text-uppercase fw-semibold">Bahasa</div>
                                                <div class="fw-semibold">{{ $grant->bahasa ?? '-' }}</div>
                                            </div>

                                            <div class="col-sm-4 mt-2">
                                                <div class="small text-muted text-uppercase fw-semibold">Kategori</div>
                                                <div class="fw-semibold">{{ $detailKategori }}</div>
                                            </div>

                                            <div class="col-12 mt-3">
                                                <div class="small text-muted text-uppercase fw-semibold">Sinopsis Buku</div>
                                                <details class="sinopsis-details">
                                                    <summary>Lihat sinopsis</summary>
                                                    <div class="sinopsis-text mt-2">{{ $grant->sinopsis ?? '-' }}</div>
                                                </details>
                                            </div>

                                            <div class="col-12 mt-3">
                                                <div class="d-flex gap-2">
                                                    @if ($grant->status_hibah == 'pending')
                                                        <button type="button" class="btn btn-approve"
                                                            onclick="openApproveModal('{{ $grant->id }}', '{{ addslashes($grant->judul_buku) }}')">
                                                            Approve
                                                        </button>

                                                        <form action="{{ route('grants.reject', $grant->id) }}" method="POST"
                                                            onsubmit="return confirm('Tolak hibah buku ini?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-reject">
                                                                Tolak
                                                            </button>
                                                        </form>
                                                    @elseif($grant->status_hibah == 'disetujui')
                                                        <button class="btn btn-done" disabled>Selesai</button>
                                                    @else
                                                        <button class="btn btn-outline-secondary" disabled>Ditolak</button>
                                                        {{-- Tombol hapus untuk grant yang ditolak --}}
                                                        <form action="{{ route('grants.destroy', $grant->id) }}" method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus data hibah yang ditolak ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-3 row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted text-uppercase fw-semibold">Alamat Pengirim</div>
                                <div>{{ $first->alamat_pengirim ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted text-uppercase fw-semibold"> Nama Donatur</div>
                                <div>{{ $first->nama_pemberi ?? '-' }}</div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- MODAL POP-UP KONFIRMASI RAK -->
    <div class="modal fade" id="approveGrantModal" tabindex="-1" aria-labelledby="approveGrantModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px;">
                <div class="modal-header"
                    style="background: var(--teal); color: var(--paper); border-top-left-radius: 17px; border-top-right-radius: 17px;">
                    <h5 class="modal-title fw-bold fs-6" id="approveGrantModalLabel"
                        style="font-family: 'Baloo 2', sans-serif;">
                        <i class="bi bi-check-circle me-1"></i> Setujui Hibah & Pilih Rak
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="approveGrantForm" action="" method="POST">
                    @csrf
                    <div class="modal-body p-4 text-dark">
                        <p class="mb-3">
                            Buku <strong id="modalBookTitle" style="color: var(--teal);"></strong> akan disetujui dan
                            ditambahkan ke katalog utama.
                        </p>
                        <div class="mb-3">
                            <label for="rack_id" class="form-label fw-bold">Pilih Lokasi Rak Buku <span
                                    class="text-danger">*</span></label>
                            <select name="rack_id" id="rack_id" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Rak Buku --</option>
                                @foreach ($racks as $rack)
                                    <option value="{{ $rack->id }}">
                                        {{ $rack->nama_rak ?? ($rack->nama ?? $rack->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light"
                        style="border-bottom-left-radius: 17px; border-bottom-right-radius: 17px;">
                        <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn text-white"
                            style="background: var(--teal); border-radius: 10px; font-weight:700;">
                            Simpan & Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT POP-UP MODAL -->
    <script>
        function openApproveModal(grantId, bookTitle) {
            // Pasang URL action form secara dinamis
            document.getElementById('approveGrantForm').action = '/grants/' + grantId + '/approve';
            document.getElementById('modalBookTitle').innerText = '"' + bookTitle + '"';

            // Tampilkan Bootstrap Modal
            var approveModal = new bootstrap.Modal(document.getElementById('approveGrantModal'));
            approveModal.show();
        }
    </script>
@endsection
