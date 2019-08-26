<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityPart extends Model
{
    protected $table = 'city_parts';

    protected $fillable = [
        'things', 'num', 'kind_id', 'info', 'status'
    ];
}
