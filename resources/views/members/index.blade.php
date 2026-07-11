@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Data Anggota Perpustakaan</h1>
        <a href="{{ route('members.create') }}" class="btn btn-primary">Tambah Anggota</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Peran</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $m)
                        <tr>
                            <td>{{ $m->nisn ?? '-' }}</td>
                            <td>
                                <strong>{{ $m->nama_lengkap }}</strong><br>
                                
                            </td>
                            <td><span class="badge bg-info text-dark">{{ strtoupper($m->peran) }}</span></td>
                            <td>{{ $m->no_hp }}</td>
                            <td>
                                <a href="{{ route('members.print_card', $m->id) }}" target="_blank"
                                    class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-card-heading"></i> Cetak Kartu
                                </a>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
