<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cetak Label QR - SDN 32</title>
    <style>
        /* CSS biar pas di print rapi */
        @media print {
            .no-print {
                display: none;
            }
        }

        .label-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 kolom per baris */
            gap: 20px;
            padding: 20px;
        }

        .label-box {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }

        .title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .code {
            font-size: 9px;
            margin-top: 5px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Klik untuk Cetak / Save PDF</button>
        <a href="/books">Kembali</a>
    </div>

    <div class="label-container">
        @foreach ($books as $book)
            <div class="label-box">
                <div class="title">{{ Str::limit($book->judul, 20) }}</div>

                {{-- Generate QR pake URL Ngrok Permanen lu --}}
                {!! QrCode::size(100)->generate(route('books.scan', $book->kode_qr)) !!}

                <div class="code">{{ $book->kode_qr }}</div>
                <div style="font-size: 8px;">Perpus SDN 32</div>
            </div>
        @endforeach
    </div>
</body>

</html>
