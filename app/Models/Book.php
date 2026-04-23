<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // Tambahkan baris ini
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'asal_buku',
        'kode_qr',
        'status'
    ];
}
