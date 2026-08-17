<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class TransactionController extends Controller
{

    // Proses ketika form pinjam buku disubmit
    public function store(Request $request)
    {
        try {
            $request->validate([
                'member_id'      => 'required',
                'book_ids'       => 'required|array|min:1',
                'book_ids.*'     => 'required',
                'tanggal_pinjam' => 'required|date',
                'deadline'       => 'required|date|after_or_equal:tanggal_pinjam',
            ]);

            $member = \App\Models\Member::where('id', $request->member_id)
                ->orWhere('nisn', $request->member_id)
                ->first();

            if (!$member) {
                return redirect()->back()->with('error', 'Anggota dengan NISN/ID ' . $request->member_id . ' tidak ditemukan!');
            }

            DB::beginTransaction();

            $judulBerhasil = [];

            foreach ($request->book_ids as $bookIdentifier) {
                $book = \App\Models\Book::where('id', $bookIdentifier)
                    ->orWhere('kode_qr', $bookIdentifier)
                    ->first();

                if (!$book) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Buku dengan kode/ID "' . $bookIdentifier . '" tidak ditemukan!');
                }

                if ($book->status !== 'tersedia') {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Buku "' . $book->judul . '" sedang tidak tersedia (status: ' . $book->status . ')');
                }

                \App\Models\Transaction::create([
                    'member_id'      => $member->id,
                    'book_id'        => $book->id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'deadline'       => $request->deadline,
                    'status'         => 'pinjam'
                ]);

                $book->update(['status' => 'dipinjam']);
                $judulBerhasil[] = $book->judul;
            }

            DB::commit();

            $ringkasanJudul = $this->formatJudulPeminjaman($judulBerhasil);

            return redirect()->route('transactions.index')->with('success', 'Berhasil! ' . $member->nama_lengkap . ' meminjam buku: ' . $ringkasanJudul);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    protected function formatJudulPeminjaman(array $judulBerhasil): string
    {
        if (empty($judulBerhasil)) {
            return 'tidak ada buku';
        }

        $judulDikumpulkan = [];
        foreach ($judulBerhasil as $judul) {
            $judul = trim((string) $judul);
            if ($judul === '') {
                continue;
            }

            $key = strtolower($judul);
            if (!isset($judulDikumpulkan[$key])) {
                $judulDikumpulkan[$key] = [
                    'judul' => $judul,
                    'jumlah' => 1,
                ];
                continue;
            }

            $judulDikumpulkan[$key]['jumlah']++;
        }

        $daftarJudul = [];
        foreach ($judulDikumpulkan as $item) {
            if ($item['jumlah'] > 1) {
                $daftarJudul[] = $item['judul'] . '=' . $item['jumlah'];
            } else {
                $daftarJudul[] = $item['judul'];
            }
        }

        return implode(', ', $daftarJudul);
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
        $newStatus = $request->input('status', 'kembali');

        // Jika berubah menjadi kembali, set tanggal_kembali & hitung denda saat itu
        if ($newStatus === 'kembali' && !$transaction->tanggal_kembali) {
            $tarifPerHari = (int) (\App\Models\Setting::where('key', 'tarif_denda_per_hari')->value('value') ?? 1000);
            try {
                $deadline = Carbon::parse($transaction->deadline);
                $compareDate = Carbon::now();
                $hariKeterlambatan = $compareDate->gt($deadline) ? $deadline->diffInDays($compareDate) : 0;
                $denda = $hariKeterlambatan * $tarifPerHari;
            } catch (\Exception $e) {
                $denda = 0;
            }

            $transaction->update([
                'status' => 'kembali',
                'tanggal_kembali' => Carbon::now()->toDateString(),
                'denda' => $denda,
            ]);

            // kembalikan status buku
            \App\Models\Book::where('id', $transaction->book_id)->update(['status' => 'tersedia']);

            return redirect()->route('transactions.index')->with('success', 'Status buku berhasil diperbarui menjadi Kembali!');
        }

        // fallback: update status only
        $transaction->update([
            'status' => $newStatus
        ]);

        // Jika status sekarang kembali dan tanggal_kembali sudah diisi sebelumnya, pastikan buku tersedia
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

            // 1. Update transaksi - set tanggal kembali and calculate denda at this moment
            $tarifPerHari = (int) (\App\Models\Setting::where('key', 'tarif_denda_per_hari')->value('value') ?? 1000);
            try {
            $deadline = Carbon::parse($transaction->deadline)->startOfDay();
            $compareDate = Carbon::now()->startOfDay();
            $seconds = $compareDate->getTimestamp() - $deadline->getTimestamp();
            $hariKeterlambatan = $seconds > 0 ? intdiv($seconds, 86400) : 0;
            $denda = $hariKeterlambatan * $tarifPerHari;
            } catch (\Exception $e) {
            $denda = 0;
            }

            $transaction->update([
            'status'          => 'kembali',
            'tanggal_kembali' => Carbon::now()->toDateString(),
            'denda' => $denda,
            ]);

            // 2. Kembalikan status & stok buku
            if ($transaction->book) {
                $transaction->book->update(['status' => 'tersedia']);
                // increment stok if model has stok column
                if (in_array('stok', array_keys($transaction->book->getAttributes()))) {
                    $transaction->book->increment('stok');
                }
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

            // 1. Ubah status transaksi menjadi hilang.
            // Hitung biaya pengganti/hilang berdasarkan setting 'tarif_denda_buku_hilang' jika ada
            $lostFee = (int) (\App\Models\Setting::where('key', 'tarif_denda_buku_hilang')->value('value') ?? 0);

            // Hitung denda keterlambatan hingga saat admin menandai hilang
            try {
                $deadline = Carbon::parse($transaction->deadline);
                $now = Carbon::now();
                $hariKeterlambatan = $now->gt($deadline) ? $deadline->diffInDays($now) : 0;
                $tarifPerHari = (int) (\App\Models\Setting::where('key', 'tarif_denda_per_hari')->value('value') ?? 1000);
                $lateFee = $hariKeterlambatan * $tarifPerHari;
            } catch (\Exception $e) {
                $hariKeterlambatan = 0;
                $lateFee = 0;
            }

            // Siapkan data update: set status hilang, tanggal_kembali = hari ini (waktu admin klik)
            $updateData = [
                'status' => 'hilang',
                'tanggal_kembali' => Carbon::now()->toDateString(),
            ];

            if (Schema::hasColumn('transactions', 'denda_hilang')) {
                $updateData['denda_hilang'] = $lostFee > 0 ? $lostFee : null;
            }

            // Jika kolom denda ada, simpan total denda (biaya hilang + denda keterlambatan)
            if (Schema::hasColumn('transactions', 'denda')) {
                $totalDenda = ($lostFee ?? 0) + ($lateFee ?? 0);
                $updateData['denda'] = $totalDenda;
            }

            $transaction->update($updateData);

            // 2. Ubah status fisik buku menjadi 'hilang'
            if ($transaction->book) {
                $transaction->book->update([
                    'status' => 'hilang'
                ]);
            }

            DB::commit();

            // Redirect back agar halaman (baik Fines maupun Transactions) ter-refresh
            return redirect()->back()->with('success', 'Buku dinyatakan HILANG. Status di riwayat & denda hilang berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui status hilang: ' . $e->getMessage());
        }
    }
}
