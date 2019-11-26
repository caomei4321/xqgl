<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patrol extends Model
{
    protected $fillable = [
        'user_id', 'patrol_matter_id', 'end_at', 'entity_name', 'distance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patrol_matter()
    {
        return $this->hasMany(PatrolMatter::class);
    }
}
