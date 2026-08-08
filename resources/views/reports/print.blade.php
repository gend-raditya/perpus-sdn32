<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perpustakaan SDN 32 Lubuk Alung</title>
    <style>
        body { font-family: sans-serif; color: #333; padding: 20px; line-height: 1.4; }
        .kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; mb-4: 20px; }
        .kop-surat h2 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .kop-surat p { margin: 5px 0 0 0; font-size: 13px; color: #555; }
        .info-filter { margin: 20px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #666; }
        th { background-color: #f2f2f2; padding: 10px; text-align: left; font-size: 13px; }
        td { padding: 8px; font-size: 13px; }
        .text-center { text-align: center; }
        .badge-status { font-weight: bold; text-transform: uppercase; font-size: 11px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- KOP SURAT SEKOLAH -->
    <div class="kop-surat">
        <h2>PEMERINTAH KABUPATEN PADANG PARIAMAN</h2>
        <h2>DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
        <h2 style="font-size: 22px;">SDN 32 LUBUK ALUNG</h2>
        <p>Alamat: Jl. Lubuk Alung, Kec. Lubuk Alung, Kab. Padang Pariaman, Sumatera Barat</p>
    </div>

    <h3 class="text-center" style="margin-top: 25px; text-transform: uppercase;">LAPORAN DATA TRANSAKSI PERPUSTAKAAN</h3>

    <div class="info-filter">
        <strong>Periode :</strong> {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} <br>
        <strong>Status Buku :</strong> {{ $status == 'all' ? 'Semua Status' : ucfirst($status) }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th>Nama Siswa</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Denda</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->member->nama }}</td>
                    <td>{{ $item->book->judul }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->denda > 0 ? 'Rp '.number_format($item->denda, 0, ',', '.') : '-' }}</td>
                    <td class="text-center badge-status">
                        {{ $item->status }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan Kepala/Pustakawan -->
    <div style="margin-top: 50px; float: right; text-align: center; width: 200px;">
        <p>Lubuk Alung, {{ date('d M Y') }}</p>
        <p style="margin-bottom: 60px;">Pustakawan Perpustakaan,</p>
        <p><strong>( ____________________ )</strong></p>
    </div>

</body>
</html>
