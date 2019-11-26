<?php

namespace App\Http\Controllers\Api;

use App\Handlers\Curl;
use App\Http\Resources\Api\PatrolResource;
use Illuminate\Http\Request;

class PatrolController extends Controller
{
    public function startAndEndPatrol(Request $request, Curl $curl)
    {
        if ($request->start_time) {
            //return $this->user();
            $patrol = $this->user()->patrols()->create([
                'entity_name' => $this->user()->entity_name,
            ]);

            return $this->success([
                'id' =>  $patrol->id
            ]);
        } elseif ($request->end_time) {

            $patrol = $this->user()->patrols()->find($request->id);

            $tracksData = [
                'ak' => env('BAIDU_MAP_AK',''),
                'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
                'mcode'     => env('BAIDU_MAP_MCODE'),
                'entity_name' => $patrol->entity_name,
                'start_time' => strtotime($patrol->created_at),
                'end_time'  => strtotime($request->end_time),
                'is_processed' => 1,
                //'process_option' => 'need_denoise=1,radius_threshold=10,need_vacuate=1,need_mapmatch=1,transport_mode=walking'
            ];

            $tracks = $curl->curl('http://yingyan.baidu.com/api/v3/track/gettrack', $tracksData, false);

            $tracks = json_decode($tracks);

            $distance = isset($tracks->distance) ? substr($tracks->distance/1000, '0','4') : 0.00;  //巡查距离

            $patrol->distance = $distance;
            $patrol->end_at = $request->end_time;
            $patrol->save();

            return $this->success('结束成功');
        }
    }

    public function patrolList()
    {
        $patrols = $this->user()->patrols()->whereDate('created_at',date('Y-m-d',time()))->get();

        return response()->json(['data' => $patrols]);
    }
}
