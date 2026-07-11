@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Verifikasi Hibah Buku</h1>
    <a href="{{ route('grants.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Catat Hibah Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Donatur (Pemberi)</th>
                        <th>Info Buku</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grants as $key => $grant)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <strong>{{ $grant->nama_pemberi }}</strong><br>
                            <small class="text-muted">Kontak: {{ $grant->kontak_pemberi ?? '-' }}</small>
                        </td>
                        <td>
                            <strong>{{ $grant->judul_buku }}</strong><br>
                            <span class="text-muted">Penulis: {{ $grant->penulis_buku }}</span><br>
                            <small class="badge bg-info text-dark">{{ $grant->jumlah_eksemplar }} Eks</small>
                        </td>
                        <td>{{ $grant->deskripsi_kondisi ?? '-' }}</td>
                        <td>
                            @if($grant->status_hibah == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                            @elseif($grant->status_hibah == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($grant->status_hibah == 'pending')
                                    <form action="{{ route('grants.approve', $grant->id) }}" method="POST" onsubmit="return confirm('Apakah buku ini layak masuk katalog?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('grants.reject', $grant->id) }}" method="POST" onsubmit="return confirm('Tolak hibah buku ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada pengajuan hibah buku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
