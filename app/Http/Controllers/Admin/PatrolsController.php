<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Patrol;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    public function export(Patrol $patrol, Excel $excel)
    {
        $patrols = $patrol->all();
        $cellData = [];
        $firstRow = ['姓名','发现问题数量','开始时间','结束时间', '时长(分钟)'];
        foreach ($patrols as $patrol) {

            //dd($patrol->patrol_matter);
            $data = [
                $patrol->user->name,
                $patrol->patrol_matter()->count(),
                $patrol->created_at,
                $patrol->end_at,
                floor((strtotime($patrol->end_at) - strtotime($patrol->created_at))/60)
            ];
            array_push($cellData, $data);
        }
        $excel->create('巡查记录', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('matter', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
