<?php

namespace App\Http\Controllers\Admin;

use App\Models\PatrolMatter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PatrolMattersController extends Controller
{
    public function index(PatrolMatter $patrolMatter)
    {
        $patrolMatters = $patrolMatter->orderBy('created_at', 'desc')->paginate();

        return view('admin.patrolMatter.index', compact('patrolMatters'));
    }

    public function show(PatrolMatter $patrolMatter)
    {
        return view('admin.patrolMatter.show', compact('patrolMatter'));
    }

    public function destroy(PatrolMatter $patrolMatter)
    {
        $patrolMatter->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
}
