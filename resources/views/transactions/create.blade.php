@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card bg-light border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-body text-center p-4">
                        <h2 class="fw-bold">Scan Peminjaman</h2>
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <p class="text-muted">Gunakan kamera HP untuk scan kartu dan buku</p>

                        <div id="reader" class="mx-auto shadow-sm mb-4"
                            style="width: 100%; max-width: 400px; border-radius: 15px; overflow: hidden;"></div>

                        <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold d-flex justify-content-between">
                                        ID Anggota
                                        <button type="button" class="btn btn-sm btn-outline-primary py-0"
                                            onclick="toggleInput('member_input')">
                                            <i class="bi bi-keyboard"></i> Manual
                                        </button>
                                    </label>
                                    <input type="text" name="member_id"
                                        class="form-control form-control-lg text-center bg-light" id="member_input"
                                        placeholder="Scan atau Ketik ID..." readonly required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold d-flex justify-content-between">
                                        ID Buku
                                        <button type="button" class="btn btn-sm btn-outline-primary py-0"
                                            onclick="toggleInput('book_input')">
                                            <i class="bi bi-keyboard"></i> Manual
                                        </button>
                                    </label>
                                    <input type="text" name="book_id"
                                        class="form-control form-control-lg text-center bg-light" id="book_input"
                                        placeholder="Scan atau Ketik ID..." readonly required>
                                </div>
                            </div>
                            <div id="status-message" class="alert alert-info d-none"></div>
                            <div class="mb-3">
                                <label for="durasi" class="form-label">Durasi Pinjam (Hari)</label>
                                <input type="number" name="durasi" id="durasi" class="form-control"
                                    placeholder="Contoh: 7" value="7" required>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 mt-3 shadow-sm">
                                <i class="bi bi-check-circle"></i> Proses Peminjaman
                            </button>


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

        // ... (bagian atas tetap sama) ...

        function onScanSuccess(decodedText, decodedResult) {
            if (!isScanning) return;

            let cleanData = decodedText;

            // LOGIKA BARU: Kalau hasil scan itu URL (ada http), ambil angka paling belakang
            if (decodedText.includes('http')) {
                // Ini bakal ngambil angka terakhir dari link kayak .../members/1 jadi 1
                let parts = decodedText.split('/');
                cleanData = parts[parts.length - 1];
            }
            // Logika lama buat bersihin karakter non-angka kalau bukan URL
            else if (!decodedText.includes('-')) {
                let matches = decodedText.match(/(\d+)(?!.*\d)/);
                cleanData = matches ? matches[0] : decodedText;
            }

            isScanning = false;
            if (navigator.vibrate) navigator.vibrate(100);

            if (!memberInput.value) {
                Swal.fire({
                    title: 'Anggota Terdeteksi!',
                    text: "ID Asli: " + cleanData, // Tampilkan ID yang udah diekstrak
                    icon: 'success',
                    confirmButtonText: 'Ya, Lanjut Scan Buku',
                }).then((result) => {
                    if (result.isConfirmed) {
                        memberInput.value = cleanData; // Sekarang isinya cuma angka "1"
                        statusMsg.classList.remove('d-none');
                        statusMsg.innerText = "Member OK. Sekarang input ID Buku.";
                        isScanning = true;
                    } else {
                        isScanning = true;
                    }
                });
            } else if (!bookInput.value) {
                // ... (sisanya sama untuk bagian buku)
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
                }).then((result) => {
                    if (result.isConfirmed) {
                        bookInput.value = cleanData;
                        document.getElementById('transaction-form').submit();
                    } else {
                        isScanning = true;
                    }
                });
            }
        }

        // ... (sisanya tetap sama) ...

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


        // Fungsi buat toggle input manual
        function toggleInput(inputId) {
            const input = document.getElementById(inputId);
            if (input.hasAttribute('readonly')) {
                input.removeAttribute('readonly');
                input.classList.replace('bg-light', 'bg-white');
                input.focus();
                // Berhenti scanning sementara biar gak tabrakan
                isScanning = false;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Mode Input Manual Aktif',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                input.setAttribute('readonly', true);
                input.classList.replace('bg-white', 'bg-light');
                isScanning = true;
            }
        }

        // Tambahkan event listener di dalam script
        document.getElementById('member_input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value) {
                    statusMsg.classList.remove('d-none');
                    statusMsg.innerText = "Member OK. Sekarang input ID Buku.";
                    document.getElementById('book_input').focus();
                }
            }
        });

        // Validasi sebelum submit
        document.getElementById('transaction-form').addEventListener('submit', function(e) {
            // Kalau kolom masih kosong, jangan dikirim dulu
            if (!memberInput.value || !bookInput.value) {
                e.preventDefault();
                Swal.fire('Opps!', 'Pastikan ID Anggota dan ID Buku sudah terisi ya.', 'warning');
                return;
            }

            // Kalau oke, biarkan form jalan ke controller store
        });


        // Handle flash messages dari session
        // Tambahkan di bawah script yang sudah ada
        @if (session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'Oke, Paham'
            });
        @endif

        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
        // Biar kalau user tekan Enter di kolom buku, langsung submit form
        document.getElementById('book_input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value && memberInput.value) {
                    // Langsung submit form-nya
                    document.getElementById('transaction-form').submit();
                } else if (!memberInput.value) {
                    Swal.fire('Eits!', 'Isi ID Anggota dulu baru ID Buku.', 'warning');
                }
            }
        });
    </script>
@endsection
