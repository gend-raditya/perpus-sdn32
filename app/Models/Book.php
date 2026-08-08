<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // Tambahkan baris ini
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'kategori_buku',
        'tahun_terbit',
        'stok',
        'asal_buku',
        'rack_id',
        'kode_qr',
        'status',
        'foto'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // protected $casts = [
    //     'kategori_buku' => 'array', // Otomatis mengubah JSON DB <-> Array PHP
    // ];

    // Di Book.php & Grant.php
    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }
}
