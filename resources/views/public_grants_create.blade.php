<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Hibah Buku - SDN 32 Lubuk Alung</title>

    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-1: #6d3fb0;
            --brand-2: #4f6ee0;
        }

        body {
            background: #fbf9f4;
            background-image:
                radial-gradient(circle at 8% 8%, rgba(109, 63, 176, .07), transparent 40%),
                radial-gradient(circle at 92% 15%, rgba(79, 110, 224, .07), transparent 40%);
            font-family: 'Inter', sans-serif;
            color: #2b2b33;
        }

        .hero-icon {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--brand-1), var(--brand-2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            color: #fff;
            margin: 0 auto 18px;
            box-shadow: 0 10px 24px rgba(109, 63, 176, .28);
        }

        .eyebrow {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--brand-1);
            margin-bottom: 6px;
        }

        .form-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 20px 45px rgba(30, 20, 60, 0.08);
            overflow: hidden;
        }

        .form-card .accent-bar {
            height: 6px;
            background: linear-gradient(90deg, var(--brand-1), var(--brand-2));
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #8b8fa3;
            margin: 26px 0 14px;
        }

        .section-label:first-child {
            margin-top: 4px;
        }

        .section-label i {
            color: var(--brand-1);
            font-size: .95rem;
        }

        .form-label {
            font-size: .87rem;
            color: #3a3a45;
        }

        .form-control,
        .form-select {
            transition: box-shadow .15s ease, background-color .15s ease, border-color .15s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff !important;
            border-color: var(--brand-2) !important;
            box-shadow: 0 0 0 3px rgba(79, 110, 224, .15) !important;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--brand-1), var(--brand-2));
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: .95rem;
            letter-spacing: .01em;
            transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
            box-shadow: 0 10px 20px rgba(79, 110, 224, .25);
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            filter: brightness(1.04);
            box-shadow: 0 14px 26px rgba(79, 110, 224, .32);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-soft-success {
            background: #eaf7ef;
            color: #1f6e42;
            border: 1px solid #cdeedb;
        }

        .alert-soft-danger {
            background: #fdeeee;
            color: #a13333;
            border: 1px solid #f6d3d3;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .foot-note {
            font-size: .78rem;
            color: #9a9ba8;
        }

        /* Styling Custom Checkbox & Dropdown Button */
        .category-box {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
        }

        .custom-check-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            background: #ffffff;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .custom-check-card:hover {
            border-color: var(--brand-2);
            background: #f4f6ff;
        }

        /* Tombol Trigger Dropdown Kategori */
        .category-toggle-btn {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            border-radius: 12px;
            color: #3a3a45;
            font-weight: 600;
            text-align: left;
            transition: all 0.2s ease;
        }

        .category-toggle-btn:hover {
            background: #f1f3f9;
            border-color: #cbd5e1;
        }

        /* Efek rotasi panah saat dropdown terbuka */
        .category-toggle-btn .arrow-icon {
            transition: transform 0.3s ease;
        }

        .category-toggle-btn[aria-expanded="true"] .arrow-icon {
            transform: rotate(180deg);
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <!-- Header Section -->
                <div class="text-center mb-4">
                    <div class="hero-icon">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div class="eyebrow">SDN 32 Lubuk Alung</div>
                    <h2 class="fw-bold mb-2">Donasikan Buku Anda</h2>
                    <p class="text-muted mb-0">Isi data di bawah untuk memberikan kontribusi bagi pendidikan adik-adik
                        kami.</p>
                </div>

                <!-- Alert Session Success -->
                @if (session('success'))
                    <div class="alert alert-soft-success border-0 shadow-sm mb-4 d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill mt-1"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <!-- Alert Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-soft-danger border-0 shadow-sm mb-4 d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Main Form Card -->
                <div class="card form-card">
                    <div class="accent-bar"></div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('public.grants.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Section 1: Data Donatur -->
                            <div class="section-label">
                                <i class="bi bi-person-fill"></i>Data Donatur
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap Anda</label>
                                    <input type="text" name="nama_pemberi" class="form-control bg-light border-0 p-3"
                                        placeholder="Contoh: Budi Santoso" value="{{ old('nama_pemberi') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Kontak Donatur (No. HP / WA)</label>
                                    <input type="text" name="kontak_pemberi"
                                        class="form-control bg-light border-0 p-3" placeholder="Contoh: 081234567890"
                                        value="{{ old('kontak_pemberi') }}" required>
                                </div>
                            </div>

                            <!-- Section 2: Detail Buku -->
                            <div class="section-label">
                                <i class="bi bi-journal-richtext"></i>Detail Buku
                            </div>

                            <!-- Opsi Multiple Kategori (Accordion / Collapsible) -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kategori Buku</label>

                                @php
                                    $categories = [
                                        'Buku Pelajaran' => 'Buku Pelajaran / Paket Sekolah',
                                        'Buku Cerita / Novel' => 'Buku Cerita / Dongeng / Novel',
                                        'Ensiklopedia / Pengetahuan' => 'Buku Pengetahuan Umum / Ensiklopedia',
                                        'Keagamaan' => 'Buku Keagamaan',
                                        'Majalah / Komik Anak' => 'Majalah / Komik Anak',
                                    ];
                                    $oldCategories = old('kategori_buku', []);
                                    // Otomatis buka accordion jika ada pilihan sebelumnya / error validasi
                                    $hasSelected = count($oldCategories) > 0;
                                @endphp

                                <!-- Tombol Panah Trigger -->
                                <button type="button" class="btn category-toggle-btn" data-bs-toggle="collapse"
                                    data-bs-target="#categoryCollapse"
                                    aria-expanded="{{ $hasSelected ? 'true' : 'false' }}"
                                    aria-controls="categoryCollapse">
                                    <span>
                                        Pilih Kategori Buku
                                        <small class="text-muted fw-normal ms-1">(Bisa pilih lebih dari satu)</small>
                                    </span>
                                    <i class="bi bi-chevron-down arrow-icon"></i>
                                </button>

                                <!-- Area Konten Kategori (Accordion Body) -->
                                <div class="collapse mt-2 {{ $hasSelected ? 'show' : '' }}" id="categoryCollapse">
                                    <div class="category-box">
                                        <div class="row g-2">
                                            @foreach ($categories as $value => $label)
                                                <div class="col-12">
                                                    <div class="form-check custom-check-card m-0">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="kategori_buku[]" value="{{ $value }}"
                                                            id="cat_{{ $loop->index }}"
                                                            {{ in_array($value, $oldCategories) ? 'checked' : '' }}>
                                                        <label class="form-check-label w-100 ms-1 style-cursor-pointer"
                                                            for="cat_{{ $loop->index }}">
                                                            {{ $label }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-semibold">Alamat Pengirim</label>
                                    <textarea name="alamat_pengirim" class="form-control bg-light border-0 p-3" rows="1"
                                        placeholder="Alamat lengkap Anda" required>{{ old('alamat_pengirim') }}</textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Jumlah (Eksemplar)</label>
                                    <input type="number" name="jumlah_eksemplar"
                                        class="form-control bg-light border-0 p-3"
                                        value="{{ old('jumlah_eksemplar', 1) }}" min="1">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pesan / Deskripsi Kondisi Buku</label>
                                <textarea name="sinopsis" class="form-control bg-light border-0 p-3" rows="3"
                                    placeholder="Tuliskan daftar judul buku, sinopsis ringkas, atau catatan kondisi buku..." required>{{ old('sinopsis') }}</textarea>
                            </div>

                            <!-- Section 3: Lampiran -->
                            <div class="section-label">
                                <i class="bi bi-image"></i>Lampiran
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Foto Buku (Opsional)</label>
                                <input type="file" name="foto_buku" class="form-control bg-light border-0 p-3"
                                    accept="image/*">
                                <div class="foot-note mt-1">Format JPG/PNG, membantu petugas memverifikasi kondisi
                                    buku.</div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-submit fw-bold text-white">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Data Hibah
                                </button>
                                <a href="{{ route('welcome') }}"
                                    class="btn btn-link back-link justify-content-center text-muted mt-3 text-decoration-none small">
                                    <i class="bi bi-arrow-left"></i>Kembali ke Beranda
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer Note -->
                <p class="text-center foot-note mt-4 mb-0">
                    Data Anda akan diverifikasi oleh petugas perpustakaan sebelum disetujui.
                </p>

            </div>
        </div>
    </div>

    <!-- JS Bootstrap JS (Diperlukan agar fitur Collapse/Accordion berfungsi) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
