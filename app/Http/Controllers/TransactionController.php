<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

// Proses ketika form pinjam buku disubmit
    public function store(Request $request)
    {
        try {
            $request->validate([
                'member_id' => 'required',
                'book_id'   => 'required',
                'durasi'    => 'required|integer',
            ]);

            // 1. CARI MEMBER berdasarkan ID atau NISN
            $member = \App\Models\Member::where('id', $request->member_id)
                ->orWhere('nisn', $request->member_id)
                ->first();

            if (!$member) {
                return redirect()->back()->with('error', 'Anggota dengan NISN/ID ' . $request->member_id . ' tidak ditemukan!');
            }

            // 2. CARI BUKU berdasarkan ID atau KODE_QR
            // Kita tambahkan orWhere agar kalau yang masuk angka ID (dari URL) tetap ketemu
            $book = \App\Models\Book::where('id', $request->book_id)
                ->orWhere('kode_qr', $request->book_id)
                ->first();

            if (!$book) {
                return redirect()->back()->with('error', 'Buku dengan kode/ID "' . $request->book_id . '" tidak ditemukan!');
            }

            // 3. VALIDASI STATUS BUKU
            if ($book->status !== 'tersedia') {
                return redirect()->back()->with('error', 'Buku "' . $book->judul . '" sedang tidak tersedia (status: ' . $book->status . ')');
            }

            DB::beginTransaction();

            // 3. SIMPAN TRANSAKSI
            // Tetap menggunakan $member->id dan $book->id (Primary Keys) agar relasi DB aman
            \App\Models\Transaction::create([
                'member_id'      => $member->id,
                'book_id'        => $book->id,
                'tanggal_pinjam' => now(),
                'deadline'       => now()->addDays((int) $request->durasi),
                'status'         => 'pinjam'
            ]);

            // 4. UPDATE STATUS BUKU
            $book->update(['status' => 'dipinjam']);

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Berhasil! ' . $member->nama_lengkap . ' meminjam buku ' . $book->judul);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // Form untuk input transaksi baru (pilih member, buku, durasi)
    public function create()
    {
        return view('transactions.create');
    }

    public function index()
    {
        $transactions = Transaction::with(['member', 'book'])
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }
}
