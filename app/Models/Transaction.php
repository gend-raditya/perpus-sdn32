<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Kosongkan guarded agar semua kolom diizinkan (Mass Assignment)
    protected $guarded = [];

    // Relasi ke Member
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Relasi ke Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
