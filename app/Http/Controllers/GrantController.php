<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\Book;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $grants = Grant::with('user')->latest()->paginate(10);
        $racks = Rack::all(); // Passing data rak untuk modal/form persetujuan jika diperlukan

        return view('grants.index', compact('grants', 'racks'));
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
        $books = $request->input('books');

        if (is_array($books) && !empty($books)) {
            $request->validate([
                'nama_pemberi'     => 'required|string|max:255',
                'kontak_pemberi'   => 'required|string|max:13|regex:/^[0-9]+$/',
                'alamat_pengirim'  => 'required|string',
                'books'            => 'required|array|min:1',
                'books.*.judul_buku'       => 'required|string|max:255',
                'books.*.isbn'             => 'nullable|string|max:255',
                'books.*.penerbit_buku'    => 'nullable|string|max:255',
                'books.*.tahun_terbit'     => 'nullable|integer|min:1900|max:' . date('Y'),
                'books.*.penulis_buku'     => 'nullable|string|max:255',
                'books.*.kategori_buku'    => 'required|string|max:100',
                'books.*.kondisi_buku'     => 'required|string|max:100',
                'books.*.sinopsis'         => 'required|string|max:2000',
                'books.*.jumlah_halaman'   => 'required|integer|min:1',
                'books.*.bahasa'           => 'required|string|max:100',
                'books.*.jumlah_eksemplar' => 'required|integer|min:1',
                'books.*.foto_buku'        => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            foreach ($books as $index => $bookData) {
                $file = $request->file("books.$index.foto_buku");
                $photoPath = null;
                if ($file) {
                    $photoPath = $file->store('grants', 'public');
                }

                Grant::create([
                    'nama_pemberi'      => $request->nama_pemberi,
                    'kontak_pemberi'    => $request->kontak_pemberi,
                    'alamat_pengirim'   => $request->alamat_pengirim,
                    'judul_buku'        => $bookData['judul_buku'],
                    'isbn'              => $bookData['isbn'] ?? null,
                    'penerbit_buku'     => $bookData['penerbit_buku'] ?? null,
                    'tahun_terbit'      => $bookData['tahun_terbit'] ?? null,
                    'penulis_buku'      => $bookData['penulis_buku'] ?? null,
                    'kategori_buku'     => $bookData['kategori_buku'],
                    'kondisi_buku'      => $bookData['kondisi_buku'],
                    'sinopsis'          => $bookData['sinopsis'],
                    'jumlah_halaman'    => $bookData['jumlah_halaman'],
                    'bahasa'            => $bookData['bahasa'],
                    'jumlah_eksemplar'  => $bookData['jumlah_eksemplar'],
                    'foto_buku'         => $photoPath,
                    'status_hibah'      => 'pending',
                    'user_id'           => Auth::id() ?? 1,
                ]);
            }

            return redirect()->route('grants.index')->with('success', 'Data hibah berhasil dicatat! Silakan tunggu verifikasi.');
        }

        // Fallback untuk form lama yang masih mengirim field flat
        $request->validate([
            'nama_pemberi'     => 'required|string|max:255',
            'kontak_pemberi'   => 'required|string|max:13|regex:/^[0-9]+$/',
            'judul_buku'       => 'required|string|max:255',
            'isbn'             => 'nullable|string|max:255',
            'penerbit_buku'    => 'nullable|string|max:255',
            'tahun_terbit'     => 'nullable|integer|min:1900|max:2100',
            'penulis_buku'     => 'nullable|string|max:255',
            'kategori_buku'    => 'required',
            'kondisi_buku'     => 'required|string|max:100',
            'sinopsis'         => 'required|string|max:2000',
            'jumlah_halaman'   => 'required|integer|min:1',
            'bahasa'           => 'required|string|max:100',
            'alamat_pengirim'  => 'required|string',
            'jumlah_eksemplar' => 'required|integer|min:1',
            'foto_buku'        => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $kategoriFormatted = is_array($request->kategori_buku)
            ? implode(', ', $request->kategori_buku)
            : $request->kategori_buku;

        $data = [
            'nama_pemberi'     => $request->nama_pemberi,
            'kontak_pemberi'   => $request->kontak_pemberi,
            'alamat_pengirim'  => $request->alamat_pengirim,
            'judul_buku'       => $request->judul_buku,
            'isbn'             => $request->isbn,
            'penerbit_buku'    => $request->penerbit_buku,
            'tahun_terbit'     => $request->tahun_terbit,
            'penulis_buku'     => $request->penulis_buku,
            'kategori_buku'    => $kategoriFormatted,
            'kondisi_buku'     => $request->kondisi_buku,
            'sinopsis'         => $request->sinopsis,
            'jumlah_halaman'   => $request->jumlah_halaman,
            'bahasa'           => $request->bahasa,
            'jumlah_eksemplar' => $request->jumlah_eksemplar,
            'status_hibah'     => 'pending',
            'user_id'          => Auth::id() ?? 1,
        ];

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
    public function approve(Request $request, $id)
    {
        $grant = Grant::findOrFail($id);

        if ($grant->status_hibah === 'disetujui') {
            return redirect()->back()->with('error', 'Buku ini sudah disetujui sebelumnya.');
        }

        // Validasi input rack_id jika dikirim melalui form modal persetujuan
        $request->validate([
            'rack_id' => 'required|exists:racks,id',
        ], [
            'rack_id.required' => 'Silakan pilih lokasi rak buku terlebih dahulu!',
            'rack_id.exists'   => 'Rak yang dipilih tidak valid!',
        ]);

        $rackId = $request->rack_id;

        $kategoriBuku = is_array($grant->kategori_buku)
            ? implode(', ', $grant->kategori_buku)
            : ($grant->kategori_buku ?? ($grant->judul_buku ?? 'Umum'));

        $judulBukuAkhir = !empty(trim((string) $grant->judul_buku))
            ? $grant->judul_buku
            : 'Buku ' . $kategoriBuku . ' (Hibah dari ' . $grant->nama_pemberi . ')';

        $jumlahEksemplar = (int) $grant->jumlah_eksemplar;
        if ($jumlahEksemplar < 1) {
            $jumlahEksemplar = 1;
        }

        // 1. Cari nomor urut terakhir dari tabel books
        $lastBookId = Book::max('id') ?? 0;

        $lastBook = null;

        // 2. Looping sesuai jumlah eksemplar
        for ($i = 0; $i < $jumlahEksemplar; $i++) {
            // Hitung nomor urut berikutnya
            $nextNumber = $lastBookId + $i + 1;

            // Format nomor urut jadi 5 digit (misal: 00001, 00002)
            $formattedNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // Generasi Kode QR Custom
            $customKodeQr = 'SDN32LA-HB-' . $formattedNumber;

            $lastBook = Book::create([
                'judul'         => $judulBukuAkhir,
                'penulis'       => $grant->penulis_buku ?? 'Tidak Diketahui',
                'penerbit'      => $grant->penerbit_buku ?? 'Hibah/Sumbangan',
                'kategori_buku' => $kategoriBuku,
                'tahun_terbit'  => $grant->tahun_terbit ?? date('Y'),
                'asal_buku'     => 'hibah',
                'rack_id'       => $rackId,
                'foto'          => $grant->foto_buku,
                'kode_qr'       => $customKodeQr,
                'status'        => 'tersedia',
                'isbn'          => $grant->isbn ?? null,
                'bahasa'        => $grant->bahasa ?? null,
                'halaman'       => $grant->jumlah_halaman ?? null,
                'sinopsis'      => $grant->sinopsis ?? null,
            ]);
        }

        // Update status hibah & hubungkan ke ID buku terakhir
        $grant->update([
            'status_hibah' => 'disetujui',
            'book_id'      => $lastBook->id
        ]);

        return redirect()->route('grants.index')->with('success', 'Hibah disetujui! ' . $jumlahEksemplar . ' eksemplar buku berhasil ditambahkan dengan Kode QR Custom.');
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

    public function destroy($id)
    {
        // 1. Cari data hibah berdasarkan ID
        $grant = \App\Models\Grant::findOrFail($id);

        // 2. Cek apakah ada file foto terkait pada pengajuan hibah ini, lalu hapus dari storage
        if ($grant->foto_buku && Storage::disk('public')->exists($grant->foto_buku)) {
            Storage::disk('public')->delete($grant->foto_buku);
        }

        // 3. Hapus data dari database
        $grant->delete();

        // 4. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('grants.index')->with('success', 'Data hibah yang ditolak berhasil dihapus.');
    }
}
