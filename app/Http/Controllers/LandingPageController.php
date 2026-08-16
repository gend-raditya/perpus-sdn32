<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\Book;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman landing page utama
     */
    public function index(Request $request)
    {
        // 1. Ambil 6 data hibah terbaru yang sudah disetujui
        $recentGrants = Grant::where('status_hibah', 'disetujui')
            ->latest()
            ->take(6)
            ->get();

        // 2. Hitung statistik untuk bagian "Stats" di landing page
        $totalBuku = Book::count();


        // Hitung buku yang berasal dari hibah saja
        $totalHibah = Grant::where('status_hibah', 'disetujui')->count();

        $totalAnggota = Member::count();

        // Hitung siswa/member yang aktif (pernah melakukan transaksi)
        // $siswaAktif = Transaction::distinct('member_id')->count();

        // =========================================================================
        // 3. LOGIC PENCARIAN BUKU UNTUK SISWA (GROUP BY & STOK)
        // =========================================================================
        // Select kolom unik, hitung total stok fisik, dan hitung yang ready
        $query = Book::select('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto')
            ->selectRaw('count(*) as total_stok')
            ->selectRaw('SUM(CASE WHEN status = "tersedia" THEN 1 ELSE 0 END) as stok_tersedia')
            ->selectRaw('MAX(created_at) as last_added');

        // Jika siswa mengetik sesuatu di kolom search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });

            $books = $query->groupBy('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto')
                ->orderBy('last_added', 'desc')
                ->get();
        } else {
            // Jika halaman pertama kali dibuka, batasi 8 kelompok buku terbaru
            $books = $query->groupBy('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto')
                ->orderBy('last_added', 'desc')
                ->take(8)
                ->get();
        }
        $listTahun = Book::select('tahun_terbit')
            ->whereNotNull('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');

        // Kirim semua variabel ke view welcome
        return view('welcome', compact('recentGrants', 'totalBuku', 'totalHibah', 'totalAnggota', 'books', 'listTahun'));
    }

    /**
     * Menampilkan form hibah buku publik
     */
    public function createGrant()
    {
        return view('public_grants_create');
    }

    /**
     * Menyimpan data hibah dari form publik
     */
    public function storeGrant(Request $request)
    {
        $rules = [
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
        ];

        $validated = $request->validate($rules);

        $createdGrantIds = [];

        foreach ($validated['books'] as $index => $bookData) {
            // handle file upload for this book index
            $file = $request->file("books.$index.foto_buku");
            $photoPath = null;
            if ($file) {
                $photoPath = $file->store('grants', 'public');
            }

            $grant = Grant::create([
                'nama_pemberi'      => $validated['nama_pemberi'],
                'kontak_pemberi'    => $validated['kontak_pemberi'],
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
                'alamat_pengirim'   => $validated['alamat_pengirim'],
                'jumlah_eksemplar'  => $bookData['jumlah_eksemplar'],
                'deskripsi_kondisi' => $bookData['sinopsis'],
                'foto_buku'         => $photoPath,
                'status_hibah'      => 'pending',
                'user_id'           => null,
            ]);

            $createdGrantIds[] = $grant->id;
        }

        // Redirect to success page for the first created grant (keeps backward compatibility)
        $firstId = $createdGrantIds[0] ?? null;
        if ($firstId) {
            return redirect()->route('public.grants.success', $firstId)
                ->with('success', 'Terima kasih, data donasi berhasil dikirim!');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
    }

    /**
     * Menampilkan halaman sukses setelah isi form
     */
    public function success($id)
    {
        $grant = Grant::findOrFail($id);
        return view('public_grants_success', compact('grant'));
    }
}
