<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramImage extends Model
{
    protected $table = 'mini_program_images';

    protected $fillable = ['image'];

    public function getImgUrlAttribute()
    {
        return env('APP_URL').$this->image;
    }
}
