@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard Pengelola</h1>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>Total Koleksi Buku</h5>
                    <h2>{{ $totalBuku }}</h2>
                    <small>Buku Terdaftar (Pengadaan & Hibah)</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h5>Hibah Perlu Dicek</h5>
                    <h2>{{ $totalHibahPending }}</h2>
                    <small>Menunggu Verifikasi Admin</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Total Anggota</h5>
                    <h2>{{ $totalAnggota }}</h2>
                    <small>Siswa & Guru Terdaftar</small>
                </div>
            </div>
        </div>
    </div>



    <div class="card shadow mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Aktivitas Peminjaman Terakhir</h5>
            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $trx)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $trx->member->nama_lengkap }}</div>
                                    <small class="text-muted">ID: {{ $trx->member_id }}</small>
                                </td>
                                <td>{{ $trx->book->judul }}</td>
                                <td>{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d M Y') }}</td>
                                <td>
                                    @if ($trx->status == 'pinjam')
                                        <span class="badge bg-success">Dipinjam</span>
                                    @else
                                        <span class="badge bg-secondary">Kembali</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada aktivitas transaksi terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
