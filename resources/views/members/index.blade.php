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

        .card-members {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0px 14px 30px -14px rgba(30, 42, 34, 0.18);
        }

        .table-members thead th {
            background: var(--teal);
            color: var(--paper);
            font-family: 'Baloo 2', sans-serif;
            font-weight: 600;
            font-size: .85rem;
            border: none;
            white-space: nowrap;
        }

        .table-members thead th:first-child {
            border-top-left-radius: 10px;
        }

        .table-members thead th:last-child {
            border-top-right-radius: 10px;
        }

        .table-members tbody tr:hover {
            background: var(--teal-light);
        }

        .badge-peran {
            background: var(--paper-alt) !important;
            border: 1.5px dashed var(--gold) !important;
            color: var(--teal-dark) !important;
            border-radius: 999px;
            font-weight: 700;
            letter-spacing: .03em;
        }

        .btn-print-card {
            background: transparent;
            border: 1.5px solid var(--teal);
            color: var(--teal) !important;
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-print-card:hover {
            background: var(--teal);
            color: var(--paper) !important;
        }

        .btn-edit-member {
            background: var(--gold);
            border: none;
            color: var(--teal-dark);
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-edit-member:hover {
            background: #d6931f;
            color: var(--teal-dark);
        }

        .btn-delete-member {
            background: transparent;
            border: 1.5px solid var(--berry);
            color: var(--berry);
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-delete-member:hover {
            background: var(--berry);
            color: #fff;
        }

        .qr-img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid var(--line);
            padding: 2px;
            background: #fff;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .qr-img:hover {
            transform: scale(1.1);
        }

        /* Styling tambahan untuk search box */
        .search-wrapper {
            position: relative;
            width: 250px;
        }

        .search-wrapper .bi-search {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--teal);
        }

        .search-wrapper input {
            padding-left: 38px;
            border-radius: 10px;
            border: 1px solid var(--line);
        }
    </style>

    {{-- Form khusus untuk cetak batch, dibuat terpisah (tidak lagi membungkus tabel)
         supaya tidak terjadi nested <form> dengan form hapus di dalam tabel.
         Checkbox yang dicentang akan di-inject ke sini via JavaScript saat submit. --}}
    <form action="{{ route('members.print_batch') }}" method="POST" target="_blank" id="formPrintBatch" style="display:none;">
        @csrf
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <button type="button" class="btn btn-sm btn-brand-primary" id="btnCetakTerpilih" disabled onclick="submitPrintBatch()">
            <i class="bi bi-printer-fill me-1"></i> Cetak Kartu Terpilih (<span id="countSelected">0</span>)
        </button>


    </div>

    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom page-title-block">
        <h1 class="h2">Data Anggota Perpustakaan</h1>
        <a href="{{ route('members.create') }}" class="btn btn-brand-primary">Tambah Anggota</a>
    </div>
    {{-- Input Search Data Anggota --}}
    <div class="search-wrapper">
        <i class="bi bi-search"></i>
        <input type="text" id="memberSearchInput" class="form-control form-control-sm"
            placeholder="Cari NISN, nama, peran, no HP...">
    </div>

    <div class="card card-members border-0">
        <div class="card-body">
            <table class="table table-striped align-middle table-members" id="memberTable">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>QR Code</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Peran</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $m)
                        @php
                            $qrData = $m->nisn ?? 'MEMBER-' . $m->id;
                            $qrUrlLarge =
                                'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrData);
                        @endphp
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" value="{{ $m->id }}" class="form-check-input member-checkbox">
                            </td>
                            <td class="text-center" style="width: 70px;">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($qrData) }}"
                                    alt="QR {{ $qrData }}" class="qr-img" title="Data: {{ $qrData }}"
                                    onclick="showQrModal('{{ $qrUrlLarge }}', '{{ $m->nama_lengkap }}', '{{ $qrData }}')">
                            </td>
                            <td>{{ $m->nisn ?? '-' }}</td>
                            <td><strong>{{ $m->nama_lengkap }}</strong></td>
                            <td><span class="badge badge-peran">{{ strtoupper($m->peran) }}</span></td>
                            <td>{{ $m->no_hp }}</td>
                            <td>
                                <a href="{{ route('members.print_card', $m->id) }}" target="_blank"
                                    class="btn btn-sm btn-print-card">
                                    <i class="bi bi-card-heading"></i> Cetak Single
                                </a>

                                <button type="button" class="btn btn-sm btn-edit-member"
                                    onclick="openEditModal('{{ $m->id }}', '{{ $m->nisn }}', '{{ $m->nama_lengkap }}', '{{ $m->peran }}', '{{ $m->no_hp }}')">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <form action="{{ route('members.destroy', $m->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete-member">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Pop-up QR Code -->
    <div class="modal fade" id="qrPreviewModal" tabindex="-1" aria-labelledby="qrPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center" style="border-radius: 18px;">
                <div class="modal-header"
                    style="background: var(--teal); color: var(--paper); border-top-left-radius: 17px; border-top-right-radius: 17px;">
                    <h5 class="modal-title fw-bold fs-6" id="qrModalTitle" style="font-family: 'Baloo 2', sans-serif;">
                        <i class="bi bi-qr-code"></i> Preview QR Code
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <img id="qrModalImg" src="" alt="QR Code Preview" class="img-fluid rounded border p-2 mb-2"
                        style="width: 250px; height: 250px;">
                    <div id="qrModalSubtext" class="fw-bold text-muted small"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 18px;">
                <div class="modal-header"
                    style="background: var(--teal); color: var(--paper); border-top-left-radius: 17px; border-top-right-radius: 17px;">
                    <h5 class="modal-title fw-bold" id="editMemberModalLabel" style="font-family: 'Baloo 2', sans-serif;">
                        <i class="bi bi-pencil-square"></i> Edit Data Anggota
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editMemberForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4 text-dark">
                        <div class="mb-3">
                            <label for="edit_nisn" class="form-label fw-bold">NISN</label>
                            <input type="text" class="form-control" id="edit_nisn" name="nisn"
                                placeholder="Masukkan NISN jika ada">
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama_lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_peran" class="form-label fw-bold">Peran</label>
                            <select class="form-select" id="edit_peran" name="peran" required>
                                <option value="siswa">SISWA</option>
                                <option value="guru">GURU</option>
                                <option value="petugas">PETUGAS</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nohp" class="form-label fw-bold">No. HP</label>
                            <input type="text" class="form-control" id="edit_nohp" name="no_hp">
                        </div>
                    </div>
                    <div class="modal-footer bg-light"
                        style="border-bottom-left-radius: 17px; border-bottom-right-radius: 17px;">
                        <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn text-white"
                            style="background: var(--teal); border-radius: 10px; font-weight:700;">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, nisn, nama, peran, nohp) {
            document.getElementById('editMemberForm').action = `/members/${id}`;
            document.getElementById('edit_nisn').value = nisn;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_peran').value = peran.toLowerCase();
            document.getElementById('edit_nohp').value = nohp;

            var editModal = new bootstrap.Modal(document.getElementById('editMemberModal'));
            editModal.show();
        }

        function showQrModal(qrUrl, nama, payload) {
            document.getElementById('qrModalImg').src = qrUrl;
            document.getElementById('qrModalTitle').innerHTML = `<i class="bi bi-qr-code"></i> QR - ${nama}`;
            document.getElementById('qrModalSubtext').innerText = `Data: ${payload}`;

            var qrModal = new bootstrap.Modal(document.getElementById('qrPreviewModal'));
            qrModal.show();
        }

        // Mengumpulkan id member yang dicentang, inject sebagai hidden input
        // ke form print-batch (yang berdiri sendiri di luar tabel), lalu submit.
        function submitPrintBatch() {
            const form = document.getElementById('formPrintBatch');

            // Bersihkan hidden input lama supaya tidak numpuk jika diklik berkali-kali
            form.querySelectorAll('input[name="member_ids[]"]').forEach(el => el.remove());

            const checked = document.querySelectorAll('.member-checkbox:checked');
            checked.forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'member_ids[]';
                hidden.value = cb.value;
                form.appendChild(hidden);
            });

            if (checked.length === 0) {
                return;
            }

            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const memberCheckboxes = document.querySelectorAll('.member-checkbox');
            const btnCetak = document.getElementById('btnCetakTerpilih');
            const countSelected = document.getElementById('countSelected');
            const searchInput = document.getElementById('memberSearchInput');
            const tableRows = document.querySelectorAll('#memberTable tbody tr');

            // Fitur Live Search
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const query = this.value.toLowerCase().trim();

                    tableRows.forEach(row => {
                        const rowText = row.innerText.toLowerCase();
                        if (rowText.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            function updateButtonState() {
                const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
                countSelected.innerText = checkedCount;

                if (checkedCount > 0) {
                    btnCetak.removeAttribute('disabled');
                } else {
                    btnCetak.setAttribute('disabled', 'disabled');
                }
            }

            // Toggle select all (hanya berlaku untuk baris yang terlihat/tidak tersembunyi filter search)
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    memberCheckboxes.forEach(cb => {
                        const parentRow = cb.closest('tr');
                        if (parentRow && parentRow.style.display !== 'none') {
                            cb.checked = this.checked;
                        }
                    });
                    updateButtonState();
                });
            }

            // Toggle individual checkbox
            memberCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAll.checked = false;
                    } else if (document.querySelectorAll('.member-checkbox:checked').length ===
                        memberCheckboxes.length) {
                        selectAll.checked = true;
                    }
                    updateButtonState();
                });
            });
        });
    </script>
@endsection
