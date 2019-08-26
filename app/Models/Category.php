<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'description',
    ];

    public $timestamps = false;

    public function situation()
    {
        return $this->hasMany('App\Models\Situation');
    }
}
