<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolMatter extends Model
{
    protected $fillable = [
        'title', 'user_id', 'content', 'suggest', 'latitude', 'longitude', 'image', 'status', 'patrol_id', 'images'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImgUrlAttribute()
    {
        return env('APP_URL').$this->image;
    }
}
