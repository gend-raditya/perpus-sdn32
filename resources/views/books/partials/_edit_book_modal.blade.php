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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Buku (Total Stok)</label>
                            <input type="number" name="jumlah" class="form-control"
                                value="{{ $item->total_stok }}" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
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
                            <option value="pengadaan" {{ $item->asal_buku == 'pengadaan' ? 'selected' : '' }}>
                                Pengadaan
                                Sekolah</option>
                            <option value="pembelian_dana_bos"
                                {{ $item->asal_buku == 'pembelian_dana_bos' ? 'selected' : '' }}>
                                Pembelian Dana Bos</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bahasa</label>
                        <select name="bahasa" class="form-select" required>
                            <option value="" disabled {{ empty($item->bahasa) ? 'selected' : '' }}>-- Pilih Bahasa --</option>
                            <option value="Indonesia" {{ ($item->bahasa ?? '') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                            <option value="Inggris" {{ ($item->bahasa ?? '') == 'Inggris' ? 'selected' : '' }}>Inggris</option>
                            <option value="Lainnya" {{ ($item->bahasa ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Halaman</label>
                        <input type="number" name="halaman" class="form-control" min="1" value="{{ $item->halaman ?? '' }}" required
                            placeholder="Contoh: 120">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sinopsis</label>
                        <textarea name="sinopsis" class="form-control" rows="4" placeholder="Ringkasan singkat buku..." required>{{ $item->sinopsis ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ganti Foto Sampul Buku</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah sampul.
                        </div>
                    </div>
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
