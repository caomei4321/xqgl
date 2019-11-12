<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\JPushHandler;
use App\Models\Alarm;
use App\Models\Matter;
use App\Models\Responsibility;
use App\Models\Situation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class AlarmsController extends Controller
{
    public function index(Matter $matter)
    {
        $matters = $matter->where('form', '4')->orderBy('allocate', 'asc')->paginate();
        return view('admin.alarm.index', compact('matters'));
    }

    public function allocate(Request $request, Matter $matter, Responsibility $responsibility,  User $user)
    {
        $matterInfo =  $matter->find($request->id);
        $users = $user->all();
        $responsibility = $responsibility->all();
        return view('admin.alarm.alarm_allocate', compact('matterInfo', 'users', 'responsibility'));
    }

    public function allocates(Request $request, JPushHandler $JPushHandler)
    {
        $data = $request->only(['matter_id', 'user_id', 'category_id', 'responsibility_id']);
        $hour = Responsibility::where('id', $data['responsibility_id'])->value('deadline');
        $time = time() + $hour * 60 * 60;
        // 将matters表中数据allocate更新为1， 代表已分配
        $matters = [
            'id' => $data['matter_id'],
            'allocate' => '1',
            'time_limit' => date('Y-m-d H:i:s', $time)
        ];
        DB::table('matters')->where('id', $data['matter_id'])->update($matters);
        // 分配信息存入user_has_matters表中
        $allocate = [
            'matter_id' => $data['matter_id'],
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ];
        DB::table('user_has_matters')->insert($allocate);
        $reg_id = DB::table('users')->where('id', $data['user_id'])->value('reg_id');
        try {
            $JPushHandler->testJpush($reg_id);
        }catch (\Exception $exception) {
            return redirect()->route('admin.alarm.index');
        }

        return redirect()->route('admin.alarm.index');
    }


    public function alarmSituation(Situation $situation)
    {
        $situations = Situation::with(['Matter', 'User'])->whereDoesntHave('Matter', function ($query){
            $query->where('form', '!=', '4');
        })->paginate();
        return view('admin.alarm.alarm', compact('situations'));
    }



    public function export(Request $request, Excel $excel)
    {
        $timeStart = $request->timeStart ? "$request->timeStart 00:00:00" : '2019-01-01 00:00:00';
        $timeEnd = $request->timeEnd ? "$request->timeEnd 23:59:59" : date('Y-m-d H:i:s', time());
        $alarms = DB::table('alarms as a')
            ->leftJoin('parts as p', 'a.device_serial', '=', 'p.num')
            ->leftJoin('coordinates as c', 'p.coordinate_id', '=', 'c.id')
            ->select('a.id', 'a.alarm_id', 'a.channel_name', 'a.alarm_type', 'a.alarm_start', 'a.device_serial', 'a.alarm_pic_url', 'a.created_at', 'p.address', 'p.longitude', 'p.latitude', 'p.coordinate_id', 'c.number')
            ->whereBetween('a.alarm_start',[$timeStart, $timeEnd])
            ->get();
        $cellData = [];
        $firstRow = ['设备序列号', '告警时间', '告警类型','位置', '经度', '纬度', '网格'];
        foreach ($alarms as $alarm) {
            $data = [
                $alarm->device_serial,
                $alarm->alarm_start,
                $alarm->alarm_type,
                $alarm->address,
                $alarm->longitude,
                $alarm->latitude,
                $alarm->number. '号网格'
            ];
            array_push($cellData, $data);
        }
        $excel->create('智能告警事件报表统计', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('alarm', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

}
