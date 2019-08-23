<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matter extends Model
{
    protected $fillable = [
        'title', 'address', 'content', 'image'
    ];

    public function situation()
    {
        return $this->hasOne('App\Models\Situation', 'matter_id');
    }
}
