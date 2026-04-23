<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota - {{ $member->nama_lengkap }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        body { font-family: 'Arial', sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding-top: 50px; }
        .card {
            width: 350px; height: 200px;
            background: white; border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            position: relative; overflow: hidden;
            border: 1px solid #ddd;
        }
        .header {
            background: #004a99; color: white;
            padding: 10px; text-align: center; font-size: 12px;
        }
        .content { display: flex; padding: 15px; }
        .qr-section { flex: 1; text-align: center; }
        .info-section { flex: 2; font-size: 11px; padding-left: 10px; }
        .info-section table { width: 100%; }
        .info-section td { vertical-align: top; padding-bottom: 5px; }
        .footer {
            position: absolute; bottom: 0; width: 100%;
            background: #004a99; color: white;
            font-size: 9px; text-align: center; padding: 5px 0;
        }
        .btn-print {
            position: fixed; top: 20px; left: 20px;
            padding: 10px 20px; background: #28a745; color: white;
            border: none; border-radius: 5px; cursor: pointer;
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">Cetak Kartu</button>

    <div class="card">
        <div class="header">
            <strong>PERPUSTAKAAN SDN 32 LUBUK ALUNG</strong><br>
            <small>Kartu Anggota Perpustakaan digital</small>
        </div>
        <div class="content">
            <div class="qr-section">
              {!! QrCode::size(100)->generate(route('members.show', $member->id)) !!}
                <div style="font-size: 10px; margin-top: 5px; font-weight: bold;">{{ $member->nisn ?? 'ID: '.$member->id }}</div>
            </div>
            <div class="info-section">
                <table>
                    <tr>
                        <td width="40%">Nama</td>
                        <td>: <strong>{{ strtoupper($member->nama_lengkap) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: {{ ucfirst($member->peran) }}</td>
                    </tr>
                    <tr>
                        <td>Berlaku</td>
                        <td>: Selama Menjadi Siswa</td>
                    </tr>
                </table>
                <p style="margin-top: 10px; font-style: italic; color: #666;">Silakan bawa kartu ini setiap melakukan peminjaman buku.</p>
            </div>
        </div>
        <div class="footer">
            Kec. Lubuk Alung, Kab. Padang Pariaman, Sumatera Barat
        </div>
    </div>
</body>
</html>
