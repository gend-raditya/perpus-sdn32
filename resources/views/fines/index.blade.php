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
</style>

@section('content')
    <div class="container-fluid p-0">

        <!-- Bagian Atas: Judul & Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="font-display mb-1" style="color: var(--ink);">Pengaturan & Data Denda Realtime</h3>
                <p class="text-muted small mb-0">Data keterlambatan otomatis terhitung berdasarkan tanggal hari ini.</p>
            </div>
            <button class="btn text-white fw-bold px-4 py-2 shadow-sm"
                style="background-color: var(--warning-orange); border-radius: 12px;" data-bs-toggle="modal"
                data-bs-target="#modalAturTarif">
               
            </button>
        </div>

        <!-- Ringkasan Statistik Denda Berdasarkan Data Asli -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card card-custom p-3 border-0">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 me-3"
                            style="background-color: var(--warning-light); color: var(--warning-orange);">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Total
                                Estimasi Denda Berjalan</small>
                            <h4 class="font-display mb-0 fw-bold">Rp {{ number_format($totalDendaBelumBayar, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom p-3 border-0">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-3 me-3" style="background-color: var(--teal-light); color: var(--teal);">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Jumlah
                                Murid Terlambat</small>
                            <h4 class="font-display mb-0 fw-bold">{{ $jumlahMuridDenda }} Siswa</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Denda Otomatis -->
        <div class="card card-custom p-4 border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable-init" style="width:100%">
                    <thead>
                        <tr style="color: var(--ink-soft); font-size: 0.9rem;">
                            <th>No</th>
                            <th>Nama Murid</th>
                            <th width="8%" class="text-center">Qty</th>
                            <th>Judul Buku</th>

                            <th>Batas Kembali</th>
                            <th>Keterlambatan</th>
                            <th>Jumlah Denda</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataDenda as $index => $item)
                            @php
                                $status = strtolower(trim($item->status));
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <!-- Nama Murid & Kelas -->
                                <td>
                                    <span
                                        class="fw-bold d-block">{{ $item->member->nama_lengkap ?? 'Tidak Diketahui' }}</span>
                                    <small class="text-muted">{{ $item->member->kelas ?? 'Siswa' }}</small>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-light text-dark border fw-bold px-2 py-1">1 Eks</span>
                                </td>

                                <!-- Judul Buku & Tanggal -->
                                <td>{{ $item->book->judul ?? 'Judul Buku Kosong' }}</td>
                                <td><small class="fw-semibold">{{ date('d M Y', strtotime($item->deadline)) }}</small></td>
                                <td class="text-danger fw-bold">{{ $item->hari_telat }} Hari</td>
                                <td class="fw-bold text-danger">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</td>

                                <!-- Badge Status -->
                                <td>
                                    @if ($status == 'hilang')
                                        <span class="badge badge-danger-soft px-3 py-2 rounded-pill">❌ Buku Hilang</span>
                                    @else
                                        <span class="badge badge-denda-belum px-3 py-2 rounded-pill">Belum Kembali</span>
                                    @endif
                                </td>

                                <!-- Kolom Aksi -->
                                <td class="text-center">
                                    @if ($status == 'hilang')
                                        <small class="text-muted fw-bold"><i class="bi bi-info-circle me-1"></i> Denda
                                            Diberhentikan</small>
                                    @else
                                        <div class="d-flex justify-content-center gap-1">
                                            <!-- Form Kembalikan -->
                                            <form action="{{ route('transaksi.kembali', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm text-white fw-bold px-3"
                                                    style="background-color: var(--sage); border-radius: 8px;"
                                                    onclick="return confirm('Apakah murid ini benar-benar mengembalikan buku dan melunasi denda?')">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Kembalikan
                                                </button>
                                            </form>

                                            <!-- Form Hilang -->
                                            <form action="{{ route('transaksi.hilang', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-2"
                                                    style="border-radius: 8px;"
                                                    onclick="return confirm('Konfirmasi: Buku ini dinyatakan HILANG oleh siswa. Denda realtime akan dihentikan. Lanjutkan?')">
                                                    <i class="bi bi-x-circle"></i> Hilang
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-emoji-smile fs-3 d-block mb-2 text-success"></i>
                                    Alhamdulillah, tidak ada siswa yang terlambat mengembalikan buku hari ini!
                                </td>
                            </tr>
                        @endforelse
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL: ATUR TARIF DENDA -->
    <div class="modal fade" id="modalAturTarif" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title font-display fw-bold fs-4"> Pengaturan Tarif Denda</h5>
                    <button type="button" class="btn-close" data-bs-shadow="none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Tarif Denda per Hari (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold border-end-0 text-muted"
                                    style="border-radius: 12px 0 0 12px;">Rp</span>
                                <input type="number" class="form-control bg-light fw-bold" value="{{ $tarifPerHari }}"
                                    name="tarif_per_hari" style="border-radius: 0 12px 12px 0;" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-light fw-bold px-4" style="border-radius: 12px;"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white fw-bold px-4"
                            style="background-color: var(--teal); border-radius: 12px;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
