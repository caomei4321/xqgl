<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\News;
use App\Models\ProgramUser;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(News $news)
    {
        $news = $news->orderBy('created_at', 'desc')->limit(7)->get();
        return $news;
    }

    // 详情+评论
    public function newsDetail(Request $request, News $news, ProgramUser $programUser)
    {
        $news = $news->find($request->id);
        $news->comments;

        foreach ($news->comments as $comment) {
            $programuser = $programUser->find($comment->user_id);
            $comment->nickname = $programuser->nickname;
            $comment->avatarurl = $programuser->avatarurl;
        }

        return $news;
    }

    // 评论
    public function comment(Request $request, Comment $comment)
    {
        $data = $request->only(['news_id','content']);
        $data['user_id'] = $this->user()->id;
        $comment->fill($data);
        $comment->save();

        return response()->json(['status' => '201', 'msg' => '评论成功', 'data' => $comment ]);

    }
}
