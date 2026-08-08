<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $fillable = ['code', 'name', 'location'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function grants()
    {
        return $this->hasMany(Grant::class);
    }
}
