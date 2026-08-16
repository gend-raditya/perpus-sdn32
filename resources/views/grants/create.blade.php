@extends('layouts.app')

@section('content')
    <style>
        .grant-form-shell {
            background: linear-gradient(180deg, #f8fbfa 0%, #ffffff 100%);
            border-radius: 22px;
            border: 1px solid rgba(31, 120, 118, 0.12);
            box-shadow: 0 18px 40px -28px rgba(28, 55, 48, 0.22);
        }

        .grant-form-section {
            background: #fff;
            border: 1px solid #e7ece9;
            border-radius: 18px;
            padding: 1.2rem;
        }

        .grant-form-section h5 {
            margin-bottom: 0;
            color: var(--teal-dark, #186b6c);
            font-weight: 700;
        }

        .grant-form-label {
            font-weight: 600;
            color: #2d3a36;
            margin-bottom: .45rem;
        }

        .grant-form-control, .grant-form-select, .grant-form-textarea {
            border: 1.5px solid #dfe8e4;
            border-radius: 12px;
            padding: .7rem .85rem;
            background: #fff;
            transition: all .2s ease;
        }

        .grant-form-control:focus, .grant-form-select:focus, .grant-form-textarea:focus {
            border-color: var(--teal, #1f7a78);
            box-shadow: 0 0 0 0.2rem rgba(31, 122, 120, 0.12);
        }

        .grant-form-textarea {
            min-height: 120px;
            resize: vertical;
        }
    </style>

    <div class="pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Input Buku Hibah / Sumbangan</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card grant-form-shell border-0">
                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 border-0 mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="grantForm" action="{{ route('grants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grant-form-section mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-person-circle text-primary fs-5"></i>
                                <h5>Informasi Donatur</h5>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="grant-form-label">Nama Pemberi</label>
                                    <input type="text" name="nama_pemberi" class="grant-form-control w-100"
                                        placeholder="Contoh: Alumni Angkatan 2010" value="{{ old('nama_pemberi') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="grant-form-label">No. HP / Kontak</label>
                                    <input type="tel" name="kontak_pemberi" class="grant-form-control w-100"
                                        placeholder="0812..." value="{{ old('kontak_pemberi') }}" required maxlength="13" inputmode="numeric" pattern="[0-9]{1,13}">
                                </div>
                            </div>
                        </div>

                        <div class="grant-form-section mb-4" id="booksContainer">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-journal-text text-primary fs-5"></i>
                                <h5>Detail Buku</h5>
                            </div>

                            <div class="book-block">
                                <div class="row g-3 align-items-start">
                                    <div class="col-md-6">
                                        <label class="grant-form-label">Judul Buku</label>
                                        <input type="text" name="books[0][judul_buku]" class="grant-form-control w-100"
                                            placeholder="Contoh: Matematika Kelas 5 SD"
                                            value="{{ old('books.0.judul_buku') }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="grant-form-label">Nomor ISBN</label>
                                        <input type="text" name="books[0][isbn]" class="grant-form-control w-100"
                                            placeholder="Contoh: 978-602-xxx"
                                            value="{{ old('books.0.isbn') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="grant-form-label">Tahun Terbit</label>
                                        <select name="books[0][tahun_terbit]" class="grant-form-select w-100" required>
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
                                        <label class="grant-form-label">Penulis</label>
                                        <input type="text" name="books[0][penulis_buku]" class="grant-form-control w-100"
                                            value="{{ old('books.0.penulis_buku') }}" placeholder="Nama penulis" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="grant-form-label">Penerbit</label>
                                        <input type="text" name="books[0][penerbit_buku]" class="grant-form-control w-100"
                                            value="{{ old('books.0.penerbit_buku') }}" placeholder="Nama penerbit" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="grant-form-label">Kategori Buku</label>
                                        <select name="books[0][kategori_buku]" class="grant-form-select w-100" required>
                                            <option value="" disabled {{ old('books.0.kategori_buku') == '' ? 'selected' : '' }}>-- Pilih Kategori Buku --</option>
                                            <option value="Buku Pelajaran" {{ old('books.0.kategori_buku') == 'Buku Pelajaran' ? 'selected' : '' }}>Buku Pelajaran / Paket Sekolah</option>
                                            <option value="Buku Cerita / Novel" {{ old('books.0.kategori_buku') == 'Buku Cerita / Novel' ? 'selected' : '' }}>Buku Cerita / Dongeng / Novel</option>
                                            <option value="Ensiklopedia / Pengetahuan" {{ old('books.0.kategori_buku') == 'Ensiklopedia / Pengetahuan' ? 'selected' : '' }}>Buku Pengetahuan Umum / Ensiklopedia</option>
                                            <option value="Keagamaan" {{ old('books.0.kategori_buku') == 'Keagamaan' ? 'selected' : '' }}>Buku Keagamaan</option>
                                            <option value="Majalah / Komik Anak" {{ old('books.0.kategori_buku') == 'Majalah / Komik Anak' ? 'selected' : '' }}>Majalah / Komik Anak</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="grant-form-label">Kondisi Buku</label>
                                        <select name="books[0][kondisi_buku]" class="grant-form-select w-100" required>
                                            <option value="" disabled {{ old('books.0.kondisi_buku') == '' ? 'selected' : '' }}>-- Pilih Kondisi --</option>
                                            <option value="Baru" {{ old('books.0.kondisi_buku') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                            <option value="Pernah Dipakai" {{ old('books.0.kondisi_buku') == 'Pernah Dipakai' ? 'selected' : '' }}>Pernah Dipakai</option>
                                            <option value="Rusak Ringan" {{ old('books.0.kondisi_buku') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="grant-form-label">Bahasa</label>
                                        <input type="text" name="books[0][bahasa]" list="bahasaList" class="grant-form-control w-100"
                                            placeholder="Cari bahasa..." value="{{ old('books.0.bahasa') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="grant-form-label">Jumlah Halaman</label>
                                        <input type="number" name="books[0][jumlah_halaman]" min="1" class="grant-form-control w-100"
                                            value="{{ old('books.0.jumlah_halaman') }}" placeholder="Misal: 120" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="grant-form-label">Jumlah (Eks)</label>
                                        <input type="number" name="books[0][jumlah_eksemplar]" class="grant-form-control w-100"
                                            value="{{ old('books.0.jumlah_eksemplar', 1) }}" min="1" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="grant-form-label">Sinopsis / Deskripsi Buku</label>
                                        <textarea name="books[0][sinopsis]" class="grant-form-textarea w-100" placeholder="Tuliskan ringkasan singkat isi buku..." required>{{ old('books.0.sinopsis') }}</textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="grant-form-label">Foto Buku</label>
                                        <input type="file" name="books[0][foto_buku]" class="grant-form-control w-100" accept="image/jpeg,image/png,image/jpg" required>
                                        <small class="text-muted d-block mt-2">Wajib upload. File yang diterima: JPG, JPEG, atau PNG. Ukuran maksimal 2 MB.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-2">
                                <button type="button" id="addBookBtn" class="btn btn-sm btn-outline-primary">Tambah Judul Lain</button>
                            </div>
                        </div>

                        <div class="grant-form-section mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-geo-alt text-primary fs-5"></i>
                                <h5>Lokasi & Dokumen</h5>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="grant-form-label">Kota / Kabupaten</label>
                                    <select id="kota" class="grant-form-select w-100" required>
                                        <option value="">-- Pilih Kota / Kabupaten --</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="grant-form-label">Detail Alamat</label>
                                    <input type="text" id="detail_alamat" class="grant-form-control w-100"
                                        placeholder="RT/RW, jalan, nomor rumah" required>
                                </div>
                                <input type="hidden" name="alamat_pengirim" id="alamat_pengirim" value="{{ old('alamat_pengirim') }}">
                                <div class="col-md-12">
                                    <small class="text-muted">Alamat pengirim akan otomatis tersusun dari kota/kabupaten dan detail alamat yang dipilih/ditulis.</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between flex-wrap gap-3 mt-4">
                            <a href="{{ route('grants.index') }}" class="btn btn-light border">Kembali</a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">Simpan Data Hibah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

            const validateFile = (input) => {
                const file = input.files && input.files[0];
                if (!file) return true;

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024;

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

            // Repeater: Tambah / Hapus blok buku (admin)
            const addBookBtn = document.getElementById('addBookBtn');
            const booksContainer = document.getElementById('booksContainer');
            const MAX_BOOKS = 10;
            let bookIndex = booksContainer.querySelectorAll('.book-block').length; // current count

            const makeNewBlock = () => {
                const template = booksContainer.querySelector('.book-block');
                if (!template) return null;
                const clone = template.cloneNode(true);

                // set dataset index
                clone.dataset.index = bookIndex;

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

                // add remove button
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger mt-2 btn-remove-book';
                removeBtn.textContent = 'Hapus Judul';
                removeBtn.addEventListener('click', function () {
                    clone.remove();
                    // reindex remaining
                    const blocks = booksContainer.querySelectorAll('.book-block');
                    blocks.forEach((b, idx) => {
                        const els = b.querySelectorAll('input, select, textarea');
                        els.forEach(el => {
                            if (el.name) el.name = el.name.replace(/books\[\d+\]/g, 'books[' + idx + ']');
                        });
                    });
                    bookIndex = booksContainer.querySelectorAll('.book-block').length;
                });

                clone.appendChild(removeBtn);

                bookIndex++;
                return clone;
            };

            if (addBookBtn) {
                addBookBtn.addEventListener('click', function () {
                    const currentCount = booksContainer.querySelectorAll('.book-block').length;
                    if (currentCount >= MAX_BOOKS) {
                        alert('Maksimum ' + MAX_BOOKS + ' judul dapat ditambahkan.');
                        return;
                    }

                    const newBlock = makeNewBlock();
                    if (newBlock) {
                        booksContainer.appendChild(newBlock);
                        bindFileInputs();
                    }
                });
            }

            // Ensure file inputs in existing blocks are bound
            bindFileInputs();

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

                // validate all file inputs
                const fileInputs = form.querySelectorAll('input[type="file"]');
                for (const fi of fileInputs) {
                    if (!validateFile(fi)) {
                        event.preventDefault();
                        return;
                    }
                }
            });
        });
    </script>
@endsection
