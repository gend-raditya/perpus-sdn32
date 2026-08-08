<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{

    // Proses ketika form pinjam buku disubmit
    public function store(Request $request)
    {
        try {
            // VALIDASI DIUBAH: Mengikuti input kalender (date) dari form baru
            $request->validate([
                'member_id'      => 'required',
                'book_id'        => 'required',
                'tanggal_pinjam' => 'required|date',
                'deadline'       => 'required|date|after_or_equal:tanggal_pinjam',
            ]);

            // 1. CARI MEMBER berdasarkan ID atau NISN (Kodingan asli lo)
            $member = \App\Models\Member::where('id', $request->member_id)
                ->orWhere('nisn', $request->member_id)
                ->first();

            if (!$member) {
                return redirect()->back()->with('error', 'Anggota dengan NISN/ID ' . $request->member_id . ' tidak ditemukan!');
            }

            // 2. CARI BUKU berdasarkan ID atau KODE_QR (Kodingan asli lo)
            $book = \App\Models\Book::where('id', $request->book_id)
                ->orWhere('kode_qr', $request->book_id)
                ->first();

            if (!$book) {
                return redirect()->back()->with('error', 'Buku dengan kode/ID "' . $request->book_id . '" tidak ditemukan!');
            }

            // 3. VALIDASI STATUS BUKU (Kodingan asli lo)
            if ($book->status !== 'tersedia') {
                return redirect()->back()->with('error', 'Buku "' . $book->judul . '" sedang tidak tersedia (status: ' . $book->status . ')');
            }

            DB::beginTransaction();

            // 3. SIMPAN TRANSAKSI (Logika disesuaikan dengan input kalender tanpa mengubah field DB)
            \App\Models\Transaction::create([
                'member_id'      => $member->id,
                'book_id'        => $book->id,
                'tanggal_pinjam' => $request->tanggal_pinjam, // Mengambil langsung dari input kalender
                'deadline'       => $request->deadline,       // Mengambil langsung dari input kalender
                'status'         => 'pinjam'                  // Tetap 'pinjam' sesuai bawaan lo
            ]);

            // 4. UPDATE STATUS BUKU (Kodingan asli lo)
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

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Update status berdasarkan input dari form inline dropdown
        $transaction->update([
            'status' => $request->input('status', 'kembali') // default kembali jika input kosong
        ]);

        // Tambahan logika: kembalikan stok atau status buku jika statusnya lunas/kembali
        if ($transaction->status == 'kembali') {
            \App\Models\Book::where('id', $transaction->book_id)->update(['status' => 'tersedia']);
        }

        return redirect()->route('transactions.index')->with('success', 'Status buku berhasil diperbarui secara manual!');
    }

    public function kembali($id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);

            // 1. Update transaksi
            $transaction->update([
                'status'          => 'kembali',
                'tanggal_kembali' => Carbon::now()->toDateString(),
            ]);

            // 2. Kembalikan status & stok buku
            if ($transaction->book) {
                $transaction->book->update(['status' => 'tersedia']);
                $transaction->book->increment('stok');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    public function hilang($id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);

            // 1. Ubah status transaksi menjadi hilang
            $transaction->update([
                'status' => 'hilang'
            ]);

            // 2. Ubah status fisik buku menjadi 'hilang'
            if ($transaction->book) {
                $transaction->book->update([
                    'status' => 'hilang'
                ]);
            }

            DB::commit();

            // Redirect back agar halaman (baik Fines maupun Transactions) ter-refresh
            return redirect()->back()->with('success', 'Buku dinyatakan HILANG. Status di riwayat & denda berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui status hilang: ' . $e->getMessage());
        }
    }
}
