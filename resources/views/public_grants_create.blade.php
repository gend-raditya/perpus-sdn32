<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Hibah Buku - SDN 32 Lubuk Alung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fcfaf5; font-family: 'Inter', sans-serif; }
        .form-card { border: none; border-radius: 15px; background: #ffffff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-submit { background-color: #764ba2; border: none; padding: 12px; border-radius: 8px; }
        .btn-submit:hover { background-color: #667eea; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Donasi Buku Anda</h2>
                <p class="text-muted">Isi data di bawah untuk memberikan kontribusi bagi pendidikan adik-adik kami.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card form-card">
                <div class="card-body p-4">
                    <form action="{{ route('public.grants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap Anda</label>
                            <input type="text" name="nama_pemberi" class="form-control bg-light border-0 p-3" placeholder="Contoh: Budi Santoso" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Buku</label>
                            <input type="text" name="judul_buku" class="form-control bg-light border-0 p-3" required>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">Penulis</label>
                                <input type="text" name="penulis_buku" class="form-control bg-light border-0 p-3" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Jumlah</label>
                                <input type="number" name="jumlah_eksemplar" class="form-control bg-light border-0 p-3" value="1" min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Foto Buku (Opsional)</label>
                            <input type="file" name="foto_buku" class="form-control bg-light border-0">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-submit fw-bold">Kirim Data Hibah</button>
                            <a href="{{ route('welcome') }}" class="btn btn-link text-muted mt-2 text-decoration-none small">Kembali ke Beranda</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
