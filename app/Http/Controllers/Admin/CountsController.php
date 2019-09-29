<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
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
        $this->dataInfo();
        // 在巡查人数
        $userNum = $this->allUserPatrol($patrol);
        $userAll = count(User::all());

        // 12345任务
        $numMatter = $this->numMatter();

        // 群众举报
        $people = $this->people();

        // 总任务
        $AllMatter = $this->allMatter();
        return view('admin.count.index', compact( 'AllMatter', 'userNum', 'userAll', 'numMatter', 'people'));
    }
    // 总任务量
    public function allMatter()
    {
        $all = DB::table('matters')->count();
        $unfinish = DB::table('user_has_matters')->where('status', '0')->count();
        $data = [
            'all' => $all,
            'unfinished' => $unfinish,
            'finished' => $all - $unfinish
        ];
        return $data;
    }
    // 12345任务
    public function numMatter()
    {
        // 今日12345未完成任务
        $unfinished = DB::table('user_has_matters')
            ->join('matters', 'user_has_matters.matter_id', '=', 'matters.id')
            ->where('user_has_matters.status', '0')
            ->where('matters.form','<', '3')
            ->whereBetween('user_has_matters.updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])
            ->count();

        // 今日123456任务
        $all = DB::table('user_has_matters')
            ->join('matters', 'user_has_matters.matter_id', '=', 'matters.id')
            ->where('matters.form','<' ,'3')
            ->whereBetween('user_has_matters.updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])
            ->count();
        $data = [
            'numfinished' => $unfinished,
            'numall' => $all
        ];
        return $data;
    }

    // 群众举报
    public function people()
    {
        // 今日群众举报未完成任务
        $unfinished = DB::table('user_has_matters')
            ->join('matters', 'user_has_matters.matter_id', '=', 'matters.id')
            ->where('user_has_matters.status', '0')
            ->where('matters.form', '3')
            ->whereBetween('user_has_matters.updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])
            ->count();

        // 今日群众举报任务
        $all = DB::table('user_has_matters')
            ->join('matters', 'user_has_matters.matter_id', '=', 'matters.id')
            ->where('matters.form', '3')
            ->whereBetween('user_has_matters.updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])
            ->count();
        $data = [
            'unfinished' => $unfinished,
            'all' => $all
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

       return count($info);

    }

    // 前七天数据
    public function dataInfo()
    {
        $all = DB::table('user_has_matters')->whereBetween('updated_at',[date('Y-m-d 00:00:00',  strtotime("-7 day")),date('Y-m-d H:i:s', time())])
            ->selectRaw('DATE(updated_at) as date,COUNT(*) as total')
            ->groupBy('date')
            ->get();

        foreach ($all as $key=>$value) {
            $unfinished = DB::table('user_has_matters')->where('status', '0')->whereBetween('updated_at',[date('Y-m-d 00:00:00', strtotime($value->date)) , date('Y-m-d 23:59:59', strtotime($value->date))] )->count();
            $value->unfinished = $unfinished;
            $value->finished = $value->total - $value->unfinished;
        }

        return  response()->json($all);
    }

}
