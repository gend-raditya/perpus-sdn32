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
        if ($request->image_captured) {
            $img = $request->image_captured; // Ini isinya data:image/jpeg;base64,xxxx

            // Buat nama file unik
            $fileName = time() . '_' . uniqid() . '.jpg';

            // Bersihkan string base64 (buang header "data:image/jpeg;base64,")
            $image_parts = explode(";base64,", $img);
            $image_base64 = base64_decode($image_parts[1]);

            // Tentukan path penyimpanan (di folder storage/app/public/members)
            $path = 'members/' . $fileName;

            // Simpan file ke storage
            Storage::disk('public')->put($path, $image_base64);

            // Tambahkan nama file ke array data untuk disimpan ke DB
            // Pastikan di migrasi tabel members kamu sudah ada kolom 'foto'
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
}
