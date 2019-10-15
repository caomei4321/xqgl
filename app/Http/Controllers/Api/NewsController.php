<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use Illuminate\Http\Request;

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
