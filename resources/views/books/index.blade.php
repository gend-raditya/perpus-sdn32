@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Koleksi Buku</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
            <i class="bi bi-plus-circle"></i> Tambah Buku Baru
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>QR Code</th>
                        <th>Judul Buku</th>
                        <th>Penulis / Penerbit</th>
                        <th>Asal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="p-2 bg-white border d-inline-block">
                                    {!! QrCode::size(80)->generate(route('books.scan', $item->kode_qr)) !!}
                                    <div class="text-center mt-1" style="font-size: 10px; font-weight: bold;">
                                        {{ $item->kode_qr }}
                                    </div>
                                </div>
                            </td>
                            <td><strong>{{ $item->judul }}</strong></td>
                            <td>{{ $item->penulis }} <br> <span class="text-muted">{{ $item->penerbit ?? '-' }}</span></td>
                            <td><span
                                    class="badge {{ $item->asal_buku == 'hibah' ? 'bg-info' : 'bg-secondary' }}">{{ strtoupper($item->asal_buku) }}</span>
                            </td>
                            <td><span class="badge bg-success">{{ strtoupper($item->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addBookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('books.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Input Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" required
                                placeholder="Contoh: Pemrograman Laravel">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="penulis" class="form-control" required placeholder="Nama Penulis">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control"
                                placeholder="Nama Penerbit (Opsional)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asal Buku</label>
                            <select name="asal_buku" class="form-select" required>
                                <option value="pengadaan">Pengadaan Sekolah</option>
                                <option value="hibah">Hibah Siswa/Alumni</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan & Generate QR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
