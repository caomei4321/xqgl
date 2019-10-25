<?php

namespace App\Http\Controllers\Admin;

use App\Models\Matter;
use App\Models\PatrolMatter;
use Carbon\Carbon;
use App\Handlers\Curl;
use App\Models\Patrol;
use App\Models\Situation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class CountsController extends Controller
{
    public function index(Situation  $situation, Patrol $patrol)
    {
        // 在巡查人数
        $userNum = $this->allUserPatrol($patrol);
        $userAll = count(User::all());

        // 12345任务
        $numMatter = $this->numMatter();

        // 群众举报
        $people = $this->people();

        return view('admin.count.index', compact('userNum', 'userAll', 'numMatter', 'people'));
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
        $numall = DB::table('user_has_matters')
            ->join('matters', 'user_has_matters.matter_id', '=', 'matters.id')
            ->where('matters.form','<' ,'3')
            ->whereBetween('user_has_matters.updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])
            ->count();
        $data = [
            'numfinished' => $unfinished,
            'numall' => $numall
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

    // 导出报表
    public function export(Request $request, Matter $matter, PatrolMatter $patrolMatter, Patrol $patrol, Excel $excel)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-01',strtotime(date("Y-m-d")));
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', time());

        // 小程序每日上报问题数量
        $programMattersCount = $matter
                                     ->where('form',3)
                                     ->whereBetween('created_at',[$startTime,$endTime])
                                     //->whereDate('created_at', '>=', $startTime)
                                     //->whereDate('created_at', '<=', $endTime)
                                     ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                     ->groupBy('date')
                                     ->get()->toArray();
        // 巡查每日发现问题数量
        $patrolMattersCount = $patrolMatter->whereBetween('created_at',[$startTime,$endTime])
                                            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                            ->groupBy('date')
                                            ->get()->toArray();

        // 每日巡查人数
        $patrolUserCount = $patrol->whereBetween('created_at',[$startTime,$endTime])
                                    //->selectRaw('')
                                    ->selectRaw('DATE(created_at) as date,  COUNT(distinct user_id) as count')
                                    ->groupBy('date')
                                    ->get()->toArray();

        $firstRow = ['日期', '群众上报问题数量', '每日巡查发现问题', '每日巡查人数'];

        // excel 数据
        $cellData = [];

        // 按日期循环
        for ($i = strtotime($startTime); $i <= strtotime($endTime);  $i += 86400) {

            $date = date('Y-m-d',$i);

            if ($key = array_search($date,array_column($programMattersCount,'date'))) {  // 二维数组查找
                $programMatters = $programMattersCount[$key]['count'];
            } else {
                $programMatters = 0;
            }

            if ($key = array_search($date,array_column($patrolMattersCount,'date'))) {
                $patrolMatters = $patrolMattersCount[$key]['count'];
            } else {
                $patrolMatters = 0;
            }

            if ($key = array_search($date,array_column($patrolUserCount,'date'))) {
                $patrolUser = $patrolUserCount[$key]['count'];
            } else {
                $patrolUser = 0;
            }

            $data = [
                $date,
                $programMatters,
                $patrolMatters,
                $patrolUser
            ];

            array_push($cellData, $data);
        }

        $excel->create('数据统计', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('matter', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
