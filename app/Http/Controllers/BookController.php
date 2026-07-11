<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class BookController extends Controller
{
    // Fungsi untuk menyimpan buku baru (bisa sekaligus banyak dengan jumlah)
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'asal_buku' => 'required',
            'tahun_terbit' => 'required|numeric',
            'jumlah' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Validasi foto masuk ke sini
        ]);

        $pathFoto = null;

        // 1. Proses upload foto di luar loop (biar file-nya cuma disimpan sekali)
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('books', 'public');
        }

        $prefix = strtoupper(substr($request->judul, 0, 3));

        // 2. Loop sebanyak jumlah eksemplar yang diinput
        for ($i = 1; $i <= $request->jumlah; $i++) {

            // Generate kode QR unik menggunakan kombinasi microtime/random biar tidak bentrok
            $kodeQr = 'SDN32-' . $prefix . '-' . date('Y') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '-' . Str::random(3);

            Book::create([
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'penerbit' => $request->penerbit,
                'tahun_terbit' => $request->tahun_terbit,
                'asal_buku' => $request->asal_buku,
                'kode_qr' => $kodeQr,
                'status' => 'tersedia',
                'foto' => $pathFoto // Path foto yang sama dimasukkan ke setiap eksemplar buku ini
            ]);
        }

        return redirect()->route('books.index')->with('success', $request->jumlah . ' buku berhasil ditambah!');
    }
    // Fungsi untuk menampilkan daftar buku (dengan pengelompokan)
    // Fungsi untuk menampilkan daftar buku (dengan pengelompokan)
    public function index()
    {
        // Masukkan 'foto' ke select dan groupBy agar datanya ketarik ke Blade
        $books = Book::select('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto')
            ->selectRaw('count(*) as total_stok')
            ->selectRaw('SUM(CASE WHEN status = "tersedia" THEN 1 ELSE 0 END) as stok_tersedia')
            ->selectRaw('MAX(created_at) as last_added')
            ->groupBy('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto')
            ->orderBy('last_added', 'desc')
            ->get();

        // PASTIKAN return ke view, bukan return $books
        return view('books.index', compact('books'));
    }

    // Fungsi untuk menampilkan detail buku berdasarkan hasil scan QR Code
    public function showByScan($kode_qr)
    {
        // Cari buku berdasarkan kode_qr
        $book = Book::where('kode_qr', $kode_qr)->first();

        // Kalau buku nggak ketemu
        if (!$book) {
            return redirect('/dashboard')->with('error', 'Buku dengan kode tersebut tidak ditemukan!');
        }

        return view('books.show', compact('book'));
    }

    // Fungsi untuk cetak label QR Code
    public function printLabels()
    {
        $books = Book::all(); // Lu bisa filter misal: where('asal_buku', 'hibah')
        return view('books.print', compact('books'));
    }

    // Fungsi untuk API detail buku (JSON)
    public function getDetailJson(Request $request)
    {
        // Ambil judul dari query string (?judul=...)
        $judul = $request->query('judul');

        // Cari semua buku yang judulnya sama
        $books = \App\Models\Book::where('judul', $judul)->get();

        // Kirim sebagai JSON
        return response()->json($books);
    }
}
