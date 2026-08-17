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
            --brand-soft: #f4f1ff;
            --line: #e7e9f3;
            --text: #2d2f3a;
            --muted: #6d7285;
        }

        body {
            background: linear-gradient(180deg, #faf8ff 0%, #f5f7fb 100%);
            font-family: 'Inter', sans-serif;
            color: var(--text);
        }

        .hero-icon {
            width: 62px;
            height: 62px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--brand-1), var(--brand-2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            margin: 0 auto 14px;
            box-shadow: 0 10px 24px rgba(109, 63, 176, .26);
        }

        .eyebrow {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--brand-1);
            margin-bottom: 6px;
        }

        .form-card {
            border: 1px solid rgba(147, 153, 177, 0.18);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 18px 44px rgba(31, 36, 55, 0.06);
            overflow: hidden;
        }

        .form-card .accent-bar {
            height: 5px;
            background: linear-gradient(90deg, var(--brand-1), var(--brand-2));
        }

        .section-box {
            background: linear-gradient(180deg, #ffffff 0%, #fafafe 100%);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 1rem 1rem 0.8rem;
            margin-bottom: 0.9rem;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .73rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.8rem;
        }

        .section-label i {
            color: var(--brand-1);
            font-size: .9rem;
        }

        .form-label {
            font-size: .82rem;
            color: #4a4d5f;
            margin-bottom: 0.38rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #e3e6f1;
            border-radius: 12px;
            background: #fafbff;
            padding: 0.72rem 0.9rem;
            font-size: 0.94rem;
            transition: all .15s ease;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff !important;
            border-color: rgba(79, 110, 224, 0.7) !important;
            box-shadow: 0 0 0 3px rgba(79, 110, 224, 0.12) !important;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--brand-1), var(--brand-2));
            border: none;
            padding: 0.9rem 1rem;
            border-radius: 12px;
            font-size: .94rem;
            letter-spacing: .01em;
            transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
            box-shadow: 0 10px 18px rgba(79, 110, 224, .18);
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            filter: brightness(1.04);
            box-shadow: 0 12px 22px rgba(79, 110, 224, .24);
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
            font-size: .76rem;
            color: #8d91a0;
        }

        .category-box {
            background: #f8f9ff;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px;
        }

        .custom-check-card {
            border: 1px solid #e7eaf6;
            border-radius: 10px;
            padding: 10px 12px;
            background: #ffffff;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .custom-check-card:hover {
            border-color: rgba(79, 110, 224, 0.5);
            background: #f5f7ff;
        }

        .category-toggle-btn {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9ff;
            border: 1px solid var(--line);
            padding: 0.8rem 0.9rem;
            border-radius: 12px;
            color: #3d4152;
            font-weight: 600;
            text-align: left;
            transition: all 0.2s ease;
        }

        .category-toggle-btn:hover {
            background: #f3f5ff;
            border-color: rgba(79, 110, 224, 0.35);
        }

        .category-toggle-btn .arrow-icon {
            transition: transform 0.3s ease;
        }

        .category-toggle-btn[aria-expanded="true"] .arrow-icon {
            transform: rotate(180deg);
        }

        /* Book card header styling for better visibility */
        .book-card { border: 1px solid #e9eefc; border-radius: 10px; overflow: hidden; }
        .book-header { background: #f6f9ff; padding: 10px 12px; display:flex; align-items:center; justify-content:space-between; gap:8px; }
        .book-header .book-title { font-weight:700; color:#2d3750; }
        .book-header .btn-remove-book { font-size:0.78rem; }
        .book-body { padding: 14px !important; }
        .book-toggle .arrow-icon { transition: transform .25s ease; }
        .book-header.collapsed .arrow-icon { transform: rotate(0deg); }
        .book-header:not(.collapsed) .arrow-icon { transform: rotate(180deg); }

        @media (max-width: 575.98px) {
            .card-body.p-4.p-md-5 {
                padding: 1rem !important;
            }

            .section-box {
                padding: 0.9rem 0.9rem 0.7rem;
            }
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">

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
                        <form id="grantForm" action="{{ route('public.grants.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="section-box">
                                <div class="section-label">
                                    <i class="bi bi-person-fill"></i>Data Donatur
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nama Lengkap Anda</label>
                                        <input type="text" name="nama_pemberi" class="form-control"
                                            placeholder="Contoh: Budi Santoso" value="{{ old('nama_pemberi') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">No. HP / Kontak</label>
                                        <input type="tel" name="kontak_pemberi" class="form-control"
                                            placeholder="0812..." value="{{ old('kontak_pemberi') }}"
                                            required maxlength="13" inputmode="numeric" pattern="[0-9]{1,13}">
                                    </div>
                                </div>
                            </div>

                            <div class="section-box" id="booksContainer">
                                <div class="section-label">
                                    <i class="bi bi-journal-richtext"></i>Detail Buku
                                </div>
                                <div class="book-block" data-index="0">
                                    <div class="book-card mb-3 border rounded">
                                        <div class="book-header p-2 d-flex justify-content-between align-items-center">
                                            <div>
                                                <button type="button" class="btn btn-link p-0 book-toggle" aria-expanded="true">
                                                    <strong class="book-title">Judul 1</strong>
                                                    <i class="bi bi-chevron-down ms-2 arrow-icon" style="transition: transform .25s"></i>
                                                </button>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-book d-none">Hapus Judul</button>
                                            </div>
                                        </div>
                                        <div class="book-body p-3">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Judul Buku</label>
                                                    <input type="text" name="books[0][judul_buku]" class="form-control"
                                                        placeholder="Contoh: Matematika Kelas 5 SD" value="{{ old('books.0.judul_buku') }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold">ISBN</label>
                                                    <input type="text" name="books[0][isbn]" class="form-control"
                                                        placeholder="978-602-xxx" value="{{ old('books.0.isbn') }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold">Tahun Terbit</label>
                                                    <select name="books[0][tahun_terbit]" class="form-select" required>
                                                        <option value="" disabled {{ old('books.0.tahun_terbit') == '' ? 'selected' : '' }}>-- Pilih Tahun --</option>
                                                        @php $maxYear = date('Y'); @endphp
                                                        @for ($year = 2000; $year <= $maxYear; $year++)
                                                            <option value="{{ $year }}" {{ old('books.0.tahun_terbit') == (string) $year ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Penulis</label>
                                                    <input type="text" name="books[0][penulis_buku]" class="form-control"
                                                        placeholder="Nama penulis" value="{{ old('books.0.penulis_buku') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Penerbit</label>
                                                    <input type="text" name="books[0][penerbit_buku]" class="form-control"
                                                        placeholder="Nama penerbit" value="{{ old('books.0.penerbit_buku') }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Kategori Buku</label>
                                                    <select name="books[0][kategori_buku]" class="form-select" required>
                                                        <option value="" disabled {{ old('books.0.kategori_buku') == '' ? 'selected' : '' }}>-- Pilih Kategori --</option>
                                                        <option value="Buku Pelajaran" {{ old('books.0.kategori_buku') == 'Buku Pelajaran' ? 'selected' : '' }}>Buku Pelajaran / Paket Sekolah</option>
                                                        <option value="Buku Cerita / Novel" {{ old('books.0.kategori_buku') == 'Buku Cerita / Novel' ? 'selected' : '' }}>Buku Cerita / Dongeng / Novel</option>
                                                        <option value="Ensiklopedia / Pengetahuan" {{ old('books.0.kategori_buku') == 'Ensiklopedia / Pengetahuan' ? 'selected' : '' }}>Buku Pengetahuan Umum / Ensiklopedia</option>
                                                        <option value="Keagamaan" {{ old('books.0.kategori_buku') == 'Keagamaan' ? 'selected' : '' }}>Buku Keagamaan</option>
                                                        <option value="Majalah / Komik Anak" {{ old('books.0.kategori_buku') == 'Majalah / Komik Anak' ? 'selected' : '' }}>Majalah / Komik Anak</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Kondisi Buku</label>
                                                    <select name="books[0][kondisi_buku]" class="form-select" required>
                                                        <option value="" disabled {{ old('books.0.kondisi_buku') == '' ? 'selected' : '' }}>-- Pilih Kondisi --</option>
                                                        <option value="Baru" {{ old('books.0.kondisi_buku') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                                        <option value="Pernah Dipakai" {{ old('books.0.kondisi_buku') == 'Pernah Dipakai' ? 'selected' : '' }}>Pernah Dipakai</option>
                                                        <option value="Rusak Ringan" {{ old('kondisi_buku') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Bahasa Buku</label>
                                                    <input type="text" name="books[0][bahasa]" list="bahasaList" class="form-control"
                                                        placeholder="Cari bahasa..." value="{{ old('books.0.bahasa') }}" required>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Halaman</label>
                                                    <input type="number" name="books[0][jumlah_halaman]" min="1" class="form-control"
                                                        value="{{ old('books.0.jumlah_halaman') }}" placeholder="120" required>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Jumlah Buku</label>
                                                    <input type="number" name="books[0][jumlah_eksemplar]" min="1" class="form-control"
                                                        value="{{ old('books.0.jumlah_eksemplar', 1) }}" required>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Sinopsis Buku</label>
                                                    <textarea name="books[0][sinopsis]" class="form-control" rows="3"
                                                        placeholder="Tuliskan ringkasan singkat isi buku..." required>{{ old('books.0.sinopsis') }}</textarea>
                                                </div>

                                                <div class="col-md-12 mt-2">
                                                    <label class="form-label fw-semibold">Foto Buku</label>
                                                    <input type="file" name="books[0][foto_buku]" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                                                    <div class="foot-note mt-2">Wajib upload. Format JPG, JPEG, atau PNG. Maksimal 2 MB.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" id="addBookBtn" class="btn btn-primary btn-sm px-4">Tambah Judul Lain</button>
                            </div>


                            <div class="section-box">
                                <div class="section-label">
                                    <i class="bi bi-geo-alt"></i>Lokasi & Dokumen
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Kota / Kabupaten</label>
                                        <select id="kota" class="form-select" required>
                                            <option value="">-- Pilih Kota --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Detail Alamat</label>
                                        <input type="text" id="detail_alamat" class="form-control"
                                            placeholder="RT/RW, jalan, nomor rumah" required>
                                    </div>
                                    <input type="hidden" name="alamat_pengirim" id="alamat_pengirim" value="{{ old('alamat_pengirim') }}">

                                    <div class="col-md-12 mt-1">
                                        <small class="text-muted">Alamat otomatis tersusun dari kota/kabupaten dan detail alamat.</small>
                                    </div>

                                </div>
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary btn-submit fw-bold text-white">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Data Hibah
                                </button>
                                <a href="{{ route('welcome') }}"
                                    class="btn btn-link back-link justify-content-center text-muted mt-2 text-decoration-none small">
                                    <i class="bi bi-arrow-left"></i>Kembali ke Beranda
                                </a>
                            </div>
                            <datalist id="bahasaList">
                                <option value="Bahasa Indonesia">
                                <option value="Bahasa Inggris">
                                <option value="Bahasa Arab">
                                <option value="Bahasa Mandarin">
                                <option value="Bahasa Jepang">
                                <option value="Bahasa Korea">
                                <option value="Bahasa Jerman">
                                <option value="Bahasa Prancis">
                                <option value="Bahasa Belanda">
                                <option value="Bahasa Melayu">
                                <option value="Bahasa Sunda">
                                <option value="Bahasa Bali">
                                <option value="Bahasa Jawa">
                                <option value="Bahasa Batak">
                                <option value="Bahasa Minang">
                                <option value="Bahasa Tamil">
                            </datalist>

                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const form = document.getElementById('grantForm');
                                const phoneInput = form?.querySelector('input[name="kontak_pemberi"]');
                                const kotaSelect = document.getElementById('kota');
                                const detailAlamatInput = document.getElementById('detail_alamat');
                                const alamatHiddenInput = document.getElementById('alamat_pengirim');

                                const kotaSumateraBarat = [
                                    'Padang', 'Padang Panjang', 'Bukittinggi', 'Payakumbuh', 'Sawahlunto', 'Solok', 'Pariaman',
                                    'Agam', 'Dharmasraya', 'Lima Puluh Kota', 'Pasaman', 'Pasaman Barat', 'Pesisir Selatan',
                                    'Sijunjung', 'Solok Selatan', 'Tanah Datar', 'Kepulauan Mentawai'
                                ];

                                const setAlamatValue = () => {
                                    const kota = kotaSelect.value;
                                    const detail = detailAlamatInput.value.trim();

                                    if (kota && detail) {
                                        alamatHiddenInput.value = `Kota ${kota}, ${detail}`;
                                    } else {
                                        alamatHiddenInput.value = '';
                                    }
                                };

                                kotaSumateraBarat.forEach((item) => {
                                    const option = document.createElement('option');
                                    option.value = item;
                                    option.textContent = item;
                                    kotaSelect.appendChild(option);
                                });

                                kotaSelect?.addEventListener('change', setAlamatValue);
                                detailAlamatInput?.addEventListener('input', setAlamatValue);

                                phoneInput?.addEventListener('input', function () {
                                    this.value = this.value.replace(/\D/g, '').slice(0, 13);
                                });


                                form?.addEventListener('submit', function (event) {
                                    const requiredFields = form.querySelectorAll('[required]');
                                    for (const field of requiredFields) {
                                        if (field.type === 'file') {
                                            if (!field.files || !field.files.length) {
                                                alert('Semua kolom wajib diisi, termasuk foto buku.');
                                                field.focus();
                                                event.preventDefault();
                                                return;
                                            }
                                        } else if (field.id !== 'alamat_pengirim' && !field.value.trim()) {
                                            alert('Semua kolom wajib diisi. Mohon lengkapi form terlebih dahulu.');
                                            field.focus();
                                            event.preventDefault();
                                            return;
                                        }
                                    }

                                    if (!kotaSelect.value || !detailAlamatInput.value.trim()) {
                                        alert('Alamat pengirim belum lengkap. Pilih kota/kabupaten dan detail alamat.');
                                        event.preventDefault();
                                        return;
                                    }

                                    setAlamatValue();

                                    if (phoneInput) {
                                        const phoneValue = phoneInput.value.trim();
                                        if (!/^\d{1,13}$/.test(phoneValue)) {
                                            alert('Nomor HP hanya boleh berisi angka, maksimal 13 digit.');
                                            phoneInput.focus();
                                            event.preventDefault();
                                            return;
                                        }
                                    }

                                });
                            });
                        </script>
                    </div>
                </div>

                <!-- Footer Note -->
                <p class="text-center foot-note mt-4 mb-0">
                    Data Anda akan diverifikasi oleh petugas perpustakaan sebelum disetujui.
                </p>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Reusable helper: update file input listeners and validation across dynamic blocks
            const form = document.getElementById('grantForm');

            const validateFile = (input) => {
                const file = input.files && input.files[0];
                if (!file) return true;

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 5 * 1024 * 1024;

                if (!validTypes.includes(file.type)) {
                    alert('Format foto tidak valid. Gunakan file JPG, JPEG, atau PNG.');
                    input.value = '';
                    return false;
                }

                if (file.size > maxSize) {
                    alert('Ukuran foto terlalu besar. Maksimal 2 MB.');
                    input.value = '';
                    return false;
                }

                return true;
            };

            const bindFileInputs = () => {
                const fileInputs = form.querySelectorAll('input[type="file"]');
                fileInputs.forEach(fi => {
                    fi.removeEventListener('change', fi._validateFn || (() => {}));
                    const fn = function () { validateFile(fi); };
                    fi.addEventListener('change', fn);
                    fi._validateFn = fn;
                });
            };

            const bindToggles = () => {
                const toggles = booksContainer.querySelectorAll('.book-toggle');
                toggles.forEach(t => {
                    // remove any previous listener marker
                    if (t._bound) return;
                    t._bound = true;
                    t.addEventListener('click', function (e) {
                        const card = t.closest('.book-card');
                        if (!card) return;
                        const body = card.querySelector('.book-body');
                        const icon = t.querySelector('.arrow-icon');
                        if (body.classList.contains('d-none')) {
                            body.classList.remove('d-none');
                            if (icon) icon.style.transform = 'rotate(180deg)';
                            t.setAttribute('aria-expanded', 'true');
                        } else {
                            body.classList.add('d-none');
                            if (icon) icon.style.transform = 'rotate(0deg)';
                            t.setAttribute('aria-expanded', 'false');
                        }
                    });
                });
            };

            // Add/Remove book blocks
            const addBookBtn = document.getElementById('addBookBtn');
            const booksContainer = document.getElementById('booksContainer');
            const MAX_BOOKS = 10;
            let bookIndex = booksContainer.querySelectorAll('.book-block').length; // starts with current count

            const updateAddButtonState = () => {
                const current = booksContainer.querySelectorAll('.book-block').length;
                if (!addBookBtn) return;
                if (current >= MAX_BOOKS) {
                    addBookBtn.disabled = true;
                    addBookBtn.classList.add('disabled');
                } else {
                    addBookBtn.disabled = false;
                    addBookBtn.classList.remove('disabled');
                }
            };

            const makeNewBlock = () => {
                const template = booksContainer.querySelector('.book-block');
                if (!template) return null;
                const clone = template.cloneNode(true);

                // set index
                clone.dataset.index = bookIndex;
                // update heading/title
                const titleEl = clone.querySelector('.book-title');
                if (titleEl) titleEl.textContent = 'Judul ' + (bookIndex + 1);
                // show remove btn
                const removeBtn = clone.querySelector('.btn-remove-book');
                if (removeBtn) removeBtn.classList.remove('d-none');

                // update names inside clone
                const inputs = clone.querySelectorAll('input, select, textarea');
                inputs.forEach((el) => {
                    if (el.name) {
                        el.name = el.name.replace(/books\[0\]/g, 'books[' + bookIndex + ']');
                    }
                    // clear values
                    if (el.tagName === 'INPUT') {
                        if (el.type === 'text' || el.type === 'number') el.value = '';
                        if (el.type === 'file') el.value = '';
                        if (el.type === 'number' && el.name.includes('jumlah_eksemplar')) el.value = 1;
                    }
                    if (el.tagName === 'SELECT') el.selectedIndex = 0;
                    if (el.tagName === 'TEXTAREA') el.value = '';
                });

                // bind remove
                if (removeBtn) {
                    removeBtn.addEventListener('click', function () {
                        clone.remove();
                        // renumber headings
                        const blocks = booksContainer.querySelectorAll('.book-block');
                        blocks.forEach((b, idx) => {
                            b.dataset.index = idx;
                            const t = b.querySelector('.book-title'); if (t) t.textContent = 'Judul ' + (idx + 1);
                            // fix names to have correct indices
                            const els = b.querySelectorAll('input, select, textarea');
                            els.forEach(el => {
                                if (el.name) el.name = el.name.replace(/books\[\d+\]/, 'books[' + idx + ']');
                            });
                        });
                        bookIndex = booksContainer.querySelectorAll('.book-block').length;
                        bindFileInputs();
                        bindToggles();
                        updateAddButtonState();
                    });
                }

                // ensure body visible for new block
                const body = clone.querySelector('.book-body');
                if (body) body.classList.remove('d-none');

                bookIndex++;
                return clone;
            };

            if (addBookBtn) {
                addBookBtn.addEventListener('click', function () {
                    const currentCount = booksContainer.querySelectorAll('.book-block').length;
                    if (currentCount >= MAX_BOOKS) {
                        alert('Maksimal ' + MAX_BOOKS + ' judul dapat ditambahkan.');
                        return;
                    }
                    const newBlock = makeNewBlock();
                    if (newBlock) {
                        booksContainer.appendChild(newBlock);
                        bindFileInputs();
                        bindToggles();
                        updateAddButtonState();
                    }
                });
            }

            // Ensure file inputs and toggles in existing blocks are bound
            bindFileInputs();
            bindToggles();
            updateAddButtonState();

            // Update book-title when user types title input for quick overview
            const updateBookTitles = () => {
                const blocks = booksContainer.querySelectorAll('.book-block');
                blocks.forEach((b, idx) => {
                    const input = b.querySelector('input[name^="books"][name$="[judul_buku]"]');
                    const titleEl = b.querySelector('.book-title');
                    if (input && titleEl) {
                        input.removeEventListener('input', input._titleFn || (() => {}));
                        const fn = function () {
                            const v = input.value.trim();
                            titleEl.textContent = v ? (v.length > 30 ? v.slice(0,30) + '…' : v) : ('Judul ' + (idx + 1));
                        };
                        input.addEventListener('input', fn);
                        input._titleFn = fn;
                        fn();
                    }
                });
            };

            // run now and after dynamic changes
            updateBookTitles();

            form?.addEventListener('DOMNodeInserted', function (e) { if (e.target && e.target.classList && e.target.classList.contains('book-block')) { updateBookTitles(); } });

            // Validate all file inputs on submit
            form?.addEventListener('submit', function (event) {
                const fileInputs = form.querySelectorAll('input[type="file"]');
                for (const fi of fileInputs) {
                    if (fi.hasAttribute('required')) {
                        if (!fi.files || !fi.files.length) {
                            alert('Semua kolom wajib diisi, termasuk foto buku untuk setiap judul.');
                            fi.focus();
                            event.preventDefault();
                            return;
                        }
                    }
                    if (!validateFile(fi)) {
                        event.preventDefault();
                        return;
                    }
                }
            });
        });
    </script>

    <!-- JS Bootstrap JS (Diperlukan agar fitur Collapse/Accordion berfungsi) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
