<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityPart extends Model
{
    protected $fillable = [
        'things', 'num', 'kind_id', 'info', 'status'
    ];
}
