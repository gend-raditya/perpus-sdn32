<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    // Tambahkan baris ini untuk "memberi izin" kolom mana saja yang boleh diisi
    protected $fillable = [
        // 'user_id',
        'nisn',
        'nama_lengkap',
        'peran',
        'no_hp',
        'alamat',
        'foto',
    ];

    // Buat relasi ke User biar gampang manggil emailnya nanti
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'member_id');
    }
}
