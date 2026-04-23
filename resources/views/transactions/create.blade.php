@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card bg-light border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body text-center p-4">
                        <h2 class="fw-bold">Scan Peminjaman</h2>
                        <p class="text-muted">Gunakan kamera HP untuk scan kartu dan buku</p>

                        <div id="reader" class="mx-auto shadow-sm mb-4"
                            style="width: 100%; max-width: 400px; border-radius: 15px; overflow: hidden;"></div>

                        <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
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
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">ID Anggota</label>
                                    <input type="text" name="member_id"
                                        class="form-control form-control-lg text-center bg-white" id="member_input"
                                        placeholder="Scan Kartu..." readonly required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">ID Buku</label>
                                    <input type="text" name="book_id"
                                        class="form-control form-control-lg text-center bg-white" id="book_input"
                                        placeholder="Scan Buku..." readonly required>
                                </div>
                            </div>

                            <div id="status-message" class="alert alert-info d-none"></div>

                            <button type="submit" class="btn btn-success btn-lg w-100 mt-3 shadow-sm">
                                <i class="bi bi-check-circle"></i> Proses Peminjaman
                            </button>
                            <button type="button" onclick="location.reload()"
                                class="btn btn-outline-secondary w-100 mt-2">Reset Scan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const memberInput = document.getElementById('member_input');
        const bookInput = document.getElementById('book_input');
        const statusMsg = document.getElementById('status-message');

        let isScanning = true;
        let html5QrCode;

        function onScanSuccess(decodedText, decodedResult) {
            if (!isScanning) return;

            // --- PROSES PEMBERSIHAN DATA ---
            // Kode ini bakal nyari angka aja. Contoh: "ID-12" jadi "12"
            let matches = decodedText.match(/(\d+)(?!.*\d)/);
            let cleanData = matches ? matches[0] : "";

            // Cek kalau setelah dibersihin malah kosong (berarti QR-nya gak ada angkanya)
            if (cleanData === "") {
                Swal.fire('Error', 'QR Code tidak valid (Tidak mengandung ID angka)!', 'error');
                return;
            }

            isScanning = false;
            if (navigator.vibrate) navigator.vibrate(100);

            if (!memberInput.value) {
                // Gunakan cleanData, bukan decodedText
                Swal.fire({
                    title: 'Anggota Terdeteksi!',
                    text: "ID Asli: " + decodedText + " -> Sistem ambil: " + cleanData,
                    icon: 'success',
                    confirmButtonText: 'Ya, Lanjut Scan Buku',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        memberInput.value = cleanData; // Masukin angka bersihnya
                        statusMsg.classList.remove('d-none');
                        statusMsg.innerText = "Member OK. Sekarang scan Barcode Buku.";
                        isScanning = true;
                    } else {
                        isScanning = true;
                    }
                });

            } else if (!bookInput.value) {
                // Sama, bersihkan data buat buku juga
                if (cleanData === memberInput.value) {
                    Swal.fire('Eits!', 'Itu kartu anggota yang tadi, Bro.', 'warning');
                    isScanning = true;
                    return;
                }

                Swal.fire({
                    title: 'Buku Terdeteksi!',
                    text: "ID Buku: " + cleanData,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Pinjam!',
                    cancelButtonText: 'Ulangi',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        bookInput.value = cleanData; // Masukin angka bersihnya
                        document.getElementById('transaction-form').submit();
                    } else {
                        isScanning = true;
                    }
                });
            }
        }

        // Pake cara manual Start biar langsung Kamera Belakang
        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length > 0) {
                let backCamera = cameras.find(c =>
                    c.label.toLowerCase().includes('back') ||
                    c.label.toLowerCase().includes('rear') ||
                    c.label.toLowerCase().includes('environment')
                );

                let cameraId = backCamera ? backCamera.id : cameras[0].id;
                html5QrCode = new Html5Qrcode("reader");

                html5QrCode.start(
                    cameraId, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    onScanSuccess
                ).catch(err => console.error("Gagal start kamera", err));
            }
        }).catch(err => {
            console.error("Gagal dapet kamera", err);
            statusMsg.classList.remove('d-none');
            statusMsg.classList.replace('alert-info', 'alert-danger');
            statusMsg.innerText = "Kamera gak kedetect. Pastiin pake HTTPS ya!";
        });
    </script>
@endsection
