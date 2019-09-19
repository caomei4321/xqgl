<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description',
    ];

    public $timestamps = false;

    public function situation()
    {
        return $this->hasMany('App\Models\Situation');
    }

    public function responsibility()
    {
        return $this->hasMany(Responsibility::class);
    }
}
