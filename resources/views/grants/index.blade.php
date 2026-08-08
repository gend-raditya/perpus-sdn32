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
                        @forelse($grants as $key => $grant)
                            <tr>
                                <td>{{ $grants->firstItem() + $key }}

                                </td>
                                <!-- DITAMBAHKAN INI -->
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                        #{{ $grant->id }}
                                    </span>
                                </td>

                                <td>
                                    <strong>{{ $grant->nama_pemberi }}</strong>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">
                                        <i class="bi bi-telephone me-1 text-muted"></i>
                                        {{ $grant->kontak_pemberi ?? ($grant->no_hp ?? '-') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-start gap-3">
                                        @php
                                            $fotoTampil = null;

                                            // 1. Cek dulu apakah data buku terkait di tabel books ada dan punya foto
                                            if (!empty($grant->judul_buku)) {
                                                $matchingBook = \App\Models\Book::where(
                                                    'judul',
                                                    $grant->judul_buku,
                                                )->first();
                                                if ($matchingBook && !empty($matchingBook->foto)) {
                                                    $fotoTampil = $matchingBook->foto;
                                                }
                                            }

                                            // 2. Jika di tabel books tidak ada/kosong, gunakan foto asli dari pengajuan hibah (grants)
                                            if (empty($fotoTampil)) {
                                                $fotoTampil = $grant->foto_buku;
                                            }
                                        @endphp

                                        @if ($fotoTampil)
                                            <img src="{{ asset('storage/' . $fotoTampil) }}"
                                                alt="Sampul {{ $grant->judul_buku }}"
                                                class="img-thumbnail shadow-sm flex-shrink-0"
                                                style="width: 50px; height: 70px; object-fit: cover; border-radius: 6px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light border rounded text-muted shadow-sm flex-shrink-0"
                                                style="width: 50px; height: 70px; font-size: 10px; text-align: center; line-height: 1.2;">
                                                Tidak Ada<br>Foto
                                            </div>
                                        @endif

                                        <div>
                                            <strong>{{ $grant->judul_buku }}</strong><br>
                                            <span class="text-muted small">Alamat Pengirim:
                                                {{ $grant->alamat_pengirim ?? '-' }}</span><br>

                                            <!-- Kategori Buku -->
                                            @if (!empty($grant->kategori_buku))
                                                <div>
                                                    <span class="badge bg-secondary mt-1">
                                                        {{ is_array($grant->kategori_buku) ? implode(', ', $grant->kategori_buku) : $grant->kategori_buku }}
                                                    </span>
                                                </div>
                                            @endif

                                            <small class="badge badge-eks mt-1">{{ $grant->jumlah_eksemplar }} Eks</small>

                                            <!-- Pesan / Deskripsi Kondisi Buku -->
                                            @if (!empty($grant->deskripsi_kondisi))
                                                <div class="text-muted small mt-1">
                                                    <i class="bi bi-chat-left-text me-1"></i><strong>Pesan/Kondisi:</strong>
                                                    {{ is_array($grant->deskripsi_kondisi) ? json_encode($grant->deskripsi_kondisi) : $grant->deskripsi_kondisi }}
                                                </div>
                                            @endif

                                            @if ($grant->sinopsis)
                                                <details class="sinopsis-details mt-1">
                                                    <summary><i class="bi bi-eye"></i> Lihat Sinopsis</summary>
                                                    <div class="sinopsis-text text-muted mt-1">
                                                        {{ $grant->sinopsis }}
                                                    </div>
                                                </details>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($grant->status_hibah == 'pending')
                                        <span class="badge badge-status-pending">Menunggu Verifikasi</span>
                                    @elseif($grant->status_hibah == 'disetujui')
                                        <span class="badge badge-status-approved">Disetujui</span>
                                    @else
                                        <span class="badge badge-status-rejected">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if ($grant->status_hibah == 'pending')
                                            <!-- Tombol Approve panggil JS untuk Buka Modal -->
                                            <button type="button" class="btn btn-sm btn-approve"
                                                onclick="openApproveModal('{{ $grant->id }}', '{{ addslashes($grant->judul_buku) }}')">
                                                Approve
                                            </button>

                                            <form action="{{ route('grants.reject', $grant->id) }}" method="POST"
                                                onsubmit="return confirm('Tolak hibah buku ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-reject">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-done" disabled>Selesai</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4" style="color: var(--ink-soft);">
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
