@extends('layouts.app')

@section('content')
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tambah Anggota Baru</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3"> <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control"
                                    placeholder="Contoh: Budi Santoso" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NISN (Wajib untuk Siswa)</label>
                                <input type="text" name="nisn" class="form-control" placeholder="10 digit nomor">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peran</label>
                                <select name="peran" class="form-select" required>
                                    <option value="siswa">Siswa</option>
                                    <option value="alumni">Alumni</option>
                                    <option value="guru">Guru</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. HP Orang Tua / Wali (Opsional)</label>
                            <input type="text" name="no_hp" class="form-control"
                                placeholder="0812... atau kosongkan jika tidak ada">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat tempat tinggal siswa"></textarea>
                        </div>

                        <input type="hidden" name="image_captured" id="image_captured">
                        <hr>
                        <button type="submit" class="btn btn-primary">Simpan Anggota</button>
                        <a href="{{ route('members.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white text-center fw-bold">Ambil Foto Langsung</div>
                <div class="card-body text-center">
                    <div id="my_camera" class="mx-auto rounded border bg-light mb-2" style="width:100%; max-width:320px;">
                    </div>

                    <div id="results" class="d-none">
                        <img id="prev_img" src="" class="img-thumbnail mb-2" style="width:150px;">
                        <p class="small text-success fw-bold">Foto berhasil diambil!</p>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-sm" onclick="take_snapshot()">
                            <i class="bi bi-camera"></i> Klik Ambil Foto
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="reset_camera()">
                            Reset Foto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<script>
    // 1. Setting Kamera
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach('#my_camera');

    // 2. Fungsi Ambil Gambar
    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            // Tampilkan preview
            document.getElementById('results').classList.remove('d-none');
            document.getElementById('prev_img').src = data_uri;

            // Masukkan string gambar ke input hidden
            document.getElementById('image_captured').value = data_uri;
        });
    }

    // 3. Fungsi Reset
    function reset_camera() {
        document.getElementById('results').classList.add('d-none');
        document.getElementById('image_captured').value = "";
    }
</script>
