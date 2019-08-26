<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Situation extends Model
{
    protected $table = 'user_has_matters';

    protected $fillable = [
        'matter_id', 'user_id', 'category_id', 'see_image', 'information', 'status'
    ];

    public function matter()
    {
        return $this->belongsTo('App\Models\Matter');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
