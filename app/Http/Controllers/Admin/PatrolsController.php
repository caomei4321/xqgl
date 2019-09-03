<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Patrol;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class PatrolsController extends Controller
{
    public function index(Patrol $patrol)
    {
        $patrols = $patrol->paginate();
        return view('admin.patrol.index', compact('patrols'));
    }

    public function show(Patrol $patrol, Curl $curl)
    {
        $entity_name  = $patrol->user->entity_name;
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
        ];
        $result = $curl->curl('http://yingyan.baidu.com/api/v3/track/gettrack', $tracksData, false);

        $tracks = json_decode($result);

        $tracks->distance = substr($tracks->distance/1000, '0','4').'km';

        //$result = $curl->curl('http://yingyan.baidu.com/api/v3/track/getdistance', $distanceData, false);

        //$distance = json_decode($result);
        //$points = $tracks->points;
        //dd($patrol->patrol_matter);
        //$distance->distance = substr($distance->distance/1000, '0','4').'km';
        return view('admin.patrol.show', compact('patrol', 'tracks'));
    }

    public function destroy(Patrol $patrol)
    {
        $patrol->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
}
