<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GrantController extends Controller
{
    public function index()
    {
        $grants = Grant::with('user')->latest()->get();
        return view('grants.index', compact('grants'));
    }

    public function approve($id)
    {
        $grant = Grant::findOrFail($id);

        // 1. Buat data buku baru dari data hibah
        $book = Book::create([
            'judul'     => $grant->judul_buku,
            'penulis'   => $grant->penulis_buku,
            'asal_buku' => 'hibah',
            'kode_qr'   => 'SDN32-' . date('Y') . '-' . strtoupper(Str::random(5)),
            'status'    => 'tersedia'
        ]);

        // 2. Update status hibah dan hubungkan ke book_id
        $grant->update([
            'status_hibah' => 'disetujui',
            'book_id'      => $book->id
        ]);

        return redirect()->back()->with('success', 'Buku hibah berhasil diverifikasi dan masuk katalog!');
    }
}
