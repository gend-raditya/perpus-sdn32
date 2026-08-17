<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Setting;
use Carbon\Carbon;
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

        // Build collection by merging live transactions and archived transactions so reports include both
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Live transactions (Eloquent so member/book relations are eager loaded)
        $liveQuery = Transaction::with(['member', 'book']);
        if ($status === 'kembali') {
            $liveQuery->whereBetween('tanggal_kembali', [$start, $end])->where('status', 'kembali');
        } elseif ($status !== 'all') {
            $liveQuery->whereBetween('tanggal_pinjam', [$start, $end])->where('status', $status);
        } else {
            $liveQuery->whereBetween('tanggal_pinjam', [$start, $end]);
        }
        $live = $liveQuery->latest()->get();

        // Archived transactions (read from transactions_archive and left join member/book for names)
        $archivedRaw = \Illuminate\Support\Facades\DB::table('transactions_archive as ta')
            ->leftJoin('members as m', 'ta.member_id', '=', 'm.id')
            ->leftJoin('books as b', 'ta.book_id', '=', 'b.id')
            ->select('ta.*', 'm.id as member_id', 'm.nama_lengkap as member_nama_lengkap', 'm.nisn as member_nisn', 'b.id as book_id', 'b.judul as book_judul');

        // For archived transactions, when user filters by 'kembali' we should filter by tanggal_kembali (same as live query)
        if ($status === 'kembali') {
            $archivedRaw->whereBetween('ta.tanggal_kembali', [$start, $end])->where('ta.status', 'kembali');
        } elseif ($status !== 'all') {
            $archivedRaw->whereBetween('ta.tanggal_pinjam', [$start, $end])->where('ta.status', $status);
        } else {
            $archivedRaw->whereBetween('ta.tanggal_pinjam', [$start, $end]);
        }

        $archived = $archivedRaw->orderBy('ta.id', 'desc')->get();

        // Normalize archived rows into objects that mimic Transaction with member/book props
        $archivedCollection = $archived->map(function ($row) {
            $obj = new \stdClass();
            foreach ((array) $row as $k => $v) $obj->{$k} = $v;

            $obj->member = (object) [
                'id' => $row->member_id,
                'nama_lengkap' => $row->member_nama_lengkap,
                'nama' => $row->member_nama_lengkap,
                'nisn' => $row->member_nisn,
            ];

            $obj->book = (object) [
                'id' => $row->book_id,
                'judul' => $row->book_judul,
            ];

            return $obj;
        });

        // Merge live and archived collections
        $reports = $live->concat($archivedCollection);

        // Jika buku fisiknya sudah ditandai 'hilang' tapi transaksi belum diubah, prioritaskan menampilkan status 'hilang' di laporan
        // (hanya berlaku bila informasi buku tersedia — arsip mungkin tidak menyertakan status buku)
        $reports = $reports->map(function ($trx) {
            if (isset($trx->book) && isset($trx->book->status) && strtolower((string) $trx->book->status) === 'hilang') {
                $trx->status = 'hilang';
            }
            return $trx;
        });

        // Hitung denda untuk setiap transaksi agar tampil di laporan
        $tarifPerHari = (int) (Setting::where('key', 'tarif_denda_per_hari')->value('value') ?? 1000);
        $reports = $reports->map(function ($trx) use ($tarifPerHari) {
            // Jika denda sudah disimpan (pada saat pengembalian), gunakan nilai tersebut
            if (isset($trx->denda) && $trx->denda !== null) {
                $trx->denda = (int) $trx->denda;
                return $trx;
            }

            try {
                $deadline = Carbon::parse($trx->deadline)->startOfDay();
                $compareDate = isset($trx->tanggal_kembali) && $trx->tanggal_kembali ? Carbon::parse($trx->tanggal_kembali)->startOfDay() : Carbon::today()->startOfDay();
                $seconds = $compareDate->getTimestamp() - $deadline->getTimestamp();
                $hariKeterlambatan = $seconds > 0 ? intdiv($seconds, 86400) : 0;
                $trx->denda = $hariKeterlambatan * $tarifPerHari;
            } catch (\Exception $e) {
                $trx->denda = 0;
            }
            return $trx;
        });

        // Urutkan data terbaru terlebih dahulu (newest by tanggal_kembali, fallback ke tanggal_pinjam/updated_at)
        $reports = $reports->sortByDesc(function ($trx) {
            $dateCandidates = [$trx->tanggal_kembali ?? null, $trx->tanggal_pinjam ?? null, $trx->updated_at ?? $trx->created_at ?? null];
            foreach ($dateCandidates as $d) {
                if (!empty($d)) {
                    try {
                        return Carbon::parse($d)->getTimestamp();
                    } catch (\Exception $e) {
                        // ignore and try next
                    }
                }
            }
            return 0;
        })->values();

        return view('reports.index', compact('reports', 'startDate', 'endDate', 'status'));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status', 'all');

        // Include both live and archived transactions for print as well
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $liveQuery = Transaction::with(['member', 'book'])->whereBetween('tanggal_pinjam', [$start, $end]);
        if ($status !== 'all') {
            $liveQuery->where('status', $status);
        }
        $live = $liveQuery->latest()->get();

        $archivedRaw = \Illuminate\Support\Facades\DB::table('transactions_archive as ta')
            ->leftJoin('members as m', 'ta.member_id', '=', 'm.id')
            ->leftJoin('books as b', 'ta.book_id', '=', 'b.id')
            ->select('ta.*', 'm.id as member_id', 'm.nama_lengkap as member_nama_lengkap', 'm.nisn as member_nisn', 'b.id as book_id', 'b.judul as book_judul')
            ->whereBetween('ta.tanggal_pinjam', [$start, $end]);

        if ($status !== 'all') {
            $archivedRaw->where('ta.status', $status);
        }

        $archived = $archivedRaw->orderBy('ta.id', 'desc')->get();

        $archivedCollection = $archived->map(function ($row) {
            $obj = new \stdClass();
            foreach ((array) $row as $k => $v) $obj->{$k} = $v;

            $obj->member = (object) [
                'id' => $row->member_id,
                'nama_lengkap' => $row->member_nama_lengkap,
                'nama' => $row->member_nama_lengkap,
                'nisn' => $row->member_nisn,
            ];

            $obj->book = (object) [
                'id' => $row->book_id,
                'judul' => $row->book_judul,
            ];

            return $obj;
        });

        $reports = $live->concat($archivedCollection);

        // Hitung denda untuk setiap transaksi agar dicetak pada laporan PDF
        $tarifPerHari = (int) (Setting::where('key', 'tarif_denda_per_hari')->value('value') ?? 1000);
        $reports = $reports->map(function ($trx) use ($tarifPerHari) {
            try {
                $deadline = Carbon::parse($trx->deadline)->startOfDay();
                $compareDate = isset($trx->tanggal_kembali) && $trx->tanggal_kembali ? Carbon::parse($trx->tanggal_kembali)->startOfDay() : Carbon::today()->startOfDay();
                $seconds = $compareDate->getTimestamp() - $deadline->getTimestamp();
                $hariKeterlambatan = $seconds > 0 ? intdiv($seconds, 86400) : 0;
                $trx->denda = $hariKeterlambatan * $tarifPerHari;
            } catch (\Exception $e) {
                $trx->denda = 0;
            }
            return $trx;
        });

        // Urutkan data terbaru terlebih dahulu (newest by tanggal_kembali, fallback ke tanggal_pinjam/updated_at)
        $reports = $reports->sortByDesc(function ($trx) {
            $dateCandidates = [$trx->tanggal_kembali ?? null, $trx->tanggal_pinjam ?? null, $trx->updated_at ?? $trx->created_at ?? null];
            foreach ($dateCandidates as $d) {
                if (!empty($d)) {
                    try {
                        return Carbon::parse($d)->getTimestamp();
                    } catch (\Exception $e) {
                        // ignore and try next
                    }
                }
            }
            return 0;
        })->values();

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
