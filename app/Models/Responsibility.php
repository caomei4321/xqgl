<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    protected $table = 'responsibility';

    protected $fillable = [
        'category_id', 'item', 'county', 'town', 'legal_doc', 'subject_duty', 'cooperate_duty', 'deadline'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
