<!-- Modal Tambah Buku -->
<div class="modal fade" id="addBookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addBookForm" action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Form Input Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" required
                            placeholder="Contoh: Buku Pintar Matematika">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor ISBN</label>
                        <input type="text" name="isbn" class="form-control"
                            placeholder="Contoh: 978-602-03-XXXX-X" required>
                        <div class="form-text">Masukkan 13 digit nomor ISBN buku.</div>
                    </div>

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
                            $selectedCategory = old('kategori_buku', '');
                        @endphp
                        <select name="kategori_buku" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Kategori Buku --</option>
                            @foreach ($categories as $value => $label)
                                <option value="{{ $value }}" {{ $selectedCategory == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control" required placeholder="Nama Penulis">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Buku</label>
                            <input type="number" name="jumlah" class="form-control" value="1" min="1"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Terbit</label>
                            <select name="tahun_terbit" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Tahun --</option>
                                @php
                                    $currentYear = date('Y');
                                @endphp
                                @for ($i = $currentYear; $i >= 1990; $i--)
                                    <option value="{{ $i }}"
                                        {{ old('tahun_terbit') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi Rak Buku</label>
                            <select name="rack_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Rak Buku --</option>
                                @foreach ($raks as $rak)
                                    <option value="{{ $rak->id }}">{{ $rak->name }} ({{ $rak->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" required
                                placeholder="Nama Penerbit">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Asal Buku</label>
                            <select name="asal_buku" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Asal Buku --</option>
                                @foreach(($sources ?? []) as $src)
                                    <option value="{{ $src->name }}" {{ old('asal_buku') == $src->name ? 'selected' : '' }}>{{ $src->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bahasa Buku</label>
                            <input type="text" name="bahasa" list="bahasaListAdd" class="form-control"
                                placeholder="Cari bahasa..." value="{{ old('bahasa') }}" required>
                            <datalist id="bahasaListAdd">
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
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Halaman</label>
                            <input type="number" name="halaman" class="form-control" min="1"
                                value="{{ old('halaman') }}" required placeholder="Contoh: 120">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sinopsis</label>
                            <textarea name="sinopsis" class="form-control" rows="4" placeholder="Ringkasan singkat buku..." required>{{ old('sinopsis') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Sampul Buku</label>
                            <input type="file" id="addBookFoto" name="foto" class="form-control" required
                                accept="image/png, image/jpeg, image/jpg">
                            <div class="form-text">Format: JPG, JPEG, PNG (Maks. 2MB).</div>
                        </div>

                        <!-- Inline alert for photo validation errors -->
                        <div id="addBookPhotoError" class="alert alert-danger d-none" role="alert"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-brand-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand-primary">Simpan & Generate QR</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Client-side validation for add book photo
    (function() {
        const form = document.getElementById('addBookForm');
        if (!form) return;

        const fotoInput = document.getElementById('addBookFoto');
        const errorEl = document.getElementById('addBookPhotoError');
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        const allowedExts = ['png', 'jpg', 'jpeg'];
        const maxSize = 2 * 1024 * 1024; // 2MB

        function showError(msg) {
            if (!errorEl) return;
            errorEl.textContent = msg;
            errorEl.classList.remove('d-none');
        }

        function hideError() {
            if (errorEl) errorEl.classList.add('d-none');
        }

        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                hideError();
                const f = this.files && this.files[0];
                if (!f) return;

                const name = (f.name || '').toLowerCase();
                const ext = name.split('.').pop();
                const typeOk = allowedTypes.includes(f.type.toLowerCase());
                const extOk = allowedExts.includes(ext);
                const sizeOk = f.size <= maxSize;

                if (!typeOk && !extOk) {
                    showError('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
                    this.value = '';
                    return;
                }
                if (!sizeOk) {
                    showError('Ukuran file terlalu besar. Maksimum 2MB.');
                    this.value = '';
                    return;
                }
            });
        }

        // Re-check on submit to prevent bypass
        form.addEventListener('submit', function(e) {
            hideError();
            const f = fotoInput && fotoInput.files && fotoInput.files[0];
            if (!f) {
                // required attribute should handle this, but double-check
                showError('Silakan upload foto sampul buku (JPG/PNG, maks 2MB).');
                e.preventDefault();
                return;
            }
            const name = (f.name || '').toLowerCase();
            const ext = name.split('.').pop();
            const typeOk = allowedTypes.includes(f.type.toLowerCase());
            const extOk = allowedExts.includes(ext);
            const sizeOk = f.size <= maxSize;
            if ((!typeOk && !extOk) || !sizeOk) {
                showError('Foto tidak valid. Pastikan format JPG/PNG dan ukuran ≤ 2MB.');
                e.preventDefault();
                return;
            }
        });
    })();
</script>
