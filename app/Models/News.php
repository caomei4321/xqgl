<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'header', 'sentence'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
