<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Patrol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class PatrolsController extends Controller
{
    public function index(Patrol $patrol)
    {
        $patrols = $patrol->paginate();
        return view('admin.patrol.index', compact('patrols'));
    }

    public function show(Patrol $patrol, Curl $curl)
    {
        $entity_name  = $patrol->entity_name;
        //   http://yingyan.baidu.com/api/v3/track/gettrack

        if ($patrol->end_at) {
            $end_at = strtotime($patrol->end_at);
        } else {
            // 没有点击结束，默认结束时间为第二天0点
            $date = date('Y-m-d',strtotime($patrol->created_at->toDateTimeString()));
            //dd($date);
            $end_at = strtotime($date) + 86400;
        }
        $tracksData = [
            'ak' => env('BAIDU_MAP_AK',''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => env('BAIDU_MAP_MCODE'),
            'entity_name' => $entity_name,
            'start_time' => strtotime($patrol->created_at),
            'end_time'  => $end_at,
            'is_processed' => 1,
            //'process_option' => 'need_denoise=1,radius_threshold=10,need_vacuate=1,need_mapmatch=1,transport_mode=walking'
        ];
        $tracks = $curl->curl('http://yingyan.baidu.com/api/v3/track/gettrack', $tracksData, false);

        $tracks = json_decode($tracks);

        $tracks->distance = isset($tracks->distance) ? substr($tracks->distance/1000, '0','4').'km' : 0 .'km';

        // 查询纠偏后的轨迹
        $processTracksData = [
            'ak' => env('BAIDU_MAP_AK',''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => env('BAIDU_MAP_MCODE'),
            'entity_name' => $entity_name,
            'start_time' => strtotime($patrol->created_at),
            'end_time'  => $end_at,
            'is_processed' => 1,
            'process_option' => 'need_denoise=1,radius_threshold=10,need_vacuate=1,need_mapmatch=1,transport_mode=walking'
        ];
        $processTracks = $curl->curl('http://yingyan.baidu.com/api/v3/track/gettrack', $processTracksData, false);

        $processTracks = json_decode($processTracks);


        $patrolMatters = $patrol->patrol_matter()->get();
        return view('admin.patrol.show', compact('patrol','patrolMatters',  'tracks', 'processTracks'));
    }

    public function destroy(Patrol $patrol)
    {
        $patrol->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }

    // 导出
    public function export(Request $request,Patrol $patrol, Excel $excel)
    {
        $timeStart = $request->timeStart ? "$request->timeStart 00:00:00" : '2019-01-01 00:00:00';
        $timeEnd = $request->timeEnd ? "$request->timeEnd 23:59:59" : date('Y-m-d H:i:s', time());
        $patrols = $patrol->whereBetween('created_at', [$timeStart, $timeEnd])->orderBy('user_id')->get();
        // 巡查总时长，总里程
        $total = Patrol::select(DB::raw('SUM(`distance`) as k'),DB::raw('SUM(UNIX_TIMESTAMP(`end_at`)) as e'), DB::raw('SUM(UNIX_TIMESTAMP(`created_at`)) as c') , 'user_id')->whereBetween('created_at', [$timeStart, $timeEnd])->groupBy('user_id')->orderBy('user_id')->get();
        $all = [];
        foreach ($total as $value) {
            $tt = [
                $value->user->name,
                '',
                '',
                '',
                floor(($value->e - $value->c) / 60),
                $value->k
            ];
            array_push($all, $tt);
        }
        // 巡查记录
        $cellData = [];
        $firstRow = ['姓名','发现问题数量','开始时间','结束时间', '时长(分钟)', '里程(KM)'];
        foreach ($patrols as $patrol) {
            $data = [
                $patrol->user->name,
                $patrol->patrol_matter()->count(),
                $patrol->created_at,
                $patrol->end_at,
                floor((strtotime($patrol->end_at) - strtotime($patrol->created_at))/60),
                $patrol->distance,
            ];
            if ((strtotime($patrol->end_at) - strtotime($patrol->created_at)) < 0 ) {
                $data['4'] = 0;
            }
            array_push($cellData, $data);
        }
        $cellData = array_merge($all, $cellData);
        // 排序
        $first_key = array_column($cellData,'0');
        array_multisort($first_key,SORT_DESC,$cellData);    // 对多个数组或多维数组进行排序

        $excel->create('巡查记录', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('matter', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
