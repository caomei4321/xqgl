<?php

namespace App\Http\Controllers\Api;

use App\Models\Hat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HatsController extends Controller
{
    public function helmetAlarm(Request $request, Hat $hat)
    {
        $data = [
            'alarm_info' => json_encode($request->data),
            'device_serial' => $request->deviceId,
            'sum' => $request->sum,
            'alarm_time' => $request->alarmTime,
            'alarm_img_url' => $request->url,
        ];

        $hat->fill($data);

        $hat->save();

        return response()->json(['status'=>'1', 'msg' => 'success']);

    }
}
