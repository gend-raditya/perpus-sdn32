<div class="modal fade" id="editBookModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg">
            <form action="{{ url('/books/' . $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square"></i> Edit Data
                        Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start text-dark">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" value="{{ $item->judul }}"
                            required>
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
                            $currentCategory = is_array($item->kategori_buku)
                                ? $item->kategori_buku[0] ?? ''
                                : $item->kategori_buku;
                            $selectedCategory = old('kategori_buku', $currentCategory);
                        @endphp
                        <select name="kategori_buku" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Kategori Buku --
                            </option>
                            @foreach ($categories as $value => $label)
                                <option value="{{ $value }}"
                                    {{ $selectedCategory == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control" value="{{ $item->penulis }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="{{ $item->isbn ?? '' }}" required>
                        <div class="form-text">Masukkan nomor ISBN.</div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Tahun Terbit</label>
                            <select name="tahun_terbit" class="form-select" required>
                                <option value="" disabled>-- Pilih Tahun --</option>
                                @php $currentYear = date('Y'); @endphp
                                @for ($i = $currentYear; $i >= 1990; $i--)
                                    <option value="{{ $i }}" {{ old('tahun_terbit', $item->tahun_terbit) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Rak Buku</label>
                        <select name="rack_id" class="form-select" required>
                            @foreach ($raks as $rak)
                                <option value="{{ $rak->id }}"
                                    {{ old('rack_id', $item->rack_id) == $rak->id ? 'selected' : '' }}>
                                    {{ $rak->name }} ({{ $rak->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" required
                            value="{{ $item->penerbit ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Asal Buku</label>
                        <select name="asal_buku" class="form-select" required>
                            <option value="" disabled {{ empty($item->asal_buku) ? 'selected' : '' }}>-- Pilih Asal Buku --</option>
                            @foreach(($sources ?? []) as $src)
                                <option value="{{ $src->name }}" {{ ($item->asal_buku ?? '') == $src->name ? 'selected' : '' }}>{{ $src->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bahasa Buku</label>
                        <input type="text" name="bahasa" list="bahasaListEdit{{ $item->id }}" class="form-control"
                            placeholder="Cari bahasa..." value="{{ old('bahasa', $item->bahasa ?? '') }}"
                            @if(!empty($item->bahasa)) required @endif>
                        <datalist id="bahasaListEdit{{ $item->id }}">
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
                        <input type="number" name="halaman" class="form-control" min="1" value="{{ $item->halaman ?? '' }}" @if(!empty($item->halaman)) required @endif
                            placeholder="Contoh: 120">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sinopsis</label>
                        <textarea name="sinopsis" class="form-control" rows="4" placeholder="Ringkasan singkat buku..." @if(!empty($item->sinopsis)) required @endif>{{ $item->sinopsis ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ganti Foto Sampul Buku</label>
                        <input type="file" name="foto" class="form-control editBookFoto" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah sampul.</div>
                        <div class="alert alert-danger d-none editPhotoError mt-2" role="alert"></div>
                    </div>
                    <script>
                        (function(){
                            if (window._editBookPhotoValidatorInitialized) return;
                            window._editBookPhotoValidatorInitialized = true;

                            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                            const allowedExts = ['png', 'jpg', 'jpeg'];
                            const maxSize = 2 * 1024 * 1024; // 2MB

                            function showError(el, msg) {
                                if (!el) return;
                                el.textContent = msg;
                                el.classList.remove('d-none');
                            }
                            function hideError(el) {
                                if (!el) return;
                                el.classList.add('d-none');
                                el.textContent = '';
                            }

                            document.querySelectorAll('.editBookFoto').forEach(function(input){
                                const container = input.closest('.mb-3');
                                let errorEl = container && container.querySelector('.editPhotoError');

                                input.addEventListener('change', function(){
                                    if (!errorEl && container) {
                                        errorEl = container.querySelector('.editPhotoError');
                                    }
                                    hideError(errorEl);
                                    const f = this.files && this.files[0];
                                    if (!f) return;

                                    const name = (f.name || '').toLowerCase();
                                    const ext = name.split('.').pop();
                                    const typeOk = allowedTypes.includes(f.type.toLowerCase());
                                    const extOk = allowedExts.includes(ext);
                                    const sizeOk = f.size <= maxSize;

                                    if ((!typeOk && !extOk) || !sizeOk) {
                                        showError(errorEl, 'Foto tidak valid. Gunakan JPG/JPEG/PNG dan ukuran ≤ 2MB.');
                                        this.value = '';
                                    }
                                });

                                // Validate again on form submit to prevent bypass
                                const form = input.closest('form');
                                if (form) {
                                    form.addEventListener('submit', function(e){
                                        if (!input) return;
                                        const f = input.files && input.files[0];
                                        if (!f) return; // no new file, OK

                                        const name = (f.name || '').toLowerCase();
                                        const ext = name.split('.').pop();
                                        const typeOk = allowedTypes.includes(f.type.toLowerCase());
                                        const extOk = allowedExts.includes(ext);
                                        const sizeOk = f.size <= maxSize;

                                        if ((!typeOk && !extOk) || !sizeOk) {
                                            if (!errorEl && container) errorEl = container.querySelector('.editPhotoError');
                                            showError(errorEl, 'Foto tidak valid. Gunakan JPG/JPEG/PNG dan ukuran ≤ 2MB.');
                                            e.preventDefault();
                                            e.stopPropagation();
                                            // focus the file input so user can reselect
                                            input.focus();
                                            return false;
                                        }
                                    });
                                }
                            });
                        })();
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-brand-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
