<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pemberi',
        'kontak_pemberi',
        'alamat_pengirim',
        'judul_buku',
        'isbn',
        'penerbit_buku',
        'tahun_terbit',
        'penulis_buku',
        'kategori_buku',
        'kondisi_buku',
        'sinopsis',
        'jumlah_halaman',
        'bahasa',
        'jumlah_eksemplar',
        'deskripsi_kondisi',
        'foto_buku',
        'status_hibah',
        'book_id'
    ];

    /**
     * Konversi otomatis atribut kategori_buku
     */
    protected $casts = [
        'kategori_buku' => 'array', // Wajib ada untuk Opsi 2 (Multiple Checkbox)
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Di Book.php & Grant.php
    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }
}
