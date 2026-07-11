<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GrantController extends Controller
{
    /**
     * Menampilkan daftar semua hibah
     */
    public function index()
    {
        // Mengambil data hibah beserta admin yang menginputnya
        $grants = Grant::with('user')->latest()->get();
        return view('grants.index', compact('grants'));
    }

    /**
     * Menampilkan form tambah hibah
     */
    public function create()
    {
        return view('grants.create');
    }

    /**
     * Menyimpan data hibah ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemberi' => 'required|string|max:255',
            'judul_buku'   => 'required|string|max:255',
            'penulis_buku' => 'required|string|max:255',
            'foto_buku'    => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        // Mengambil ID Admin yang sedang login (Menggunakan Request agar VS Code tidak merah)
       $data['user_id'] = $request->user() ? $request->user()->id : 1;

        // Default status saat pertama kali input adalah pending
        $data['status_hibah'] = 'pending';

        // Proses Upload Foto jika ada
        if ($request->hasFile('foto_buku')) {
            $path = $request->file('foto_buku')->store('grants', 'public');
            $data['foto_buku'] = $path;
        }

        Grant::create($data);

        return redirect()->route('grants.index')->with('success', 'Data hibah berhasil dicatat! Silakan tunggu verifikasi.');
    }

    /**
     * Menyetujui hibah dan otomatis memasukkannya ke tabel Books
     */
    // Proses ketika admin klik tombol "Setujui" pada daftar hibah
    public function approve($id)
    {
        $grant = Grant::findOrFail($id);

        // Pastikan tidak di-approve dua kali
        if ($grant->status_hibah === 'disetujui') {
            return redirect()->back()->with('error', 'Buku ini sudah disetujui sebelumnya.');
        }

        // 1. Buat data buku baru di tabel books secara otomatis
        $book = Book::create([
            'judul'         => $grant->judul_buku,
            'penulis'       => $grant->penulis_buku,
            'penerbit'      => 'Hibah/Sumbangan', // Nilai default untuk buku hibah
            'tahun_terbit'  => date('Y'),         // Default tahun saat ini
            'stok'          => $grant->jumlah_eksemplar ?? 1,
            'asal_buku'     => 'hibah',
            'kode_qr'       => 'QR-HB-' . strtoupper(Str::random(6)), // Generate QR unik
            'status'        => 'tersedia'
        ]);

        // 2. Update status di tabel grants dan hubungkan ID bukunya
        $grant->update([
            'status_hibah' => 'disetujui',
            'book_id'      => $book->id
        ]);

        return redirect()->route('grants.index')->with('success', 'Hibah disetujui! Buku otomatis masuk ke katalog perpustakaan.');
    }

    /**
     * Menolak hibah jika buku tidak layak
     */
    public function reject($id)
    {
        $grant = Grant::findOrFail($id);
        $grant->update(['status_hibah' => 'ditolak']);

        return redirect()->route('grants.index')->with('info', 'Data hibah telah ditolak.');
    }
}
