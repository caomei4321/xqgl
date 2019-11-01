<?php

namespace App\Http\Controllers\Api;

use App\Models\Hat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HatsController extends Controller
{
    public function helmetAlarm(Request $request, Hat $hat)
    {
        $data = [
            'alarm_info' => json_encode($request->data),
            'device_serial' => $request->deviceId,
            'sum' => $request->sum,
            'alarm_time' => $request->alarmTime,
        ];
        $image = base64_decode($request->url);
        $imgname = 'ht' . '_' . time() . '_' . str_random(10) . '.jpg';
        $path = Storage::disk('public')->put($imgname, $image);
        $data['alarm_img_url'] = '/storage/' . $path;

        $hat->fill($data);
        $hat->save();

        return response()->json(['status'=>'1', 'msg' => 'success']);

    }
}
