<!-- Modal Riwayat Stok -->
<div class="modal fade" id="stockHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Riwayat Perubahan Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="stockHistoryLoading" class="text-center small text-muted">Memuat riwayat...</div>
                <table class="table table-sm table-striped d-none" id="stockHistoryTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Before</th>
                            <th>After</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
