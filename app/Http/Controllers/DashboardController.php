<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Book;
use App\Models\Grant;
use App\Models\Member; // Import model Member lo di sini, bro!

class DashboardController extends Controller
{
    // Menampilkan halaman dashboard dengan statistik dan data terbaru
    public function index()
    {
        // Ambil data statistik asli dari database
        $totalBuku = Book::count();
        $totalHibahPending = Grant::where('status_hibah', 'pending')->count();

        // PERBAIKAN: Hitung dari tabel members, bukan tabel users dengan role member
        $totalAnggota = Member::count();

        // Ambil 5 aktivitas transaksi terbaru
        $recentTransactions = Transaction::with(['member', 'book'])
            ->latest()
            ->take(5)
            ->get();

        // Kembalikan ke view (Cukup satu return saja, yang double di bawah sudah dihapus)
        return view('dashboard.index', compact(
            'totalBuku',
            'totalHibahPending',
            'totalAnggota',
            'recentTransactions'
        ));
    }
}
