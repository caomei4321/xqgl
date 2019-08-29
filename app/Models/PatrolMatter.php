<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolMatter extends Model
{
    protected $fillable = [
        'title', 'user_id', 'content', 'suggest', 'latitude', 'longitude', 'image', 'status'
    ];

}
