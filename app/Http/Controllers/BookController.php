<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'asal_buku' => 'required'
        ]);

        // 2. Generate Kode QR Unik (Misal: PRPS-2026-001)
        // Kita gabungin prefix, tahun, dan random string biar unik
        $kodeQr = 'SDN32-' . date('Y') . '-' . strtoupper(Str::random(5));

        // 3. Simpan ke Database
        Book::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'asal_buku' => $request->asal_buku,
            'kode_qr' => $kodeQr,
            'status' => 'tersedia'
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambah dan QR Code dibuat!');
    }

    public function index()
    {
        $books = Book::latest()->get(); // Ambil semua buku, urutkan dari yang terbaru
        return view('books.index', compact('books'));
    }

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
}
