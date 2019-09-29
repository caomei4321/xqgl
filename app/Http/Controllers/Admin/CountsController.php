<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Patrol;
use App\Models\Situation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CountsController extends Controller
{
    public function index(Situation  $situation, Patrol $patrol)
    {
        // 今日未完成任务
        $unfinished = DB::table('user_has_matters')->where('status', '0')->whereBetween('updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])->count();
        // 今日所有任务
        $all = DB::table('user_has_matters')->whereBetween('updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])->count();
        // 总任务
        $AllMatter = $this->allMatter();
        return view('admin.count.index', compact('unfinished', 'all', 'AllMatter'));
    }
    // 总任务量
    public function allMatter()
    {
        $all = DB::table('user_has_matters')->count();
        $unfinish = DB::table('user_has_matters')->where('status', '0')->count();
        $data = [
            'all' => $all,
            'unfinished' => $unfinish,
            'finished' => $all - $unfinish
        ];
        return $data;
    }

    // 总人数在巡查
    public function allUserPatrol(Patrol $patrol)
    {
        $sub_query = Patrol::orderBy('created_at', 'desc');
        $patrols = Patrol::select('user_id', 'end_at', 'created_at')
            ->from(DB::raw('('.$sub_query->toSql().') as a'))
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:i:s', time())])
            ->groupBy('user_id')
            ->orderBy('created_at', 'desc')
            ->get();
        $info = [];
        foreach ($patrols as $patrol) {
            $data = [
                'name'=> $patrol->user->name,
                'created_at' => $patrol->created_at->toDateTimeString(),
                'end_at' => $patrol->end_at
            ];
            if (!$data['end_at']) {
                array_push($info, $data);
            }
        }

        return response()->json($info);

    }


    // 巡查总量
    public function guiJi(Patrol $patrol, User $user)
    {
        $patrols = Patrol::with('User')->whereBetween('created_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])->get();
        $info = [];
        foreach ($patrols as $patrol) {
            $data['name'] = $patrol->user->name;
            array_push($info, $data['name']);
        }
        $ret = array_count_values($info);

        return response()->json($ret);
    }
}
