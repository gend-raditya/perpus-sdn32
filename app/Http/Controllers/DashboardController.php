<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Kita ambil data statistik buat dipajang di dashboard
        $totalBuku = \App\Models\Book::count();
        $totalHibahPending = \App\Models\Grant::where('status_hibah', 'pending')->count();
        $totalAnggota = \App\Models\User::where('role', 'member')->count();

        $recentTransactions = Transaction::with(['member', 'book'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalBuku',
            'totalHibahPending',
            'totalAnggota',
            'recentTransactions' // Kirim variabel ini ke view
        ));

        return view('dashboard.index', compact('totalBuku', 'totalHibahPending', 'totalAnggota'));
    }
}
