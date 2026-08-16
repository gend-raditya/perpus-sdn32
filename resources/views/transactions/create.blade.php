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
                            <button type="button" class="btn btn-sm btn-manual-toggle px-3 py-1"
                                onclick="toggleManualMode()">
                                <i class="bi bi-keyboard"></i> Input Manual
                            </button>
                        </div>

                        <div id="reader" class="mx-auto shadow-sm mb-4"
                            style="width: 100%; max-width: 400px; overflow: hidden;"></div>

                        <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label field-label text-start d-block">ID Anggota</label>
                                    <input type="text" name="member_id"
                                        class="form-control form-control-lg text-center bg-light" id="member_input"
                                        placeholder="Scan atau Ketik ID Anggota..." readonly required>
                                </div>
                            </div>

                            <!-- Area List Buku yang Ditambahkan -->
                            <div class="mb-3 text-start">
                                <label class="form-label field-label">Daftar Buku yang Dipinjam:</label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control bg-light" id="book_input"
                                        placeholder="Scan buku berikutnya..." readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="btn-add-manual-book"
                                        style="display:none;" onclick="addBookManualBtn()">Tambah</button>
                                </div>
                                <ul id="book-list" class="list-group shadow-sm mb-2">
                                    <li class="list-group-item text-muted text-center py-2" id="empty-book-notice">Belum ada
                                        buku yang ditambahkan.</li>
                                </ul>
                                <!-- Container untuk input array hidden yang akan dikirim ke Laravel -->
                                <div id="hidden-books-container"></div>
                            </div>

                            <div id="status-message" class="alert alert-info d-none"></div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_pinjam" class="form-label field-label text-start d-block">Tanggal
                                        Pinjam</label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control"
                                        value="{{ date('Y-m-d') }}" required
                                        style="border-radius: 12px; border: 1.5px solid var(--line);">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="deadline" class="form-label field-label text-start d-block">Tanggal Kembali
                                        (Deadline)</label>
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
        const bookListContainer = document.getElementById('book-list');
        const hiddenBooksContainer = document.getElementById('hidden-books-container');
        const emptyBookNotice = document.getElementById('empty-book-notice');

        let isScanning = true;
        let html5QrCode;
        let scannedBooks = [];

        function cleanScannedText(decodedText) {
            let cleanData = decodedText;
            if (decodedText.includes('http')) {
                let parts = decodedText.split('/');
                cleanData = parts[parts.length - 1];
            } else if (!decodedText.includes('-')) {
                let matches = decodedText.match(/(\d+)(?!.*\d)/);
                cleanData = matches ? matches[0] : decodedText;
            }
            return cleanData.trim();
        }

        // Fungsi helper untuk mengecek validitas ke Database via Endpoint API/Route Laravel
        // Fungsi helper untuk mengecek validitas ke Database via Endpoint API/Route Laravel
        async function checkDatabase(type, id) {
            try {
                let response = await fetch(`/api/check-${type}/${encodeURIComponent(id)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                return await response.json();
            } catch (error) {
                console.error("Gagal memeriksa database", error);
                return { exists: true, status: 'tersedia', judul: '' };
            }
        }

        function bookStatusMessage(status) {
            const statusText = {
                'dipinjam': 'sedang dipinjam',
                'hilang': 'dinyatakan hilang',
                'tersedia': 'tersedia',
                default: 'tidak tersedia'
            };

            return statusText[status] ?? statusText.default;
        }

        async function onScanSuccess(decodedText, decodedResult) {
            if (!isScanning) return;

            let cleanData = cleanScannedText(decodedText);
            isScanning = false;

            if (html5QrCode) {
                html5QrCode.pause();
            }

            if (navigator.vibrate) navigator.vibrate(100);

            // 1. Jika Member belum diisi -> Validasi Anggota ke Database
            if (!memberInput.value) {
                let memberData = await checkDatabase('member', cleanData);

                if (!memberData.exists) {
                    Swal.fire({
                        title: 'Tidak Ditemukan!',
                        text: 'ID Anggota ' + cleanData + ' tidak terdaftar di database.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        isScanning = true;
                        if (html5QrCode) html5QrCode.resume();
                    });
                    return;
                }

                Swal.fire({
                    title: 'Anggota Terdeteksi!',
                    text: "ID Anggota: " + cleanData,
                    icon: 'success',
                    confirmButtonText: 'Ya, Lanjut Scan Buku',
                }).then((result) => {
                    if (result.isConfirmed) {
                        memberInput.value = cleanData;
                        memberInput.classList.replace('bg-light', 'bg-white');
                        statusMsg.classList.remove('d-none');
                        statusMsg.innerText = "Member OK. Silakan scan buku-buku yang ingin dipinjam.";
                    }
                    isScanning = true;
                    if (html5QrCode) html5QrCode.resume();
                });
            }
            // 2. Jika Member sudah ada -> Validasi Buku ke Database
            else {
                if (cleanData === memberInput.value) {
                    Swal.fire({
                        title: 'Eits!',
                        text: 'Itu kartu anggota yang tadi, Bro.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        isScanning = true;
                        if (html5QrCode) html5QrCode.resume();
                    });
                    return;
                }

                if (scannedBooks.includes(cleanData)) {
                    Swal.fire({
                        title: 'Perhatian',
                        text: 'Buku dengan ID ' + cleanData + ' sudah ada dalam daftar.',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        isScanning = true;
                        if (html5QrCode) html5QrCode.resume();
                    });
                    return;
                }

                let bookData = await checkDatabase('book', cleanData);

                if (!bookData.exists) {
                    Swal.fire({
                        title: 'Buku Tidak Ditemukan!',
                        text: 'ID Buku ' + cleanData + ' tidak terdaftar di database.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        isScanning = true;
                        if (html5QrCode) html5QrCode.resume();
                    });
                    return;
                }

                if (bookData.status === 'dipinjam' || bookData.status === 'hilang') {
                    Swal.fire({
                        title: 'Buku Tidak Tersedia!',
                        text: 'Buku "' + (bookData.judul || cleanData) + '" ' + bookStatusMessage(bookData.status) + '. Tidak bisa dipinjam.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        isScanning = true;
                        if (html5QrCode) html5QrCode.resume();
                    });
                    return;
                }

                addBookToList(cleanData);

                Swal.fire({
                    title: 'Buku Berhasil Ditambahkan!',
                    text: "ID Buku: " + cleanData + ". Tambah buku lain?",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tambah Lagi',
                    cancelButtonText: 'Cukup / Proses',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        isScanning = true;
                        if (html5QrCode) html5QrCode.resume();
                    } else {
                        isScanning = false;
                        Swal.fire({
                            title: 'Siap Diproses',
                            text: 'Silakan klik tombol "Proses Peminjaman" di bawah.',
                            icon: 'info',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        }

        function addBookToList(bookId) {
            scannedBooks.push(bookId);

            if (emptyBookNotice) {
                emptyBookNotice.remove();
            }

            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center py-2 px-3';
            li.id = 'item_' + bookId;
            li.innerHTML = `
                <span><i class="bi bi-book text-teal me-2"></i> ID Buku: <b>${bookId}</b></span>
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeBook('${bookId}')">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            `;
            bookListContainer.appendChild(li);

            const inputHidden = document.createElement('input');
            inputHidden.type = 'hidden';
            inputHidden.name = 'book_ids[]';
            inputHidden.value = bookId;
            inputHidden.id = 'hidden_' + bookId;
            hiddenBooksContainer.appendChild(inputHidden);

            bookInput.value = '';
        }

        function removeBook(bookId) {
            scannedBooks = scannedBooks.filter(item => item !== bookId);

            const itemElement = document.getElementById('item_' + bookId);
            if (itemElement) itemElement.remove();

            const hiddenElement = document.getElementById('hidden_' + bookId);
            if (hiddenElement) hiddenElement.remove();

            if (scannedBooks.length === 0) {
                const notice = document.createElement('li');
                notice.className = 'list-group-item text-muted text-center py-2';
                notice.id = 'empty-book-notice';
                notice.innerText = 'Belum ada buku yang ditambahkan.';
                bookListContainer.appendChild(notice);
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

        function toggleManualMode() {
            if (memberInput.hasAttribute('readonly')) {
                memberInput.removeAttribute('readonly');
                bookInput.removeAttribute('readonly');

                memberInput.classList.replace('bg-light', 'bg-white');
                bookInput.classList.replace('bg-light', 'bg-white');
                document.getElementById('btn-add-manual-book').style.display = 'block';

                memberInput.focus();
                isScanning = false;
                if (html5QrCode) html5QrCode.pause();

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
                document.getElementById('btn-add-manual-book').style.display = 'none';
                isScanning = true;
                if (html5QrCode) html5QrCode.resume();
            }
        }

        memberInput.addEventListener('keypress', async function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                let val = this.value.trim();
                if (val) {
                    let memberData = await checkDatabase('member', val);
                    if (!memberData.exists) {
                        Swal.fire('Tidak Ditemukan!', 'ID Anggota tidak terdaftar di database.', 'error');
                        return;
                    }
                    statusMsg.classList.remove('d-none');
                    statusMsg.innerText = "Member OK. Silakan ketik ID Buku lalu klik Tambah.";
                    bookInput.focus();
                }
            }
        });

        async function addBookManualBtn() {
            let val = bookInput.value.trim();
            if (!memberInput.value) {
                Swal.fire('Eits!', 'Isi ID Anggota terlebih dahulu.', 'warning');
                return;
            }
            if (val) {
                if (scannedBooks.includes(val)) {
                    Swal.fire('Perhatian', 'Buku sudah ada di daftar.', 'warning');
                    return;
                }
                let bookData = await checkDatabase('book', val);
                if (!bookData.exists) {
                    Swal.fire('Tidak Ditemukan!', 'ID Buku tidak terdaftar di database.', 'error');
                    return;
                }

                if (bookData.status === 'dipinjam' || bookData.status === 'hilang') {
                    Swal.fire({
                        title: 'Buku Tidak Tersedia!',
                        text: 'Buku "' + (bookData.judul || val) + '" ' + bookStatusMessage(bookData.status) + '. Tidak bisa dipinjam.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                addBookToList(val);
                bookInput.focus();
            }
        }

        bookInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addBookManualBtn();
            }
        });

        document.getElementById('transaction-form').addEventListener('submit', function(e) {
            if (!memberInput.value) {
                e.preventDefault();
                Swal.fire('Oops!', 'ID Anggota belum terisi.', 'warning');
                return;
            }
            if (scannedBooks.length === 0) {
                e.preventDefault();
                Swal.fire('Oops!', 'Minimal harus ada 1 buku yang dimasukkan ke daftar peminjaman.', 'warning');
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
    </script>
@endsection
