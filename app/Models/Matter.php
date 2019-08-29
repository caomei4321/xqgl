<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matter extends Model
{
    protected $fillable = [
        'title', 'address', 'content', 'image', 'accept_num', 'time_limit', 'work_num', 'level', 'type', 'source', 'is_reply', 'is_secret', 'contact_name', 'contact_phone', 'reply_remark', 'category_id', 'suggestion', 'approval', 'result', 'form', 'allocate', 'status'
    ];

    public function situation()
    {
        return $this->hasOne('App\Models\Situation', 'matter_id');
    }
}
