@extends('layouts.app')

@section('content')
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tambah Anggota Baru</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('members.store') }}" method="POST">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control"
                                    placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email (Untuk Login)</label>
                                <input type="email" name="email" class="form-control" placeholder="budi@example.com"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NISN (Kosongkan jika Alumni)</label>
                                <input type="text" name="nisn" class="form-control" placeholder="10 digit nomor">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peran</label>
                                <select name="peran" class="form-select" required>
                                    <option value="siswa">Siswa</option>
                                    <option value="alumni">Alumni</option>
                                    <option value="guru">Guru</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. HP Orang Tua / Wali (Opsional)</label>
                            <input type="text" name="no_hp" class="form-control"
                                placeholder="0812... atau kosongkan jika tidak ada">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="alert alert-info">
                            <small><strong>Note:</strong> Password default untuk anggota baru adalah
                                <strong>12345678</strong></small>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">Simpan Anggota</button>
                        <a href="{{ route('members.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
