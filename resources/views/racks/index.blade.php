@extends('layouts.app') {{-- sesuaikan dengan nama file layout utama kamu --}}

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-display fw-bold mb-1">Data Rak Buku</h3>
            <p class="text-muted small mb-0">Kelola lokasi penyimpanan dan tata letak koleksi buku.</p>
        </div>
        <button class="btn text-white fw-semibold px-3 py-2 shadow-sm"
            style="background-color: var(--teal); border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#addRackModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Rak
        </button>
    </div>

    <div class="card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable-init w-100">
                <thead style="background-color: var(--paper-alt);">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Kode Rak</th>
                        <th>Nama Rak</th>
                        <th>Lokasi</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($racks as $index => $rack)
                        <tr>
                            <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge px-3 py-2"
                                    style="background-color: var(--teal-light); color: var(--teal); font-weight: 700; border-radius: 8px;">
                                    {{ $rack->code }}
                                </span>
                            </td>
                            <td class="fw-bold">{{ $rack->name }}</td>
                            <td>
                                <span class="text-muted">
                                    <i class="bi bi-geo-alt me-1"
                                        style="color: var(--gold);"></i>{{ $rack->location ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-warning rounded-3" data-bs-toggle="modal"
                                        data-bs-target="#editRackModal{{ $rack->id }}" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <form action="{{ route('racks.destroy', $rack->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus rak ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"
                                            title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Rak -->
                        <div class="modal fade" id="editRackModal{{ $rack->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                                    <div class="modal-header" style="border-bottom: 2px dashed var(--line);">
                                        <h5 class="modal-title font-display fw-bold">Edit Data Rak</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('racks.update', $rack->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kode Rak</label>
                                                <input type="text" name="code" class="form-control rounded-3"
                                                    value="{{ old('code', $rack->code) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Nama Rak</label>
                                                <input type="text" name="name" class="form-control rounded-3"
                                                    value="{{ old('name', $rack->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Lokasi / Keterangan</label>
                                                <input type="text" name="location" class="form-control rounded-3"
                                                    value="{{ old('location', $rack->location) }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light rounded-3"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn text-white rounded-3 px-4"
                                                style="background-color: var(--teal);">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Rak -->
    <div class="modal fade" id="addRackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header" style="border-bottom: 2px dashed var(--line);">
                    <h5 class="modal-title font-display fw-bold">Tambah Rak Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('racks.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Rak</label>
                            <input type="text" name="code" class="form-control rounded-3"
                                placeholder="Contoh: RAK-01" value="{{ old('code') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Rak</label>
                            <input type="text" name="name" class="form-control rounded-3"
                                placeholder="Contoh: Rak Buku Pelajaran" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lokasi / Keterangan</label>
                            <input type="text" name="location" class="form-control rounded-3"
                                placeholder="Contoh: Sudut Kanan Ruang Utama" value="{{ old('location') }}">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white rounded-3 px-4"
                            style="background-color: var(--teal);">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
