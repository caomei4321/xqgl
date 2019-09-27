<?php

namespace App\Http\Controllers\Admin;

use App\Models\Patrol;
use App\Models\Situation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CountsController extends Controller
{
    public function index(Situation  $situation)
    {
        $situations = Situation::with(['Matter', 'User'])->where('status', '>', '0')->whereBetween('updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])->paginate(10);
        $all = count($situation->whereBetween('updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])->get());
        return view('admin.count.index', compact('situations', 'all'));
    }

    // 任务量
    public function allMatters()
    {
        $all = DB::table('user_has_matters')->count();
        $unfinish = DB::table('user_has_matters')->where('status', '0')->count();
        $data = [
            'all' => $all,
            'unfinished' => $unfinish,
            'finished' => $all - $unfinish
        ];
        return response()->json($data);
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


    // 每个人的任务量
    public function everyUserDay()
    {
        $all = DB::table('user_has_matters')
            ->join('users', 'users.id', '=', 'user_has_matters.user_id')
            ->select('user_id', 'users.name', DB::raw('GROUP_CONCAT(matter_id) as total'))
            ->groupBy('user_id',  'users.name')->get()->map(function ($value) {
                return (array)$value;
            });
        foreach ($all as $v) {
            $v['total'] =  count(explode( ',', $v['total']));
        }

        $unfinished = DB::table('user_has_matters')
            ->join('users', 'users.id', '=', 'user_has_matters.user_id')
            ->where('status', '0')
            ->select('user_id', 'users.name', DB::raw('GROUP_CONCAT(matter_id) as un'))
            ->groupBy('user_id', 'users.name')->get()->map(function ($value) {
                return (array)$value;
            });
        foreach ($unfinished as $v) {
            $v['un'] =  count(explode( ',', $v['un']));
        }

        $arr = array();
        foreach($all as $k=>$v){
            $arr[] = array_merge($v,$unfinished[$k]);
        }

        $ret = [];
        foreach ($arr as $v) {
            $v['name'] = $v['name'];
            $v['total'] = count(explode( ',', $v['total']));
            $v['un'] =  count(explode( ',', $v['un']));
            $v['on'] = $v['total'] - $v['un'];
            array_push($ret, $v);
        }

        return response()->json($ret);
    }

    // 一个月内每日的任务量、完成量、未处理量
    public function mouthToDay()
    {
        $date = date('Y-m-d H:i:s', time());
        $firstday = date("Y-m-01 00:00:00",strtotime($date));
        $lastday = date("Y-m-d 23:59:59",strtotime("$firstday +1 month -1 day"));
        $list = DB::table('matters')->whereBetween('created_at', [$firstday, $lastday])->select('created_at')->get();

        $sql = "SELECT DATE_FORMAT(lefttable.date,'%Y-%c') as yearMonth,lefttable.date, righttable.a FROM 
        (SELECT ADDDATE(y.first, x.d - 1) as date
FROM
(SELECT 1 AS d UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL
SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL
SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL
SELECT 29 UNION ALL SELECT 30 UNION ALL SELECT 31) x,
(SELECT CONCAT('2019-9','-01') as FIRST, DAY(LAST_DAY(str_to_date('2019-9','%Y-%c'))) AS last) y
WHERE x.d <= y.last and ADDDATE(y.first, x.d - 1)<=CURDATE()) as lefttable
LEFT JOIN
(select count(status) as a, created_at from matters where status = 0 ) as righttable ON DATE_FORMAT(lefttable.date,'%Y-%m-%d')=DATE_FORMAT(righttable.created_at, '%Y-%m-%d')";
        dd(DB::select($sql));


    }

}
