@extends('layouts.app')

@section('content')
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Input Buku Hibah / Sumbangan</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('grants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="mb-3 text-primary">Informasi Donatur</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pemberi</label>
                                <input type="text" name="nama_pemberi" class="form-control"
                                    placeholder="Contoh: Alumni Angkatan 2010" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP / Kontak</label>
                                <input type="text" name="kontak_pemberi" class="form-control" placeholder="0812...">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3 text-primary">Data Buku</h5>
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul_buku" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Penulis</label>
                                <input type="text" name="penulis_buku" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah (Eks)</label>
                                <input type="number" name="jumlah_eksemplar" class="form-control" value="1"
                                    min="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kondisi Buku / Deskripsi</label>
                            <textarea name="deskripsi_kondisi" class="form-control" rows="3"
                                placeholder="Contoh: Buku bekas layak pakai, sampul agak lecet"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Buku (Bukti Hibah)</label>
                            <input type="file" name="foto_buku" class="form-control" accept="image/*">
                            <small class="text-muted">Opsional. Gunakan untuk dokumentasi serah terima.</small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">Simpan Data Hibah</button>
                            <a href="{{ route('grants.index') }}" class="btn btn-light">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
