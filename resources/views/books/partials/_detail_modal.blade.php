<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-book"></i> Detail & Inventaris Buku</h5>
                <div class="ms-auto me-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="printAllQR()">
                        <i class="bi bi-printer"></i> Cetak Semua QR
                    </button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        <img id="detailFoto" src="" alt="Sampul Buku"
                            class="img-fluid rounded shadow-sm border p-1"
                            style="max-height: 180px; object-fit: cover;">
                        <div id="detailNoFoto"
                            class="d-flex align-items-center justify-content-center bg-light border rounded text-muted mx-auto"
                            style="width: 120px; height: 160px; font-size: 12px;">No Image</div>
                    </div>
                    <div class="col-md-9 text-dark">
                        <h4 class="fw-bold mb-1" id="displayJudul"
                            style="font-family: 'Baloo 2', sans-serif; color: var(--teal-dark);"></h4>
                        <p class="text-muted mb-3" id="displayPenulisPenerbit"></p>

                        <div class="row g-2">
                            <div class="col-6 col-md-4">
                                <small class="text-muted d-block">Tahun Terbit</small>
                                <strong id="displayTahun">-</strong>
                            </div>
                            <div class="col-6 col-md-3">
                                <small class="text-muted d-block">Lokasi Rak</small>
                                <strong id="displayRak" class="text-teal">-</strong>
                            </div>
                            <div class="col-6 col-md-4">
                                <small class="text-muted d-block">Asal Buku</small>
                                <strong id="displayAsal">-</strong>
                            </div>
                            <div class="col-12 col-md-4">
                                <small class="text-muted d-block">Status Stok</small>
                                <span class="badge bg-dark" id="displayStokTotal">0 Total</span>
                                <span class="badge bg-success" id="displayStokReady">0 Ready</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-qr-code-scan text-teal"></i> Daftar Eksemplar & QR
                    Code</h6>

                <div class="table-responsive"
                    style="max-height: 300px; border-radius: 8px; border: 1px solid var(--line);">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark text-center" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th style="background: var(--teal);">No</th>
                                <th style="background: var(--teal);">Kode QR / ID Fisik</th>
                                <th style="background: var(--teal);">Status</th>
                                <th style="background: var(--teal);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detailBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Zoom QR -->
<div class="modal fade" id="qrZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h6 class="mb-0 fw-bold" id="zoomKodeLabel">KODE QR</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body p-4 text-center">
                <img id="zoomQrImage" src="" alt="Zoom QR" class="img-fluid" style="max-width: 300px;">
                <hr>
                <p class="text-muted small">Klik tutup untuk kembali.</p>
            </div>
        </div>
    </div>
</div>
