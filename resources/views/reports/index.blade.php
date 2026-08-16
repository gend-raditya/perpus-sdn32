@extends('layouts.app') {{-- Sesuaikan nama layout utama lu --}}

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Card Filter -->
            <div class="card card-custom mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 font-display text-success">
                        <i class="bi bi-filter-square-fill me-2"></i> Filter Laporan Transaksi
                    </h5>

                    <form action="{{ route('reports.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                                <input type="date" name="start_date"
                                    value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}" class="form-control"
                                    style="border-radius: 8px;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                                <input type="date" name="end_date"
                                    value="{{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}" class="form-control"
                                    style="border-radius: 8px;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted">Status Buku</label>
                                <select name="status" class="form-select" style="border-radius: 8px;">
                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="pinjam" {{ $status == 'pinjam' ? 'selected' : '' }}>Sedang Dipinjam
                                    </option>
                                    <option value="kembali" {{ $status == 'kembali' ? 'selected' : '' }}>Sudah Kembali
                                    </option>
                                    <option value="hilang" {{ $status == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-1">
                                <button type="submit" class="btn text-white fw-bold flex-fill px-2"
                                    style="background-color: var(--teal); border-radius: 8px;" title="Filter Data">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="{{ route('reports.print', ['start_date' => $startDate, 'end_date' => $endDate, 'status' => $status]) }}"
                                    target="_blank" class="btn text-white fw-bold flex-fill px-2"
                                    style="background-color: var(--gold); border-radius: 8px;" title="Cetak PDF">
                                    <i class="bi bi-printer"></i> PDF
                                </a>
                                <a href="{{ route('reports.export-excel', ['start_date' => $startDate, 'end_date' => $endDate, 'status' => $status]) }}"
                                    class="btn btn-success fw-bold flex-fill px-2" style="border-radius: 8px;"
                                    title="Export Excel">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Tabel Data -->
            <div class="card card-custom">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable-init w-100">
                            <thead>
                                <tr class="text-muted small uppercase" style="border-bottom: 2px solid var(--line);">
                                    <th class="py-3">No</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Judul Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th>Denda</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $index => $item)
                                    <tr>
                                        <td class="fw-bold">{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $item->member->nisn ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $item->member->nama_lengkap ?? ($item->member->nama ?? '-') }}</span>
                                        </td>
                                        <td>{{ $item->book->judul ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                                        <td>
                                            {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                                        </td>
                                        <td>
                                            @if (isset($item->denda) && $item->denda > 0)
                                                <span class="text-danger fw-bold">Rp {{ number_format($item->denda, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 'pinjam')
                                                <span class="badge px-3 py-2 bg-warning text-dark"
                                                    style="border-radius: 20px;">Dipinjam</span>
                                            @elseif($item->status == 'kembali')
                                                <span class="badge px-3 py-2 text-white"
                                                    style="border-radius: 20px; background-color: var(--sage);">Kembali</span>
                                            @else
                                                <span class="badge px-3 py-2 bg-danger"
                                                    style="border-radius: 20px;">Hilang</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
