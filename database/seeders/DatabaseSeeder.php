<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT AKUN ADMIN/PETUGAS (Untuk Login)
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@sdn32.com',
            'password' => Hash::make('password'), // Login pakai password ini
        ]);

        // 2. BUAT DATA MEMBER (Tanpa Akun User sesuai diskusi kita)
        Member::create([
            'nama_lengkap' => 'Budi Santoso',
            'nisn' => '1234567890',
            'peran' => 'siswa',
            'no_hp' => '08123456789',
            'alamat' => 'Lubuk Alung',
        ]);

        Member::create([
            'nama_lengkap' => 'Faiz Ramadhan',
            'nisn' => '23109402342',
            'peran' => 'alumni',
            'no_hp' => '0838292923',
            'alamat' => 'Bandung',
        ]);

        // 3. BUAT DATA BUKU DEFAULT
        Book::create([
            'judul' => 'Si Kancil dan Buaya',
            'penulis' => 'Anonim',
            'penerbit' => 'Erlangga',
            'tahun_terbit' => 2020,
            'stok' => 5,
            'status' => 'tersedia',
            'kode_qr' => 'B001',
        ]);

        Book::create([
            'judul' => 'Matematika Kelas 4',
            'penulis' => 'Kemendikbud',
            'penerbit' => 'Kemendikbud',
            'tahun_terbit' => 2022,
            'stok' => 20,
            'status' => 'tersedia',
            'kode_qr' => 'B002',
        ]);
    }
}
