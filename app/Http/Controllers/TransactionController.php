<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log; // <--- Tambahkan ini


class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'member_id' => 'required',
            'book_id' => 'required',
        ]);

        // 2. Logging buat debug (Pastiin sudah import use Log di atas)
        Log::info("Data masuk: Member ID = " . $request->member_id . ", Book ID = " . $request->book_id);

        try {
            // 3. Cek apakah buku sedang dipinjam (Dahuluin ini sebelum simpan!)
            $isBorrowed = Transaction::where('book_id', $request->book_id)
                ->where('status', 'pinjam')
                ->exists();

            if ($isBorrowed) {
                return back()->with('error', 'Waduh, bukunya lagi dipinjam orang lain, Bro!');
            }

            // 4. Proses Simpan
            Transaction::create([
                'member_id' => $request->member_id,
                'book_id' => $request->book_id,
                'tanggal_pinjam' => now(),
                'deadline' => now()->addDays(7),
                'status' => 'pinjam',
            ]);

            return redirect()->route('transactions.index')->with('success', 'Buku berhasil dipinjam!');
        } catch (\Exception $e) {
            // Tangkap error kalau database nolak (misal ID Member gak ada)
            Log::error("Error Simpan Transaksi: " . $e->getMessage());
            return back()->with('error', 'Gagal Simpan: ID Member atau Buku tidak valid di sistem.');
        }
    }

    public function create()
    {
        return view('transactions.create');
    }

    // Fungsi store yang tadi (pastiin udah ada juga)\

    public function index()
    {
        // Ambil data transaksi beserta data member dan bukunya
        $transactions = Transaction::with(['member', 'book'])
            ->latest()
            ->paginate(10); // Menampilkan 10 data per halaman

        return view('transactions.index', compact('transactions'));
    }
}
