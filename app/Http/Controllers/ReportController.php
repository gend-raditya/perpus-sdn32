<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
// [TAMBAHAN 1] Import package Excel & Export class
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan format Y-m-d agar dibaca sempurna oleh <input type="date">
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $status = $request->input('status', 'all');

        $query = Transaction::with(['member', 'book']);

        // Pastikan range tanggal mencakup dari 00:00:00 s/d 23:59:59
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        if ($status === 'kembali') {
            $query->whereBetween('tanggal_kembali', [$start, $end])->where('status', 'kembali');
        } elseif ($status !== 'all') {
            $query->whereBetween('tanggal_pinjam', [$start, $end])->where('status', $status);
        } else {
            $query->whereBetween('tanggal_pinjam', [$start, $end]);
        }

        $reports = $query->latest()->get();

        return view('reports.index', compact('reports', 'startDate', 'endDate', 'status'));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status', 'all');

        $query = Transaction::with(['member', 'book'])
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reports = $query->latest()->get();

        // Menggunakan view khusus cetak (clean tanpa sidebar)
        return view('reports.print', compact('reports', 'startDate', 'endDate', 'status'));
    }

    // [TAMBAHAN 2] Method baru untuk handle fitur export Excel
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $status = $request->input('status', 'all');

        return Excel::download(
            new ReportsExport($startDate, $endDate, $status),
            'Laporan_Transaksi_' . $startDate . '_s-d_' . $endDate . '.xlsx'
        );
    }
}
