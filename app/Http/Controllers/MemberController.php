<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class MemberController extends Controller
{
    // Menampilkan daftar semua anggota
    public function index()
    {
        $members = Member::with('user')->latest()->get();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    // Proses ketika form tambah member disubmit
    public function store(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'nama_lengkap' => 'required',
            'nisn'         => 'nullable|unique:members,nisn',
            'peran'        => 'required',
        ]);

        // Ambil semua data input kecuali token dan data foto mentah
        $data = $request->only(['nisn', 'nama_lengkap', 'peran', 'no_hp', 'alamat']);

        // 2. LOGIKA PROSES FOTO WEBCAM
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            // Ekstensi otomatis dinamis (.png/.jpg/.jpeg) mengikuti file asli
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'members/' . $fileName;

            // Simpan file asli ke storage
            Storage::disk('public')->put($path, file_get_contents($file));
            $data['foto'] = $path;
        }

        // 3. SIMPAN KE DATABASE
        \App\Models\Member::create($data);

        return redirect()->route('members.index')->with('success', 'Anggota ' . $request->nama_lengkap . ' berhasil ditambahkan!');
    }

    //
    public function printCard($id)
    {
        $member = Member::with('user')->findOrFail($id);
        return view('members.print_card', compact('member'));
    }

    public function show($id)
    {
        // Cari data member beserta data user-nya
        $member = Member::with('user')->findOrFail($id);

        // Lempar ke view detail
        return view('members.show', compact('member'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'nisn' => 'nullable|string',
            'nama_lengkap' => 'required|string|max:255',
            'peran' => 'required|in:siswa,guru,petugas',
            'no_hp' => 'nullable|string',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $member = Member::findOrFail($id);
        $member->update($request->all());

        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage jika sebelumnya sudah ada foto
            if ($member->foto) {
                Storage::disk('public')->delete($member->foto);
            }

            $file = $request->file('foto');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'members/' . $fileName;
            Storage::disk('public')->put($path, file_get_contents($file));
            $data['foto'] = $path;
        }

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil diperbarui!');
    }

    // Tambahkan fungsi ini di dalam MemberController.php kamu (misal di paling bawah)
    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        // 1. Cek apakah ada transaksi aktif yang bukunya BELUM dikembalikan
        $hasActiveBorrow = $member->transactions()
            ->where('status', 'pinjam') // Mencari yang statusnya masih dipinjam
            ->exists();

        if ($hasActiveBorrow) {
            // Jika masih ada buku yang dipinjam, blokir proses hapus
            return redirect()->route('members.index')
                ->with('error', 'Anggota tidak bisa dihapus karena masih meminjam buku!');
        }

        // 2. Jika tidak ada pinjaman aktif, hapus riwayat transaksi lamanya terlebih dahulu
        // agar foreign key constraint di MySQL tidak protes (Integrity violation)
        $member->transactions()->delete();

        // 3. Baru hapus data anggota perpus tersebut
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Anggota dan riwayat transaksinya berhasil dihapus!');
    }

    // Fungsi untuk mencetak kartu anggota secara batch
    public function printBatch(Request $request)
    {
        $ids = $request->input('member_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada anggota yang dipilih.');
        }

        $members = Member::whereIn('id', $ids)->get();

        return view('members.print_cards_batch', compact('members'));
    }
}
