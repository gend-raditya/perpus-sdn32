<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rack;
use App\Models\Grant;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Fungsi untuk menyimpan buku baru (bisa sekaligus banyak dengan jumlah)
    public function store(Request $request)
    {
        $request->validate([
            'judul'         => 'required',
            'penulis'       => 'required',
            'kategori_buku' => 'required|string|max:255',
            'asal_buku'     => 'required|in:pengadaan,pembelian_dana_bos,hibah',
            'tahun_terbit'  => 'required|numeric',
            'jumlah'        => 'required|integer|min:1',
            'rack_id'       => 'required|exists:racks,id',
            'foto'          => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Validasi foto
        ]);

        $pathFoto = null;

        // 1. Proses upload foto di luar loop (biar file-nya cuma disimpan sekali)
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('books', 'public');

            // SINKRONISASI: Jika buku dari hibah dan ada data grant yang cocok, update foto_buku-nya juga
            Grant::where('judul_buku', $request->judul)
                ->orWhere('deskripsi_kondisi', 'like', '%' . $request->judul . '%')
                ->update(['foto_buku' => $pathFoto]);
        }

        $prefix = strtoupper(substr($request->judul, 0, 3));

        // 2. Loop sebanyak jumlah eksemplar yang diinput
        for ($i = 1; $i <= $request->jumlah; $i++) {

            // Generate kode QR unik menggunakan kombinasi microtime/random biar tidak bentrok
            $kodeQr = 'SDN32-' . $prefix . '-' . date('Y') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '-' . Str::random(3);

            Book::create([
                'judul'         => $request->judul,
                'penulis'       => $request->penulis,
                'penerbit'      => $request->penerbit,
                'kategori_buku' => $request->kategori_buku,
                'tahun_terbit'  => $request->tahun_terbit,
                'asal_buku'     => $request->asal_buku,
                'rack_id'       => $request->rack_id,
                'kode_qr'       => $kodeQr,
                'status'        => 'tersedia',
                'foto'          => $pathFoto // Path foto yang sama dimasukkan ke setiap eksemplar buku ini
            ]);
        }

        return redirect()->route('books.index')->with('success', $request->jumlah . ' buku berhasil ditambah!');
    }

    // Fungsi untuk menampilkan daftar buku (dengan pengelompokan)
    public function index()
    {
        // Masukkan 'foto' dan 'kategori_buku' ke select dan groupBy agar datanya ketarik ke Blade
        $books = Book::select('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto', 'rack_id', 'kategori_buku')
            ->selectRaw('MAX(id) as id')
            ->selectRaw('count(*) as total_stok')
            ->selectRaw('SUM(CASE WHEN status = "tersedia" THEN 1 ELSE 0 END) as stok_tersedia')
            ->selectRaw('MAX(created_at) as last_added')
            ->groupBy('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto', 'rack_id', 'kategori_buku')
            ->orderBy('last_added', 'desc')
            ->get();

        $racks = Rack::all();
        $raks  = $racks; // Menyediakan $raks untuk kompatibilitas tampilan blade
        // Ambil daftar tahun unik untuk filter di view admin
        $listTahun = Book::select('tahun_terbit')
            ->whereNotNull('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');

        // PASTIKAN return ke view, bukan return $books
        return view('books.index', compact('books', 'racks', 'raks', 'listTahun'));
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
        $books = Book::where('judul', $judul)->get();

        // Kirim sebagai JSON
        return response()->json($books);
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'kategori_buku' => 'required|string|max:255',
            'penerbit'      => 'nullable|string|max:255',
            'tahun_terbit'  => 'required|numeric',
            'rack_id'       => 'required|exists:racks,id',
            'asal_buku'     => 'required',
            'jumlah'        => 'required|numeric|min:1',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Cari data sampel buku lama sebelum di-update
        $oldBook = Book::findOrFail($id);

        // 3. Simpan relasi data lama untuk pencarian kelompok buku
        $oldQuery = Book::where('judul', $oldBook->judul)
            ->where('penulis', $oldBook->penulis)
            ->where('penerbit', $oldBook->penerbit)
            ->where('tahun_terbit', $oldBook->tahun_terbit);

        // Hitung jumlah eksemplar saat ini
        $currentCount = $oldQuery->count();

        // 4. Handle upload foto baru jika ada
        $fotoPath = $oldBook->foto;
        if ($request->hasFile('foto')) {
            if ($oldBook->foto) {
                Storage::disk('public')->delete($oldBook->foto);
            }
            $fotoPath = $request->file('foto')->store('books', 'public');

            // SINKRONISASI: Perbarui juga foto di tabel Grants menggunakan pencocokan yang lebih luas
            Grant::where('judul_buku', $oldBook->judul)
                ->orWhere('judul_buku', $request->judul)
                ->orWhere('judul_buku', 'like', '%' . $request->judul . '%')
                ->orWhere('deskripsi_kondisi', 'like', '%' . $oldBook->judul . '%')
                ->update(['foto_buku' => $fotoPath]);
        }

        // 5. Update data utama untuk semua eksemplar yang sudah ada
        $oldQuery->update([
            'judul'         => $request->judul,
            'penulis'       => $request->penulis,
            'penerbit'      => $request->penerbit,
            'kategori_buku' => $request->kategori_buku,
            'tahun_terbit'  => $request->tahun_terbit,
            'asal_buku'     => $request->asal_buku,
            'rack_id'       => $request->rack_id,
            'foto'          => $fotoPath,
        ]);

        // 6. Penanganan Penambahan Stok (Jika input `jumlah` > jumlah saat ini)
        $newCount = (int) $request->jumlah;

        if ($newCount > $currentCount) {
            $selisih = $newCount - $currentCount;
            $prefix = strtoupper(substr($request->judul, 0, 3));

            for ($i = 1; $i <= $selisih; $i++) {
                // Generate QR Code unik untuk eksemplar baru
                $nomorUrut = $currentCount + $i;
                $kodeQr = 'SDN32-' . $prefix . '-' . date('Y') . '-' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT) . '-' . Str::random(3);

                Book::create([
                    'judul'         => $request->judul,
                    'penulis'       => $request->penulis,
                    'penerbit'      => $request->penerbit,
                    'kategori_buku' => $request->kategori_buku,
                    'tahun_terbit'  => $request->tahun_terbit,
                    'asal_buku'     => $request->asal_buku,
                    'rack_id'       => $request->rack_id,
                    'kode_qr'       => $kodeQr,
                    'status'        => 'tersedia',
                    'foto'          => $fotoPath,
                ]);
            }
        }

        return redirect()->route('books.index')->with('success', 'Data koleksi buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // 1. Cari sampel buku berdasarkan ID
        $sampleBook = Book::findOrFail($id);

        // 2. Cek apakah ADA SALAH SATU eksemplar dari koleksi buku ini yang punya transaksi
        $hasTransaction = Book::where('judul', $sampleBook->judul)
            ->where('penulis', $sampleBook->penulis)
            ->where('penerbit', $sampleBook->penerbit)
            ->where('tahun_terbit', $sampleBook->tahun_terbit)
            ->whereHas('transactions')
            ->exists();

        if ($hasTransaction) {
            return redirect()->back()->with('error', 'Koleksi buku ini tidak dapat dihapus karena ada eksemplar yang memiliki riwayat peminjaman!');
        }

        // 3. Hapus foto sampul jika ada
        if ($sampleBook->foto) {
            Storage::disk('public')->delete($sampleBook->foto);
        }

        // 4. Hapus seluruh eksemplar buku
        Book::where('judul', $sampleBook->judul)
            ->where('penulis', $sampleBook->penulis)
            ->where('penerbit', $sampleBook->penerbit)
            ->where('tahun_terbit', $sampleBook->tahun_terbit)
            ->delete();

        return redirect()->back()->with('success', 'Seluruh koleksi buku dengan judul tersebut berhasil dihapus!');
    }
}
