<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class FineController extends Controller
{
    public function index()
    {
        // 1. Tentukan tarif denda per hari
        $tarifPerHari = 1000;

        // 2. AMBIL SEMUA DATA AKTIF (Yang statusnya 'pinjam' atau 'hilang')
        // Filter deadline dibuang dari query biar semua data peminjaman narik dulu
        $transaksiAktif = Transaction::with(['member', 'book'])
            ->where('status', 'pinjam') //👈 Hanya tarik yang masih aktif PINJAM. Jika diklik 'hilang', otomatis keluar dari tabel ini!
            ->get();

        $totalDendaBelumBayar = 0;

        // 3. Looping untuk menghitung denda secara realtime
        $dataDenda = $transaksiAktif->map(function ($transaksi) use ($tarifPerHari, &$totalDendaBelumBayar) {
            $tanggalDeadline = Carbon::parse($transaksi->deadline);
            $hariIni = Carbon::today();

            // Cek apakah hari ini sudah melewati deadline
            if ($hariIni->gt($tanggalDeadline)) {
                // Jika terlambat, hitung selisih harinya
                $hariKeterlambatan = $tanggalDeadline->diffInDays($hariIni);
            } else {
                // Jika belum melewati deadline, keterlambatan dianggap 0 hari
                $hariKeterlambatan = 0;
            }

            // Hitung jumlah denda berjalan
            $jumlahDenda = $hariKeterlambatan * $tarifPerHari;

            // KONDISI KHUSUS: Jika statusnya hilang, lu bisa kasih denda flat (misal 50rb)
            // atau biarkan denda keterlambatan terakhirnya membeku.
            // Di bawah ini denda keterlambatan tetap dihitung sampai hari dia dinyatakan hilang.
            $totalDendaBelumBayar += $jumlahDenda;

            // Masukkan data kalkulasi baru ke object transaksi agar bisa dibaca di Blade
            $transaksi->hari_telat = $hariKeterlambatan;
            $transaksi->total_denda = $jumlahDenda;

            return $transaksi;
        });

        // Hitung jumlah murid unik yang memiliki denda berjalan (> 0)
        $jumlahMuridDenda = $dataDenda->filter(function ($item) {
            return $item->total_denda > 0;
        })->unique('member_id')->count();

        // 4. Lempar data ke View
        return view('fines.index', [
            'dataDenda' => $dataDenda,
            'totalDendaBelumBayar' => $totalDendaBelumBayar,
            'jumlahMuridDenda' => $jumlahMuridDenda,
            'tarifPerHari' => $tarifPerHari
        ]);
    }
}
