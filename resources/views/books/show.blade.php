@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white text-center py-3">
                <h5 class="mb-0">Informasi Hasil Scan</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    {!! QrCode::size(120)->generate($book->kode_qr) !!}
                    <div class="mt-2 fw-bold text-muted">{{ $book->kode_qr }}</div>
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <small class="text-muted">Judul Buku</small>
                        <h5 class="mb-0">{{ $book->judul }}</h5>
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted">Penulis</small>
                        <p class="mb-0 fw-bold">{{ $book->penulis }}</p>
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted">Asal Buku</small>
                        <div>
                            <span class="badge {{ $book->asal_buku == 'hibah' ? 'bg-info' : 'bg-secondary' }}">
                                {{ strtoupper($book->asal_buku) }}
                            </span>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted">Status Saat Ini</small>
                        <div>
                            @if ($book->status == 'tersedia')
                                <span class="badge bg-success p-2">DAPAT DIPINJAM</span>
                            @else
                                <span class="badge bg-danger p-2">SEDANG DIPINJAM</span>
                            @endif
                        </div>
                    </li>
                </ul>

                <div class="d-grid gap-2 mt-4">
                    @if ($book->status == 'tersedia')
                        <button class="btn btn-success btn-lg">
                            <i class="bi bi-arrow-right-circle"></i> Proses Pinjam
                        </button>
                    @else
                        <button class="btn btn-warning btn-lg">
                            <i class="bi bi-arrow-left-circle"></i> Proses Kembali
                        </button>
                    @endif
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Kembali ke Katalog</a>
                </div>
            </div>
        </div>
    </div>
@endsection
