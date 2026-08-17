<!-- Modal Manage Asal Buku -->
<div class="modal fade" id="manageSourcesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kelola Asal Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('book-sources.store') }}" method="POST" class="mb-3">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Nama asal buku (mis. Pengadaan Sekolah)" required>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th style="width:140px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sources as $src)
                                <tr>
                                    <td>
                                        <form action="{{ route('book-sources.update', $src->id) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $src->name }}" class="form-control form-control-sm" required>
                                            <button class="btn btn-sm btn-success" type="submit">Simpan</button>
                                        </form>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <form action="{{ route('book-sources.destroy', $src->id) }}" method="POST" onsubmit="return confirm('Hapus asal buku ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

