@extends('layouts.app')

@section('content')
    <style>
        .page-title-block h1 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--ink);
        }

        .card-form {
            border: 1px solid var(--line) !important;
            border-radius: 18px !important;
            background: #fff;
            box-shadow: 0px 14px 30px -14px rgba(30, 42, 34, 0.18) !important;
        }

        .card-form .form-label {
            font-weight: 700;
            color: var(--ink-soft);
            font-size: .88rem;
        }

        .card-form .form-control,
        .card-form .form-select {
            border: 1.5px solid var(--line);
            border-radius: 10px;
        }

        .card-form .form-control:focus,
        .card-form .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 4px var(--teal-light);
        }

        .card-form .form-text { color: var(--ink-soft); }

        .alert-danger {
            background: rgba(189, 63, 92, 0.1) !important;
            border: 1.5px dashed var(--berry) !important;
            border-left: 5px solid var(--berry) !important;
            color: var(--ink) !important;
            border-radius: 14px !important;
        }

        .alert-danger ul { margin-bottom: 0; }

        .card-form hr {
            border-top: 1.5px dashed var(--line);
            opacity: 1;
        }

        .btn-brand-primary {
            background: var(--teal);
            border: none;
            color: var(--paper);
            font-weight: 700;
            border-radius: 12px;
            padding: 10px 22px;
            box-shadow: 3px 3px 0 var(--teal-dark);
            transition: all .2s ease;
        }

        .btn-brand-primary:hover {
            color: var(--paper);
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0 var(--teal-dark);
        }

        .btn-brand-cancel {
            background: transparent;
            border: 1.5px solid var(--ink-soft);
            color: var(--ink-soft);
            border-radius: 12px;
            padding: 10px 22px;
            font-weight: 600;
        }

        .btn-brand-cancel:hover {
            background: var(--ink-soft);
            color: var(--paper);
        }
    </style>

    <div class="pt-3 pb-2 mb-3 border-bottom page-title-block">
        <h1 class="h2">Tambah Anggota Baru</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-form shadow-sm">
                <div class="card-body">
                    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
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
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control"
                                    placeholder="Contoh: Budi Santoso" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NISN (Wajib untuk Siswa)</label>
                                <input type="text" name="nisn" class="form-control" placeholder="10 digit nomor">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peran</label>
                                <select name="peran" class="form-select" required>
                                    <option value="siswa">Siswa</option>
                                    {{-- <option value="alumni">Alumni</option> --}}
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
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat tempat tinggal siswa"></textarea>
                        </div>

                        {{-- <div class="mb-4">
                            <label class="form-label fw-bold">Foto Anggota (Opsional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, atau PNG.</div>
                        </div> --}}

                        <hr>
                        <button type="submit" class="btn btn-brand-primary">Simpan Anggota</button>
                        <a href="{{ route('members.index') }}" class="btn btn-brand-cancel">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
