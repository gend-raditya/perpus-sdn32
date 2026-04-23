@extends('layouts.app')

@section('content')
<div class="container pt-4">
    <div class="card shadow border-0 overflow-hidden" style="border-radius: 15px;">
        <div class="bg-primary p-4 text-center text-white">
            <h4 class="mb-0">Profil Anggota Perpustakaan</h4>
        </div>
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="d-inline-block p-2 bg-white shadow-sm mb-3" style="border-radius: 10px;">
                    {!! QrCode::size(120)->generate(Request::url()) !!}
                </div>
                <h3 class="fw-bold mb-0">{{ $member->nama_lengkap }}</h3>
                <span class="badge bg-info text-dark">{{ strtoupper($member->peran) }}</span>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">NISN</span>
                    <span class="fw-bold">{{ $member->nisn ?? '-' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Email</span>
                    <span class="fw-bold">{{ $member->user->email }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">No. HP Wali</span>
                    <span class="fw-bold">{{ $member->no_hp ?? '-' }}</span>
                </li>
                <li class="list-group-item">
                    <span class="text-muted d-block mb-1">Alamat</span>
                    <span class="fw-bold">{{ $member->alamat ?? 'Tidak ada alamat' }}</span>
                </li>
            </ul>

            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>
@endsection
