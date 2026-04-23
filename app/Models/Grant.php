<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    protected $fillable = ['user_id', 'judul_buku', 'penulis_buku', 'status_hibah', 'book_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
