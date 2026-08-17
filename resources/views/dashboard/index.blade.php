@extends('layouts.app')

@section('content')
    <style>
        /* Tema mengikuti variabel warna & font global dari layouts.app */
        .dashboard-title h1 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--ink);
        }

        .stat-card-dash {
            border: 2px dashed var(--line);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0px 14px 30px -16px rgba(30, 42, 34, 0.2);
            position: relative;
            overflow: hidden;
        }

        .stat-card-dash .card-body {
            padding: 28px 26px;
            position: relative;
            z-index: 1;
        }

        .stat-card-dash::before {
            content: "";
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: .12;
            z-index: 0;
        }

        .stat-card-dash .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #fff;
            margin-bottom: 20px;
        }

        .stat-card-dash h5 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 600;
            font-size: .95rem;
            color: var(--ink-soft);
            margin-bottom: 10px;
        }

        .stat-card-dash h2 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 800;
            font-size: 2.3rem;
            letter-spacing: .03em;
            line-height: 1.3;
            color: var(--ink);
            margin-bottom: 10px;
        }

        .stat-card-dash small {
            color: var(--ink-soft);
            display: block;
            line-height: 1.5;
        }

        .stat-card-buku .stat-icon {
            background: var(--teal);
        }

        .stat-card-buku::before {
            background: var(--teal);
        }

        .stat-card-hibah .stat-icon {
            background: var(--gold);
        }

        .stat-card-hibah::before {
            background: var(--gold);
        }

        .stat-card-anggota .stat-icon {
            background: var(--sage);
        }

        .stat-card-anggota::before {
            background: var(--sage);
        }

        .card-activity {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0px 14px 30px -16px rgba(30, 42, 34, 0.18);
            overflow: hidden;
        }

        .card-activity .card-header {
            background: var(--paper-alt) !important;
            border-bottom: 2px dashed var(--line);
            padding: 18px 22px;
        }

        .card-activity .card-header h5 {
            font-family: 'Baloo 2', sans-serif;
            color: var(--ink);
        }

        .card-activity table td,
        .card-activity table th {
            padding: 14px 22px;
        }

        .btn-brand-outline {
            border: 1.5px solid var(--teal);
            color: var(--teal);
            background: transparent;
            font-weight: 700;
            border-radius: 999px;
            transition: all .2s ease;
        }

        .btn-brand-outline:hover {
            background: var(--teal);
            color: var(--paper);
        }

        #activityTable thead th {
            background: transparent;
            color: var(--ink-soft);
            font-weight: 700;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            border-bottom: 2px dashed var(--line) !important;
        }

        #activityTable tbody tr:hover {
            background: var(--teal-light);
        }

        .badge-dipinjam {
            background: var(--sage) !important;
            border-radius: 999px;
            font-weight: 600;
        }

        .badge-kembali {
            background: var(--ink-soft) !important;
            border-radius: 999px;
            font-weight: 600;
        }
    </style>
    <div class="container-fluid p-0">

        <!-- ALERT WARNING: TELAT & MENDEKATI DEADLINE -->
        @if (isset($mendekatiDeadline) && $mendekatiDeadline->count() > 0)
            <div class="alert alert-warning border-0 shadow-sm mb-4"
                style="border-radius: 12px; background-color: var(--warning-light, #fff3cd); color: #856404;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <div class="p-2 rounded-3 me-3" style="background-color: rgba(255, 193, 7, 0.3);">
                            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Ada {{ $mendekatiDeadline->count() }} peminjaman yang sudah mendekati jatuh tempo</h6>
                            <p class="mb-0 small">Peringatan berlaku 1 hari sebelum pengembalian atau saat sudah terlambat.</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-warning fw-bold px-3 text-dark shadow-none"
                        data-bs-toggle="modal" data-bs-target="#modalPeringatanKeterlambatan"
                        style="border-radius: 8px;">
                        Peringatan Keterlambatan
                    </button>
                </div>
            </div>
        @endif

        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom dashboard-title">
            <h1 class="h2">Dashboard Pengelola</h1>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <a href="{{ route('books.index') }}" class="text-decoration-none d-block">
                    <div class="stat-card-dash stat-card-buku">
                        <div class="card-body">
                            <div class="stat-icon"><i class="bi bi-bookshelf"></i></div>
                            <h5>Total Koleksi Buku</h5>
                            <h2>{{ $totalBuku }}</h2>
                            <small>Buku Terdaftar (Pengadaan & Hibah)</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('grants.index') }}" class="text-decoration-none d-block">
                    <div class="stat-card-dash stat-card-hibah">
                        <div class="card-body">
                            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                            <h5>Hibah Perlu Dicek</h5>
                            <h2>{{ $totalHibahPending }}</h2>
                            <small>Menunggu Verifikasi Admin</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('members.index') }}" class="text-decoration-none d-block">
                    <div class="stat-card-dash stat-card-anggota">
                        <div class="card-body">
                            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                            <h5>Total Anggota</h5>
                            <h2>{{ $totalAnggota }}</h2>
                            <small>Siswa & Guru Terdaftar</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card-activity mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Aktivitas Peminjaman Terakhir</h5>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-brand-outline">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="activityTable" class="table table-hover mb-0">
                        <thead>
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
                                        <div class="fw-bold" style="color: var(--ink);">{{ $trx->member->nama_lengkap }}
                                        </div>
                                        <small class="text-muted">ID: {{ $trx->member_id }}</small>
                                    </td>
                                    <td>{{ $trx->book->judul }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>
                                        @if ($trx->status == 'pinjam')
                                            <span class="badge badge-dipinjam">Dipinjam</span>
                                        @else
                                            <span class="badge badge-kembali">Kembali</span>
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
    </div>

    @if (isset($mendekatiDeadline) && $mendekatiDeadline->count() > 0)
        <div class="modal fade" id="modalPeringatanKeterlambatan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                    <div class="modal-header border-0 px-4 py-3" style="background: linear-gradient(135deg, #fff3cd, #ffe9a8);">
                        <h5 class="modal-title fw-bold text-dark mb-0">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Peringatan Keterlambatan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3 small text-dark-50">Daftar pengembalian buku yang sudah mendekati batas atau terlambat.</div>
                        <div class="list-group list-group-flush">
                            @foreach ($mendekatiDeadline as $target)
                                @php
                                    $deadline = \Carbon\Carbon::parse($target->deadline);
                                    $isLate = $deadline->isPast();
                                    $namaAnggota = $target->member->nama_lengkap ?? 'Siswa';
                                @endphp
                                <div class="list-group-item px-0 py-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $namaAnggota }}</div>
                                            <small class="text-muted">{{ $target->book->judul ?? 'Buku' }}</small>
                                        </div>
                                        <span class="badge rounded-pill {{ $isLate ? 'bg-danger' : 'bg-warning text-dark' }} px-3 py-2">
                                            {{ $isLate ? 'Telat' : 'Jatuh Tempo' }}
                                        </span>
                                    </div>
                                    <div class="small text-muted mt-2">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        Batas pengembalian: {{ $deadline->format('d M Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                        <a href="{{ route('fines.index') }}" class="btn btn-warning fw-bold text-dark px-4"
                            style="border-radius: 10px;">Kelola Pengembalian & Denda</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endsection
