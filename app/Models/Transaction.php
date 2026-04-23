<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Tambahin kolom-kolom ini biar bisa diinput otomatis
    protected $fillable = [
        'member_id',
        'book_id',
        'tanggal_pinjam',
        'deadline',
        'status'
    ];

    // Relasi ke Member (Biar bisa nampilin nama murid)
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Relasi ke Book (Biar bisa nampilin judul buku)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
