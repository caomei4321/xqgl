<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alarm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class AlarmsController extends Controller
{
    public function index()
    {
        $alarms = DB::table('alarms as a')
                ->leftJoin('parts as p', 'a.device_serial', '=', 'p.num')
                ->leftJoin('coordinates as c', 'p.coordinate_id', '=', 'c.id')
                ->select('a.id', 'a.alarm_id', 'a.channel_name', 'a.alarm_type', 'a.alarm_start', 'a.device_serial', 'a.alarm_pic_url', 'a.created_at', 'p.address', 'p.longitude', 'p.latitude', 'p.coordinate_id', 'c.number')
                ->paginate();
        return view('admin.alarm.index', compact('alarms'));
    }

    public function detail(Request $request, Alarm $alarm)
    {
        $alarms = DB::table('alarms as a')
            ->leftJoin('parts as p', 'a.device_serial', '=', 'p.num')
            ->leftJoin('coordinates as c', 'p.coordinate_id', '=', 'c.id')
            ->select('a.id', 'a.alarm_id', 'a.channel_name', 'a.alarm_type', 'a.alarm_start', 'a.device_serial', 'a.alarm_pic_url', 'a.created_at', 'p.address', 'p.longitude', 'p.latitude', 'p.coordinate_id', 'c.number')
            ->where('a.id', $request->id)
            ->first();
        return view('admin.alarm.show', compact('alarms'));
    }

    public function detailMap(Request $request, Alarm $alarm)
    {
        $alarms = DB::table('alarms as a')
            ->leftJoin('parts as p', 'a.device_serial', '=', 'p.num')
            ->leftJoin('coordinates as c', 'p.coordinate_id', '=', 'c.id')
            ->select('a.id', 'a.alarm_id', 'a.channel_name', 'a.alarm_type', 'a.alarm_start', 'a.device_serial', 'a.alarm_pic_url', 'a.created_at', 'p.address', 'p.longitude', 'p.latitude', 'p.coordinate_id', 'c.number')
            ->where('a.id', $request->id)
            ->first();
        return view('admin.alarm.show_map', compact('alarms'));
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
        $firstRow = ['设备序列号', '告警时间', '告警类型', '经度', '纬度', '网格'];
        foreach ($alarms as $alarm) {
            $data = [
                $alarm->device_serial,
                $alarm->alarm_start,
                $alarm->alarm_type,
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
