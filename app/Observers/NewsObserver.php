<?php
namespace App\Observers;

use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsObserver
{
    public function deleted(News $news)
    {
        DB::table('comments')->where('news_id', $news->id)->delete();
    }
}