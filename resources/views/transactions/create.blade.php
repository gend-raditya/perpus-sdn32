@extends('layouts.app')

@section('content')
    <style>
        .card-scan {
            border: 2px dashed var(--line) !important;
            border-radius: 20px !important;
            background: #fff !important;
        }

        .card-scan h2 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            color: var(--ink);
        }

        .card-scan>.card-body>p.text-muted {
            color: var(--ink-soft) !important;
        }

        #reader {
            border: 2px dashed var(--teal) !important;
            border-radius: 15px !important;
            background: var(--paper-alt);
        }

        .field-label {
            font-weight: 700 !important;
            color: var(--ink-soft);
            font-size: .9rem;
        }

        .btn-manual-toggle {
            border: 1.5px solid var(--teal);
            color: var(--teal);
            background: transparent;
            border-radius: 999px;
            font-weight: 700;
            font-size: .75rem;
        }

        .btn-manual-toggle:hover {
            background: var(--teal);
            color: var(--paper);
        }

        input#member_input,
        input#book_input {
            border-radius: 12px;
            border: 1.5px solid var(--line);
            font-weight: 700;
            letter-spacing: .05em;
        }

        input#member_input.bg-light,
        input#book_input.bg-light {
            background-color: var(--paper-alt) !important;
            border-style: dashed;
        }

        input#member_input.bg-white,
        input#book_input.bg-white {
            background-color: #fff !important;
            border-color: var(--teal) !important;
            box-shadow: 0 0 0 4px var(--teal-light);
        }

        #status-message.alert-info {
            background: var(--teal-light) !important;
            border: 1.5px dashed var(--teal) !important;
            color: var(--teal-dark) !important;
            border-radius: 12px !important;
        }

        .btn-process {
            background: var(--teal) !important;
            border: none !important;
            color: var(--paper) !important;
            border-radius: 14px !important;
            font-weight: 700;
            box-shadow: 4px 4px 0 var(--teal-dark);
            transition: all .2s ease;
        }

        .btn-process:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--teal-dark);
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-scan border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <h2 class="fw-bold">Scan Peminjaman</h2>
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                            <p class="text-muted mb-0">Gunakan kamera HP untuk scan kartu dan buku</p>
                            <button type="button" class="btn btn-sm btn-manual-toggle px-3 py-1" onclick="toggleManualMode()">
                                <i class="bi bi-keyboard"></i> Input Manual
                            </button>
                        </div>

                        <div id="reader" class="mx-auto shadow-sm mb-4"
                            style="width: 100%; max-width: 400px; overflow: hidden;"></div>

                        <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label field-label text-start d-block">ID Anggota</label>
                                    <input type="text" name="member_id"
                                        class="form-control form-control-lg text-center bg-light" id="member_input"
                                        placeholder="Scan atau Ketik ID..." readonly required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label field-label text-start d-block">ID Buku</label>
                                    <input type="text" name="book_id"
                                        class="form-control form-control-lg text-center bg-light" id="book_input"
                                        placeholder="Scan atau Ketik ID..." readonly required>
                                </div>
                            </div>

                            <div id="status-message" class="alert alert-info d-none"></div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_pinjam" class="form-label field-label text-start d-block">Tanggal Pinjam</label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control"
                                        value="{{ date('Y-m-d') }}" required
                                        style="border-radius: 12px; border: 1.5px solid var(--line);">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="deadline" class="form-label field-label text-start d-block">Tanggal Kembali (Deadline)</label>
                                    <input type="date" name="deadline" id="deadline" class="form-control"
                                        value="{{ date('Y-m-d', strtotime('+7 days')) }}" required
                                        style="border-radius: 12px; border: 1.5px solid var(--line);">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-process btn-lg w-100 mt-3 shadow-sm">
                                <i class="bi bi-check-circle"></i> Proses Peminjaman
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

            let cleanData = decodedText;

            if (decodedText.includes('http')) {
                let parts = decodedText.split('/');
                cleanData = parts[parts.length - 1];
            }
            else if (!decodedText.includes('-')) {
                let matches = decodedText.match(/(\d+)(?!.*\d)/);
                cleanData = matches ? matches[0] : decodedText;
            }

            isScanning = false;
            if (navigator.vibrate) navigator.vibrate(100);

            if (!memberInput.value) {
                Swal.fire({
                    title: 'Anggota Terdeteksi!',
                    text: "ID Asli: " + cleanData,
                    icon: 'success',
                    confirmButtonText: 'Ya, Lanjut Scan Buku',
                }).then((result) => {
                    if (result.isConfirmed) {
                        memberInput.value = cleanData;
                        statusMsg.classList.remove('d-none');
                        statusMsg.innerText = "Member OK. Sekarang input ID Buku.";
                        isScanning = true;
                    } else {
                        isScanning = true;
                    }
                });
            } else if (!bookInput.value) {
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

        // Toggle manual gabungan untuk membuka kunci input anggota & buku sekaligus
        function toggleManualMode() {
            if (memberInput.hasAttribute('readonly')) {
                memberInput.removeAttribute('readonly');
                bookInput.removeAttribute('readonly');

                memberInput.classList.replace('bg-light', 'bg-white');
                bookInput.classList.replace('bg-light', 'bg-white');

                memberInput.focus();
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
                memberInput.setAttribute('readonly', true);
                bookInput.setAttribute('readonly', true);
                memberInput.classList.replace('bg-white', 'bg-light');
                bookInput.classList.replace('bg-white', 'bg-light');
                isScanning = true;
            }
        }

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

        document.getElementById('transaction-form').addEventListener('submit', function(e) {
            if (!memberInput.value || !bookInput.value) {
                e.preventDefault();
                Swal.fire('Opps!', 'Pastikan ID Anggota dan ID Buku sudah terisi ya.', 'warning');
                return;
            }
        });

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

        document.getElementById('book_input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value && memberInput.value) {
                    document.getElementById('transaction-form').submit();
                } else if (!memberInput.value) {
                    Swal.fire('Eits!', 'Isi ID Anggota dulu baru ID Buku.', 'warning');
                }
            }
        });
    </script>
@endsection
