<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\Book;
use App\Models\Rack;
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
        // 1. Validasi input yang datang dari form baru
        $request->validate([
            'nama_pemberi'     => 'required|string|max:255',
            'kontak_pemberi'   => 'required|string|max:20',
            'kategori_buku'    => 'required', // Menerima array dari checkbox atau string dari select
            'alamat_pengirim'  => 'required|string',
            'jumlah_eksemplar' => 'required|integer|min:1',
            'foto_buku'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Jika kategori_buku dikirim dalam bentuk array (checkbox), gabungkan menjadi string
        $kategoriFormatted = is_array($request->kategori_buku)
            ? implode(', ', $request->kategori_buku)
            : $request->kategori_buku;

        // 2. Petakan input form baru ke dalam kolom database lama
        $data = [
            'nama_pemberi'     => $request->nama_pemberi,
            'kontak_pemberi'   => $request->kontak_pemberi,
            'alamat_pengirim'  => $request->alamat_pengirim, // Kolom asli database (mengatasi error NOT NULL)
            'kategori_buku'    => $kategoriFormatted,      // Kolom kategori asli
            'jumlah_eksemplar' => $request->jumlah_eksemplar,
            'status_hibah'     => 'pending',

            // Terapkan penyesuaian legacy agar fitur/view lama tidak break:
            'judul_buku'       => $kategoriFormatted,
            'penulis_buku'     => $request->alamat_pengirim,
        ];

        // Mengambil ID Admin/User yang sedang login (fallback ke ID 1 jika tidak ada session)
        $data['user_id'] = Auth::id() ?? 1;

        // Proses Upload Foto jika ada
        if ($request->hasFile('foto_buku')) {
            $path = $request->file('foto_buku')->store('grants', 'public');
            $data['foto_buku'] = $path;
        }

        // 3. Simpan ke database menggunakan array pemetaan di atas
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

        $judulBukuOtomatis = 'Buku ' . $kategoriBuku . ' (Hibah dari ' . $grant->nama_pemberi . ')';

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
                'judul'         => $judulBukuOtomatis,
                'penulis'       => 'Tidak Diketahui',
                'penerbit'      => 'Hibah/Sumbangan',
                'kategori_buku' => $kategoriBuku, // <-- Ditambahkan agar kategori masuk ke tabel books
                'tahun_terbit'  => date('Y'),
                'total_stok'    => 1,
                'stok_tersedia' => 1,
                'asal_buku'     => 'hibah',
                'rack_id'       => $rackId, // Sesuai dengan kolom relasi rak
                'foto'          => $grant->foto_buku,
                'kode_qr'       => $customKodeQr, // <-- Menggunakan Kode QR Custom
                'status'        => 'tersedia'
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
}
