<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('user')->latest()->get();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:users,email',
            'peran' => 'required',
            'no_hp' => 'nullable',
        ]);

        // 1. Buat User baru dulu (buat login mereka nanti)
        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => bcrypt('12345678'), // Default password
        ]);

        // 2. Hubungkan ke data Member
        Member::create([
            'user_id' => $user->id,
            'nisn' => $request->nisn,
            'nama_lengkap' => $request->nama_lengkap,
            'peran' => $request->peran,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('members.index')->with('success', 'Anggota berhasil didaftarkan!');
    }

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
