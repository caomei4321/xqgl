<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hat extends Model
{
    protected $fillable = [
        'device_serial', 'alarm_info', 'sum', 'alarm_time', 'alarm_img_url'
    ];
}
