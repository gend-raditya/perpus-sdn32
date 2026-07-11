<?php

namespace App\Http\Controllers;

use App\Models\Grant;
use App\Models\Book;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
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

        // Hitung siswa/member yang aktif (pernah melakukan transaksi)
        $siswaAktif = \App\Models\Transaction::distinct('member_id')->count();

        // =========================================================================
        // 3. LOGIC PENCARIAN BUKU UNTUK SISWA (UPGRADED WITH GROUP BY & STOK)
        // =========================================================================
        // Kita select kolom unik, lalu hitung total stok fisik, dan hitung yang ready
        $query = Book::select('judul', 'penulis', 'penerbit', 'asal_buku', 'tahun_terbit', 'foto')
            ->selectRaw('count(*) as total_stok')
            ->selectRaw('SUM(CASE WHEN status = "tersedia" THEN 1 ELSE 0 END) as stok_tersedia')
            ->selectRaw('MAX(created_at) as last_added');

        // Jika siswa mengetik sesuatu di kolom search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
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

        // Kirim semua variabel ke view welcome
        return view('welcome', compact('recentGrants', 'totalBuku', 'totalHibah', 'siswaAktif', 'books'));
    }

    public function createGrant()
    {
        return view('public_grants_create');
    }

    public function storeGrant(Request $request)
    {
        $request->validate([
            'nama_pemberi' => 'required|string|max:255',
            'judul_buku'   => 'required|string|max:255',
            'penulis_buku' => 'required',
            'foto_buku'    => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();
        $data['status_hibah'] = 'pending';

        // Kita set user_id default ke 1 (Admin) karena pengirimnya adalah publik (bukan user login)
        $data['user_id'] = 1;

        if ($request->hasFile('foto_buku')) {
            $data['foto_buku'] = $request->file('foto_buku')->store('grants', 'public');
        }

        // Simpan ke database dan ambil object-nya
        $grant = Grant::create($data);

        // Alihkan ke route sukses dengan mengirimkan ID hibah yang baru dibuat
        return redirect()->route('public.grants.success', $grant->id)
            ->with('success', 'Data hibah berhasil dikirim!');
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
