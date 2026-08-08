<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Anggota Perpustakaan</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
        }

        /* Menggunakan ukuran & layout yang disesuaikan dengan desain referensi */
        .card {
            width: 350px;
            height: 200px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            border: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            page-break-inside: avoid;
        }

        .header {
            background: #004a99 !important;
            color: white !important;
            padding: 8px 10px;
            text-align: center;
            font-size: 11px;
        }

        .content {
            display: flex;
            padding: 12px;
            flex: 1;
        }

        .qr-section {
            flex: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-section img {
            width: 75px;
            height: 75px;
        }

        .info-section {
            flex: 2;
            font-size: 11px;
            padding-left: 10px;
        }

        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-section td {
            vertical-align: top;
            padding-bottom: 4px;
        }

        .footer {
            width: 100%;
            background: #004a99 !important;
            color: white !important;
            font-size: 8px;
            text-align: center;
            padding: 4px 0;
        }

        /* Pengaturan Khusus Halaman Cetak */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .cards-container {
                gap: 10mm;
            }

            .card {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #28a745; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            Cetak Sekarang
        </button>
    </div>

    <div class="cards-container">
        @foreach ($members as $m)
            @php
                $qrData = $m->nisn ?? 'MEMBER-' . $m->id;
            @endphp
            <div class="card">
                <div class="header">
                    <strong>PERPUSTAKAAN SDN 32 LUBUK ALUNG</strong><br>
                    <small style="font-size: 9px;">Kartu Anggota Perpustakaan digital</small>
                </div>

                <div class="content">
                    <div class="qr-section">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrData) }}"
                            alt="QR">
                        <div style="font-size: 9px; margin-top: 4px; font-weight: bold; color: #333;">
                            {{ $m->nisn ?? 'ID: ' . $m->id }}
                        </div>
                    </div>

                    <div class="info-section">
                        <table>
                            <tr>
                                <td width="35%">Nama</td>
                                <td>: <strong>{{ strtoupper($m->nama_lengkap) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: {{ ucfirst($m->peran) }}</td>
                            </tr>
                            <tr>
                                <td>No. HP</td>
                                <td>: {{ $m->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Berlaku</td>
                                <td>: Selama Menjadi Siswa</td>
                            </tr>
                        </table>
                        <p style="margin-top: 6px; font-style: italic; color: #666; font-size: 9px;">
                            Harap membawa kartu ini saat melakukan peminjaman buku.
                        </p>
                    </div>
                </div>

                <div class="footer">
                    Kec. Lubuk Alung, Kab. Padang Pariaman, Sumatera Barat
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // Otomatis buka dialog cetak saat halaman terbuka
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
