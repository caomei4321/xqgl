<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HatsController extends Controller
{
    public function index(Hat $hat)
    {
        $hats = $hat->orderBy('created_at', 'desc')->paginate();
        return view('admin.hats.index', compact('hats'));
    }

    public function destroy(Hat $hat)
    {
        $hat->delete();
        return response()->json(['status'=>'1', 'msg'=>'删除成功']);
    }
}
