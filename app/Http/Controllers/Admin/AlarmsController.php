<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alarm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

}
