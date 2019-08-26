<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'category_id', 'author_id', 'longitude', 'latitude', 'address', 'title', 'show', 'bad_img', 'executor_id', 'good_img', 'reply', 'reason'
    ];
}
