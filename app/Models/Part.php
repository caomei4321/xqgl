<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = "parts";

    protected $fillable = [
        'things', 'num', 'kind_id', 'info', 'address', 'longitude', 'latitude', 'image', 'status', 'coordinate_id'
    ];
}
