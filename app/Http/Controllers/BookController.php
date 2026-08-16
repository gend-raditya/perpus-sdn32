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
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'kategori_buku' => 'required|string|max:255',
            'asal_buku'     => 'required|in:pengadaan,pembelian_dana_bos,hibah',
            'tahun_terbit'  => 'required|numeric',
            'jumlah'        => 'required|integer|min:1',
            'rack_id'       => 'required|exists:racks,id',
            'foto'          => 'required|image|mimes:jpeg,jpg,png|max:2048', // Foto wajib saat tambah
            'isbn'          => 'required|string|max:255',
            'bahasa'        => 'required|string|max:100',
            'halaman'       => 'required|integer|min:1',
            'sinopsis'      => 'required|string',
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
                'foto'          => $pathFoto, // Path foto yang sama dimasukkan ke setiap eksemplar buku ini
                'isbn'          => $request->isbn ?? null,
            'bahasa'        => $request->bahasa ?? null,
            'halaman'       => $request->halaman ?? null,
            'sinopsis'      => $request->sinopsis ?? null,
            ]);
        }

        return redirect()->route('books.index')->with('success', $request->jumlah . ' buku berhasil ditambah!');
    }

    // Fungsi untuk menampilkan daftar buku (dengan pengelompokan)
    public function index()
    {
        // Masukkan 'foto' dan 'kategori_buku' ke select dan groupBy agar datanya ketarik ke Blade
        $books = Book::select('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto', 'rack_id', 'kategori_buku', 'isbn')
            ->selectRaw('MAX(id) as id')
            ->selectRaw('count(*) as total_stok')
            ->selectRaw('SUM(CASE WHEN status = "tersedia" THEN 1 ELSE 0 END) as stok_tersedia')
            ->selectRaw('MAX(created_at) as last_added')
            ->groupBy('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto', 'rack_id', 'kategori_buku', 'isbn')
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
        $judul = $request->query('judul');

        if (!$judul) {
            return response()->json([]);
        }

        $books = Book::where('judul', $judul)->get();

        return response()->json($books);
    }

    // Endpoint untuk memperbarui stok kelompok buku (dijalankan via AJAX dari tabel)
    public function updateStockGroup(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'nullable|string',
            'penerbit' => 'nullable|string',
            'tahun_terbit' => 'nullable|numeric',
            'isbn' => 'nullable|string',
            'new_total' => 'required|integer|min:0',
        ]);

        $judul = $request->input('judul');
        $penulis = $request->input('penulis');
        $penerbit = $request->input('penerbit');
        $tahun = $request->input('tahun_terbit');
        $isbn = $request->input('isbn');
        $newTotal = (int) $request->input('new_total');

        // Cari koleksi yang sesuai
        $query = Book::where('judul', $judul)
            ->where('penulis', $penulis ?? Book::raw('penulis'))
            ->where('penerbit', $penerbit ?? Book::raw('penerbit'))
            ->where('tahun_terbit', $tahun ?? Book::raw('tahun_terbit'));

        // NOTE: jika ada nilai NULL pada kolom, where(...) dengan NULL tidak cocok.
        // Untuk fleksibilitas, gunakan whereRaw fallback jika value tidak diberikan.

        if (is_null($penulis)) {
            $query = Book::where('judul', $judul);
        }

        $currentCount = $query->count();

        // Simpan riwayat perubahan stok
        \App\Models\StockHistory::create([
            'judul' => $judul,
            'penulis' => $penulis,
            'penerbit' => $penerbit,
            'tahun_terbit' => $tahun,
            'isbn' => $isbn,
            'previous_total' => $currentCount,
            'new_total' => $newTotal,
            'user_id' => auth()->id(),
            'note' => 'Perubahan stok melalui tabel (inline edit)'
        ]);

        if ($newTotal == $currentCount) {
            return response()->json(['status' => 'ok', 'message' => 'Tidak ada perubahan stok']);
        }
        if ($newTotal > $currentCount) {
            // Tambah eksemplar baru: duplikat data sample dari salah satu row
            $sample = $query->first();
            if (!$sample) {
                return response()->json(['status' => 'error', 'message' => 'Contoh data buku tidak ditemukan untuk menambah stok'], 422);
            }

            $toAdd = $newTotal - $currentCount;
            $prefix = strtoupper(substr($sample->judul, 0, 3));

            for ($i = 1; $i <= $toAdd; $i++) {
                // Nomor urut buat unik sederhana: ambil count + i
                $nomorUrut = $currentCount + $i;
                $kodeQr = 'SDN32-' . $prefix . '-' . date('Y') . '-' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT) . '-' . \Illuminate\Support\Str::random(3);

                Book::create([
                    'judul' => $sample->judul,
                    'penulis' => $sample->penulis,
                    'penerbit' => $sample->penerbit,
                    'kategori_buku' => $sample->kategori_buku,
                    'tahun_terbit' => $sample->tahun_terbit,
                    'asal_buku' => $sample->asal_buku,
                    'rack_id' => $sample->rack_id,
                    'kode_qr' => $kodeQr,
                    'status' => 'tersedia',
                    'foto' => $sample->foto,
                    'isbn' => $sample->isbn,
                ]);
            }

            return response()->json(['status' => 'ok', 'message' => 'Stok berhasil ditambah']);
        }

        // Jika newTotal < currentCount => hapus eksemplar yang berstatus tersedia
        $toRemove = $currentCount - $newTotal;
        $available = $query->where('status', 'tersedia')->orderBy('id', 'desc')->limit($toRemove)->get();

        if ($available->count() < $toRemove) {
            return response()->json(['status' => 'error', 'message' => 'Tidak cukup eksemplar tersedia untuk dihapus. Hapus gagal.'], 422);
        }

        // Hapus eksemplar yang dipilih
        $ids = $available->pluck('id')->toArray();
        Book::whereIn('id', $ids)->delete();

        return response()->json(['status' => 'ok', 'message' => 'Stok berhasil dikurangi']);
    }

    // API: riwayat stok (JSON)
    public function stockHistoryJson(Request $request)
    {
        $judul = $request->query('judul');
        if (!$judul) return response()->json([]);

        $query = \App\Models\StockHistory::where('judul', $judul);
        if ($request->has('tahun_terbit')) {
            $query->where('tahun_terbit', $request->query('tahun_terbit'));
        }
        if ($request->has('isbn')) {
            $query->where('isbn', $request->query('isbn'));
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        return response()->json($data);
    }

    public function create()
    {
        return redirect()->route('books.index');
    }

    public function show(Book $book)
    {
        return redirect()->route('books.index');
    }

    public function edit(Book $book)
    {
        return redirect()->route('books.index');
    }

    public function update(Request $request, Book $book)
    {
        // 1. Validasi input
        $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'kategori_buku' => 'required|string|max:255',
            'penerbit'      => 'required|string|max:255',
            'tahun_terbit'  => 'required|numeric',
            'rack_id'       => 'required|exists:racks,id',
            'asal_buku'     => 'required',
            'jumlah'        => 'required|numeric|min:1',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Foto opsional saat edit
            'isbn'          => 'required|string|max:255',
            'bahasa'        => 'required|string|max:100',
            'halaman'       => 'required|integer|min:1',
            'sinopsis'      => 'required|string',
        ]);

        // 2. Cari data sampel buku lama sebelum di-update
        $oldBook = $book;

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
            'isbn'          => $request->isbn ?? null,
            'bahasa'        => $request->bahasa ?? null,
            'halaman'       => $request->halaman ?? null,
            'sinopsis'      => $request->sinopsis ?? null,
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
                    'isbn'          => $request->isbn ?? null,
                    'bahasa'        => $request->bahasa ?? null,
                    'halaman'       => $request->halaman ?? null,
                    'sinopsis'      => $request->sinopsis ?? null,
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
