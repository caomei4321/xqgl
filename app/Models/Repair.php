<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $fillable = [
        'user_id', 'address', 'description', 'status', 'bad_img', 'good_img'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBadImgUrlAttribute()
    {
        return env('APP_URL').$this->bad_img;
    }

    public function getGoodImgUrlAttribute()
    {
        return env('APP_URL').$this->good_img;
    }

    public function getRepairStatusAttribute()
    {
        if ($this->status === 1) {
            return '维修完成';
        } else {
            return '维修未完成';
        }
    }
}
