@extends('layouts.app')

<style>
    /* CSS tambahan khusus halaman denda */
    .badge-denda-belum {
        background-color: var(--warning-light) !important;
        color: var(--warning-orange) !important;
        border: 1px solid rgba(222, 126, 38, 0.3) !important;
        font-weight: 700;
    }

    .badge-danger-soft {
        background-color: #fce8e6 !important;
        color: #a80000 !important;
        border: 1px solid rgba(168, 0, 0, 0.2) !important;
        font-weight: 700;
    }

    .member-group-row {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>

@section('content')
    <div class="container-fluid py-2 px-4">

        <!-- Bagian Atas: Judul & Tombol Aksi -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="font-display mb-1 fw-bold" style="color: var(--ink);">Pengembalian & Data Denda</h3>
                <p class="text-muted small mb-0">Data keterlambatan otomatis terhitung berdasarkan tanggal hari ini.</p>
            </div>
            <button
                class="btn btn-brand-primary text-black fw-bold px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2"
                style="border-radius: 12px;" data-bs-toggle="modal"
                data-bs-target="#modalAturTarif">
                <i class="bi bi-gear"></i> Atur Tarif
            </button>
        </div>

        <!-- Ringkasan Statistik Denda Berdasarkan Data Asli -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card card-custom p-4 border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-4 me-3 d-flex align-items-center justify-content-center"
                            style="background-color: var(--warning-light); color: var(--warning-orange); width: 56px; height: 56px;">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block fw-semibold text-uppercase tracking-wider mb-1"
                                style="font-size: 0.75rem;">Total Estimasi Denda Berjalan</span>
                            <h3 class="font-display mb-0 fw-bold text-dark">Rp
                                {{ number_format($totalDendaBelumBayar, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom p-4 border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-4 me-3 d-flex align-items-center justify-content-center"
                            style="background-color: var(--teal-light); color: var(--teal); width: 56px; height: 56px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block fw-semibold text-uppercase tracking-wider mb-1"
                                style="font-size: 0.75rem;">Jumlah Murid Terlambat</span>
                            <h3 class="font-display mb-0 fw-bold text-dark">{{ $jumlahMuridDenda }} Siswa</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form & Tabel Pengembalian Massal (Bulk) per Anggota -->
        <form action="{{ route('transaksi.kembali.bulk') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="card card-custom border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <!-- Header Card: Info, Search Box Siswa, & Tombol Submit Bulk -->
                <div
                    class="p-4 bg-white border-bottom d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="text-muted small d-flex align-items-center">
                        <i class="bi bi-info-circle me-2 text-primary fs-5"></i>
                        <span>Centang buku-buku yang ingin dikembalikan secara bersamaan.</span>
                    </div>

                    <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                        <!-- Input Search Cepat Nama Siswa -->
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text bg-light border-end-0 text-muted"
                                style="border-radius: 10px 0 0 10px;">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchSiswa" class="form-control bg-light border-start-0 shadow-none"
                                placeholder="Cari nama siswa..." style="border-radius: 0 10px 10px 0;">
                        </div>

                        <button type="submit" id="btnKembalikan"
                            class="btn btn-success fw-bold px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 text-nowrap"
                            style="border-radius: 10px;"
                            onclick="return confirm('Apakah buku yang dicentang benar-benar sudah dikembalikan?')" disabled>
                            <i class="bi bi-check2-square"></i> Kembalikan Buku yang Dicentang
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabelDenda" style="width:100%">
                        <thead class="bg-light">
                            <tr style="color: var(--ink-soft); font-size: 0.85rem;">
                                <th width="5%" class="text-center py-3">Pilih</th>
                                <th class="py-3">Nama Anggota</th>
                                <th class="py-3">Judul Buku</th>
                                <th class="py-3">Batas Kembali</th>
                                <th class="py-3">Keterlambatan</th>
                                <th class="py-3">Jumlah Denda</th>
                                <th class="py-3">Status</th>
                                <th class="text-center py-3">Aksi Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Mengelompokkan transaksi berdasarkan member_id agar nama siswa tidak berulang
                                $groupedData = $dataDenda->groupBy('member_id');
                            @endphp

                            @forelse($groupedData as $memberId => $transactions)
                                @php
                                    $firstItem = $transactions->first();
                                    $memberNama = $firstItem->member->nama_lengkap ?? 'Tidak Diketahui';
                                    $memberKelas = $firstItem->member->kelas ?? 'Siswa';
                                    $totalBuku = $transactions->count();
                                @endphp

                                <!-- Baris Header / Info Anggota (Colspan 8) -->
                                <tr class="member-group-row" data-nama="{{ strtolower($memberNama) }}">
                                    <td colspan="8" class="py-3 px-3">
                                        <div class="d-flex align-items-center gap-2 justify-content-between">
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-sm btn-light toggle-books p-1"
                                                    data-member="{{ $memberId }}" aria-expanded="false" title="Tampilkan/ Sembunyikan daftar buku">
                                                    <i class="bi bi-chevron-down"></i>
                                                </button>
                                                <i class="bi bi-person-fill text-primary fs-5"></i>
                                                <strong class="text-dark">{{ $memberNama }}</strong>
                                                <span class="text-muted fw-normal">({{ $memberKelas }})</span>
                                                <span class="badge bg-secondary text-white ms-2 px-2 py-1">{{ $totalBuku }} Buku Dipinjam</span>
                                                @php
                                                    $selectableBooks = $transactions->filter(fn($item) => strtolower(trim((string) $item->status)) !== 'hilang')->count();
                                                @endphp
                                                @if ($selectableBooks > 0)
                                                    <label class="d-inline-flex align-items-center gap-2 ms-2 mb-0 text-dark small fw-semibold">
                                                        <input type="checkbox" class="form-check-input member-select-all shadow-sm"
                                                            data-member="{{ $memberId }}" style="cursor:pointer; width: 16px; height: 16px;">
                                                        <span>Pilih semua</span>
                                                    </label>
                                                @endif
                                            </div>
                                            <div>
                                                <!-- optional right aligned controls per member -->
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Perulangan Buku yang Dipinjam oleh Siswa Tersebut (disembunyikan awalnya) -->
                                @foreach ($transactions as $item)
                                    @php
                                        $status = strtolower(trim($item->status));
                                    @endphp
                                    <tr class="item-row d-none" data-member="{{ $memberId }}">
                                        <!-- Checkbox Bulk -->
                                        <td class="text-center">
                                            @if ($status != 'hilang')
                                                <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                    class="form-check-input shadow-sm"
                                                    style="cursor: pointer; width: 18px; height: 18px;">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <!-- Detail Item Indikator -->
                                        <td>
                                            <span class="text-muted small d-inline-flex align-items-center">
                                                <i class="bi bi-arrow-return-right me-1 text-secondary"></i> Item
                                            </span>
                                        </td>

                                        <!-- Judul Buku & Tanggal -->
                                        <td>
                                            <span
                                                class="fw-bold text-dark">{{ $item->book->judul ?? 'Judul Buku Kosong' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary small d-block">Pinjam: {{ date('d M Y', strtotime($item->tanggal_pinjam)) }}</span>
                                            <span class="fw-semibold text-secondary small">Deadline: {{ date('d M Y', strtotime($item->deadline)) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-danger fw-bold">{{ $item->hari_telat }} Hari</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-danger">Rp
                                                {{ number_format($item->total_denda, 0, ',', '.') }}</span>
                                        </td>

                                        <!-- Badge Status -->
                                        <td>
                                            @if ($status == 'hilang')
                                                <span class="badge badge-danger-soft px-3 py-2 rounded-pill">❌ Buku
                                                    Hilang</span>
                                            @else
                                                <span class="badge badge-denda-belum px-3 py-2 rounded-pill">Belum
                                                    Kembali</span>
                                            @endif
                                        </td>

                                        <!-- Kolom Aksi Satuan (Hanya Tombol/Icon Hilang atau Info) -->
                                        <td class="text-center">
                                            @if ($status == 'hilang')
                                                <small class="text-muted fw-bold d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-info-circle"></i> Denda Diberhentikan
                                                </small>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger fw-bold px-2 py-1 shadow-sm btn-hilang"
                                                    style="border-radius: 6px;" title="Nyatakan Hilang"
                                                    data-id="{{ $item->id }}">
                                                    <i class="bi bi-x-circle"></i>Hilang
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <div class="p-3">
                                            <i class="bi bi-emoji-smile fs-1 d-block mb-3 text-success"></i>
                                            <h5 class="fw-bold text-dark mb-1">Aman!</h5>
                                            <p class="text-muted mb-0 small">Tidak ada siswa yang memiliki keterlambatan atau mengembalikan buku saat ini.
                                               </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <!-- MODAL: ATUR TARIF DENDA -->
    <div class="modal fade" id="modalAturTarif" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4 pb-0">
                    <h5 class="modal-title font-display fw-bold fs-4 d-flex align-items-center gap-2">
                        <i class="bi bi-gear-fill text-warning"></i> Pengaturan Tarif Denda
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('fines.updateTarif') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-2">Tarif Denda per Hari (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold border-end-0 text-muted"
                                    style="border-radius: 12px 0 0 12px;">Rp</span>
                                <input type="number" class="form-control bg-light fw-bold shadow-none"
                                    value="{{ $tarifPerHari }}" name="tarif_per_hari"
                                    style="border-radius: 0 12px 12px 0;" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-2">Tarif Denda Buku Hilang (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold border-end-0 text-muted"
                                    style="border-radius: 12px 0 0 12px;">Rp</span>
                                <input type="number" class="form-control bg-light fw-bold shadow-none"
                                    value="{{ $tarifDendaHilang }}" name="tarif_denda_hilang"
                                    style="border-radius: 0 12px 12px 0;">
                            </div>
                            <div class="form-text small text-muted">Jika kosong, biaya hilang tidak akan otomatis diterapkan.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4 pt-0">
                        <button type="button" class="btn btn-light fw-bold px-4 py-2" style="border-radius: 12px;"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white fw-bold px-4 py-2 shadow-sm"
                            style="background-color: var(--teal); border-radius: 12px;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script JavaScript untuk Filter/Pencarian Nama Siswa Secara Instan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchSiswa');
            if (!searchInput) return;

            searchInput.addEventListener('keyup', function() {
                const keyword = this.value.toLowerCase().trim();

                // Ambil semua grup baris siswa dan baris item buku di dalam tabel
                const groupRows = document.querySelectorAll('.member-group-row');

                groupRows.forEach(groupRow => {
                    const studentName = groupRow.getAttribute('data-nama');
                    let nextElement = groupRow.nextElementSibling;
                    let hasVisibleItems = false;

                    // Kumpulkan semua baris item buku milik siswa ini sampai ketemu grup siswa berikutnya
                    let studentItems = [];
                    while (nextElement && !nextElement.classList.contains('member-group-row')) {
                        studentItems.push(nextElement);
                        nextElement = nextElement.nextElementSibling;
                    }

                    // Cek apakah nama siswa cocok dengan keyword pencarian
                    const isMatch = studentName.includes(keyword);

                    if (isMatch) {
                        groupRow.style.display = '';
                        studentItems.forEach(item => item.style.display = '');
                    } else {
                        groupRow.style.display = 'none';
                        studentItems.forEach(item => item.style.display = 'none');
                    }
                });

                // Initialize toggle buttons (in case search ran and changed DOM visibility)
                document.querySelectorAll('.toggle-books').forEach(btn => {
                    const memberId = btn.getAttribute('data-member');
                    // set initial icon state (collapsed)
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-chevron-up');
                        icon.classList.add('bi-chevron-down');
                    }
                });

            });

            // Toggle show/hide books per student
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.toggle-books');
                if (!btn) return;
                const memberId = btn.getAttribute('data-member');
                if (!memberId) return;
                const rows = document.querySelectorAll('tr.item-row[data-member="' + memberId + '"]');
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                rows.forEach(r => {
                    if (expanded) {
                        r.classList.add('d-none');
                    } else {
                        r.classList.remove('d-none');
                    }
                });
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                // toggle icon
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-chevron-down', expanded);
                    icon.classList.toggle('bi-chevron-up', !expanded);
                }
            });

            document.addEventListener('change', function(e) {
                const memberSelect = e.target.closest('.member-select-all');
                if (!memberSelect) return;

                const memberId = memberSelect.getAttribute('data-member');
                const itemCheckboxes = document.querySelectorAll('input[name="ids[]"][value]:not(:disabled)');
                const memberItemCheckboxes = Array.from(itemCheckboxes).filter(cb => {
                    const row = cb.closest('tr.item-row');
                    return row && row.getAttribute('data-member') === memberId;
                });

                memberItemCheckboxes.forEach(cb => {
                    cb.checked = memberSelect.checked;
                });

                const btnKembalikan = document.getElementById('btnKembalikan');
                if (btnKembalikan) {
                    const anyChecked = Array.from(document.querySelectorAll('input[name="ids[]"]')).some(cb => cb.checked);
                    btnKembalikan.disabled = !anyChecked;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="ids[]"]');
            const btnKembalikan = document.getElementById('btnKembalikan');

            function updateButtonState() {
                const isChecked = Array.from(checkboxes).some(cb => cb.checked);

                if (btnKembalikan) {
                    if (isChecked) {
                        btnKembalikan.removeAttribute('disabled');
                    } else {
                        btnKembalikan.setAttribute('disabled', 'true');
                    }
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const memberId = cb.closest('tr.item-row')?.getAttribute('data-member');
                    const memberToggle = document.querySelector('.member-select-all[data-member="' + memberId + '"]');
                    if (memberToggle) {
                        const memberCheckboxes = Array.from(document.querySelectorAll('tr.item-row[data-member="' + memberId + '"] input[name="ids[]"]'));
                        memberToggle.checked = memberCheckboxes.length > 0 && memberCheckboxes.every(item => item.checked);
                    }
                    updateButtonState();
                });
            });

            // Handler untuk tombol Nyatakan Hilang (AJAX) - menghindari nested form submit
            document.querySelectorAll('.btn-hilang').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const id = this.getAttribute('data-id');
                    if (!id) return;
                    if (!confirm('Nyatakan buku ini hilang?')) return;

                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

                    // Safer fallback: build a form and submit (POST + _method=PATCH) so browser sends cookies and CSRF reliably
                    const baseHilangUrl = "{{ url('/transactions') }}";
                    const action = baseHilangUrl + "/" + id + "/hilang";

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = action;

                    // CSRF token
                    const inputToken = document.createElement('input');
                    inputToken.type = 'hidden';
                    inputToken.name = '_token';
                    inputToken.value = token;
                    form.appendChild(inputToken);

                    // _method = PATCH
                    const inputMethod = document.createElement('input');
                    inputMethod.type = 'hidden';
                    inputMethod.name = '_method';
                    inputMethod.value = 'PATCH';
                    form.appendChild(inputMethod);

                    // optional: include an indicator we came from AJAX UI
                    const inputAjax = document.createElement('input');
                    inputAjax.type = 'hidden';
                    inputAjax.name = 'from_ajax';
                    inputAjax.value = '1';
                    form.appendChild(inputAjax);

                    document.body.appendChild(form);
                    form.submit();
                });
            });

        });
    </script>
@endsection
