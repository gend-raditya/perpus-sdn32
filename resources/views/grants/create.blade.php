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
                            <ul class="mb-0">
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
                                    placeholder="Contoh: Alumni Angkatan 2010" value="{{ old('nama_pemberi') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. HP / Kontak</label>
                                <input type="text" name="kontak_pemberi" class="form-control"
                                    placeholder="0812..." value="{{ old('kontak_pemberi') }}">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3 text-primary">Data Buku</h5>

                        <div class="mb-3">
                            <label class="form-label">Kategori Buku</label>
                            <select name="kategori_buku" class="form-select" required>
                                <option value="" disabled {{ old('kategori_buku') == '' ? 'selected' : '' }}>-- Pilih Kategori Buku --</option>
                                <option value="Buku Pelajaran" {{ old('kategori_buku') == 'Buku Pelajaran' ? 'selected' : '' }}>Buku Pelajaran / Paket Sekolah</option>
                                <option value="Buku Cerita / Novel" {{ old('kategori_buku') == 'Buku Cerita / Novel' ? 'selected' : '' }}>Buku Cerita / Dongeng / Novel</option>
                                <option value="Ensiklopedia / Pengetahuan" {{ old('kategori_buku') == 'Ensiklopedia / Pengetahuan' ? 'selected' : '' }}>Buku Pengetahuan Umum / Ensiklopedia</option>
                                <option value="Keagamaan" {{ old('kategori_buku') == 'Keagamaan' ? 'selected' : '' }}>Buku Keagamaan</option>
                                <option value="Majalah / Komik Anak" {{ old('kategori_buku') == 'Majalah / Komik Anak' ? 'selected' : '' }}>Majalah / Komik Anak</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Alamat Pengirim</label>
                                <textarea name="alamat_pengirim" class="form-control" rows="1"
                                    placeholder="Alamat lengkap donatur" required>{{ old('alamat_pengirim') }}</textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah (Eks)</label>
                                <input type="number" name="jumlah_eksemplar" class="form-control"
                                    value="{{ old('jumlah_eksemplar', 1) }}" min="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Buku (Bukti Hibah / Sampul)</label>
                            <input type="file" name="foto_buku" class="form-control" accept="image/*">
                            <small class="text-muted">Opsional. Gunakan untuk dokumentasi serah terima atau sampul katalog.</small>
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
