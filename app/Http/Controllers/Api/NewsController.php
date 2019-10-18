<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index(News $news)
    {
        $news = $news->orderBy('created_at', 'desc')->limit(7)->get();
        return $news;
    }

    public function newsDetail(Request $request, News $news)
    {
        $news = $news->find($request->id);
        return $news;
    }
}
