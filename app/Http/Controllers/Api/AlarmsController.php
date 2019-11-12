<?php

namespace App\Http\Controllers\Api;

use App\Handlers\JPushHandler;
use App\Models\Alarm;
use App\Models\Matter;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlarmsController extends Controller
{
    // 接收告警信息， 推送任务到该设备区域所有人
    public function alarm(Request $request, Matter $matter, Part $part)
    {
        if ($request->alarmType === 'leftdetection') {
            $content = '物品遗留';
        }elseif ($request->alarmType === 'enterareadetection') {
            $content = '进入区域';
        }else{
            $content = $request->alarmType;
        }
        $data = [
            'title' => '告警提示',
            'alarm_id' => $request->alarmId,
            'channel_name' => $request->channelName,
            'alarm_type' => $request->alarmType,
            'alarm_start' => $request->alarmStart,
            'device_serial' => $request->deviceSerial,
            'alarm_pic_url' => $request->alarmPicUrl,
            'address' => $part->where('num', $request->deviceSerial)->value('address'),
            'content' => $request->alarmStart .';'. $content,
            'form' => '4',
        ];
        $matter->fill($data);
        $matter->save();

        return response()->json(['status' => '1', 'msg' => 'success']);

//        DB::beginTransaction();
//        $alarm->fill($data);
//        $alarm->save();
//
//        // 报警信息
//        $alarm = $alarm->where('alarm_id', $request->alarmId)->first();
////        dd($alarm);
//        // 网格
//        $wangge = DB::table('parts') ->where('num', $alarm->device_serial)->first();
//        // 根据网格查找该网格所有的人
//        $users = DB::table('users')->where('responsible_area', $wangge->coordinate_id)->get();
//        $info = [];
//        $time = date('Y-m-d H:i:s', time());
//        foreach ($users as $value){
//            $array = [
//                'user_id' => $value->id,
//                'alarm_id' => $alarm->id,
//                'created_at' => $time,
//                'updated_at' => $time
//            ];
//            array_push($info, $array);
//        }
//        $ret = DB::table('alarm_users')->insert($info);
//        // 推送
////        foreach ($users as $value) {
////            $JPushHandler->testJpush($value->reg_id);
////        }
//        if ($ret) {
//            DB::commit();
//            return response()->json(['status' => '1', 'msg' => '上传成功']);
//        } else {
//            DB::rollBack();
//            return response()->json(['status' => '1', 'msg' => '上传成功']);
//        }

    }

    // 分配告警任务到人员
    public function userHasAlarms()
    {

        $data = $this->user()->alarm()->where('alarm_users.status', '0')->orderBy('created_at', 'desc')->get();

        return response()->json($data);
    }

    // 完成告警任务
    public function completeAlarm(Request $request, Alarm $alarm)
    {
        $path = Storage::disk('public')->putfile('seeImg', $request->file('see_image'));
        $url = Storage::url($path);

        $data = [
          'see_image' => $url,
          'information' => $request->information,
          'status' => 1,
          'user_id' => $this->user()->id
        ];
        DB::table('alarm_users')->where('alarm_id', $request->id)->update($data);
//        $res = $alarm->update($data);
        return $this->response()->accepted('',['msg' => '维修完成']);
    }
}
