<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil! - Hibah Buku SDN 32</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .success-card { border: none; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .step-icon { width: 40px; height: 40px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #764ba2; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 text-center">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h2 class="fw-bold mt-3">Data Hibah Berhasil Dikirim!</h2>
                <p class="text-muted">ID Hibah Anda: <strong>#HB-{{ str_pad($grant->id, 4, '0', STR_PAD_LEFT) }}</strong></p>
            </div>

            <div class="card success-card text-start mb-4">
                <div class="card-body p-4 p-lg-5">
                    <h5 class="fw-bold mb-4">Langkah Selanjutnya:</h5>

                    <div class="d-flex mb-4">
                        <div class="step-icon me-3">1</div>
                        <div>
                            <h6 class="fw-bold mb-1">Siapkan Buku</h6>
                            <p class="text-muted mb-0 small">Pastikan buku "{{ $grant->judul_buku }}" dalam kondisi rapi. Selipkan catatan nama Anda di dalamnya.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="step-icon me-3">2</div>
                        <div>
                            <h6 class="fw-bold mb-1">Kirim atau Antar Langsung</h6>
                            <p class="text-muted mb-0 small">Alamat: <strong>SDN 32 Lubuk Alung</strong>, Jl. Raya Padang - Bukittinggi, Kec. Lubuk Alung, Kab. Padang Pariaman.</p>
                            <a href="https://maps.google.com/?q=-0.6789,100.2865" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-geo-alt"></i> Lihat di Google Maps
                            </a>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="step-icon me-3">3</div>
                        <div>
                            <h6 class="fw-bold mb-1">Konfirmasi Kedatangan</h6>
                            <p class="text-muted mb-0 small">Hubungi petugas kami jika Anda ingin mengirim via ekspedisi.</p>
                            <a href="https://wa.me/6281267797130?text=Halo%20Admin%20Perpus%2C%20saya%20ingin%20mengirim%20buku%20hibah%20%23HB-{{ $grant->id }}" class="btn btn-sm btn-success mt-2">
                                <i class="bi bi-whatsapp"></i> Chat Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('welcome') }}" class="btn btn-link text-decoration-none">Kembali ke Beranda</a>
        </div>
    </div>
</div>

</body>
</html>
