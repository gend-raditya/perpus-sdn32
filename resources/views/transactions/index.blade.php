@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-sm card-custom">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 font-display">Riwayat Peminjaman Buku</h5>
                        <p class="small text-muted mb-0">Kelola dan pantau sirkulasi peminjaman buku siswa</p>
                    </div>
                    <a href="{{ route('transactions.create') }}" class="btn btn-teal btn-sm text-white"
                        style="background-color: var(--teal); box-shadow: 2px 2px 0 var(--gold);">
                        <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-hover align-middle datatable-init">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Anggota</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Deadline</th>
                                <th width="18%">Status</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $trx->member->nama_lengkap }}</strong></td>
                                    <td>{{ $trx->book->judul }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->deadline)->format('d M Y') }}</td>
                                    <!-- KOLOM STATUS -->
                                    <!-- KOLOM STATUS -->
                                    <td>
                                        @php
                                            $status = strtolower(trim($trx->status));
                                        @endphp

                                        @if ($status == 'pinjam')
                                            <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold">⏳
                                                Dipinjam</span>
                                        @elseif($status == 'hilang')
                                            <span class="badge rounded-pill bg-danger text-white px-3 py-2 fw-bold">❌
                                                Hilang</span>
                                        @else
                                            <span class="badge rounded-pill bg-success text-white px-3 py-2 fw-bold">✅
                                                Kembali</span>
                                        @endif
                                    </td>

                                    <!-- KOLOM AKSI -->
                                    <td class="text-center">
                                        @if ($status == 'pinjam')
                                            <span class="badge bg-light text-warning border border-warning px-2 py-1">Pinjam
                                                Aktif</span>
                                        @elseif ($status == 'hilang')
                                            <span class="badge bg-light text-danger border border-danger px-2 py-1">Buku
                                                Hilang</span>
                                        @else
                                            <span class="badge bg-light text-success border border-success px-2 py-1"><i
                                                    class="bi bi-check2"></i> Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada transaksi terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
