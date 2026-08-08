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
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama_pemberi'     => 'required|string|max:255',
            'kontak_pemberi'   => 'required|string|max:50',

            // Pastikan kategori_buku dikirim sebagai array dan minimal pilih 1
            'kategori_buku'    => 'required|array|min:1',
            'kategori_buku.*'  => 'string', // Isinya harus string

            'alamat_pengirim'  => 'required|string',
            'jumlah_eksemplar' => 'required|integer|min:1',

            // Input 'sinopsis' di form masuk ke validasi ini
            'sinopsis'         => 'required|string',
            'foto_buku'        => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        // 2. Handle Foto jika ada
        if ($request->hasFile('foto_buku')) {
            $validatedData['foto_buku'] = $request->file('foto_buku')->store('grants', 'public');
        }

        // 3. Simpan ke Database
        $grant = Grant::create([
            'nama_pemberi'      => $validatedData['nama_pemberi'],
            'kontak_pemberi'    => $validatedData['kontak_pemberi'],

            // Langsung masukkan array, Laravel casts yang ubah jadi string/json di DB
            'kategori_buku'     => $validatedData['kategori_buku'],

            'alamat_pengirim'   => $validatedData['alamat_pengirim'],
            'jumlah_eksemplar'  => $validatedData['jumlah_eksemplar'],

            // Petakan input form 'sinopsis' ke kolom DB 'deskripsi_kondisi'
            'deskripsi_kondisi' => $validatedData['sinopsis'],

            'foto_buku'         => $validatedData['foto_buku'] ?? null,
            'status_hibah'      => 'pending',
            'user_id'           => null, // Dikirim oleh publik, bukan admin
        ]);

        return redirect()->route('public.grants.success', $grant->id)
            ->with('success', 'Terima kasih, data donasi berhasil dikirim!');
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
