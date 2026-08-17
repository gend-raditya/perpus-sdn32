<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Book;
use App\Models\Grant;
use App\Models\Member;
use Carbon\Carbon; // <-- Jangan lupa import Carbon

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

        // Peringatan dipicu 1 hari sebelum jatuh tempo atau saat sudah terlambat.
        $today = Carbon::today();
        $dueReminderDate = $today->copy()->addDays(1);

        $mendekatiDeadline = Transaction::with(['member', 'book'])
            ->where('status', 'pinjam')
            ->where('deadline', '<=', $dueReminderDate)
            ->orderBy('deadline', 'asc')
            ->get();

        // Kembalikan ke view dengan menambahkan variabel $mendekatiDeadline
        return view('dashboard.index', compact(
            'totalBuku',
            'totalHibahPending',
            'totalAnggota',
            'recentTransactions',
            'mendekatiDeadline' // <-- Variabel baru untuk alert warning di view
        ));
    }
}
