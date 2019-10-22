<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'news_id','content'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function programuser()
    {
        return $this->belongsTo(ProgramUser::class);
    }
}
