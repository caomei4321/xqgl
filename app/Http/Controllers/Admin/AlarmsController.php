<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Handlers\JPushHandler;
use App\Models\Alarm;
use App\Models\Category;
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

    public function edit(Request $request,Matter $matter, Situation $situation, User $user)
    {
        $category = Category::all();
        $user_id = $situation->where('matter_id', $request->id)->value('user_id');
        $users = $user->all();
        $matter = $matter->find($request->id);
        return view('admin.alarm.alarm_edit', compact('matter', 'category', 'users', 'user_id'));
    }

    public function update(Request $request, Matter $matter, Situation $situation, ImageUploadHandler $uploader)
    {
        $data = [
            'id' => $request->id,
            'title' => $request->title,
            'address' => $request->address,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'alarm_type' => $request->alarm_type,
            'alarm_start' => $request->alarm_start
        ];
        if (!empty($request->alarm_pic_url)){
            $result = $uploader->save($request->alarm_pic_url, 'matters', 'am');
            if ($result) {
                $data['alarm_pic_url'] = $result['path'];
            }
        }
        $situation->where('matter_id', $request->id)->update([
            'user_id' => $request->user_id
        ]);
        $matter->where('id', $request->id)->update($data);
        return redirect()->route('admin.alarm.index');
    }

    public function allocate(Request $request, Matter $matter, Responsibility $responsibility,  User $user)
    {
        $matterInfo =  $matter->find($request->id);
        $users = $user->all();
        $responsibility = $responsibility->all();
        return view('admin.alarm.alarm_allocate', compact('matterInfo', 'users', 'responsibility'));
    }

    public function allocates(Request $request,Matter $matter,Situation $situation,User $user, JPushHandler $JPushHandler)
    {
        $data = $request->only(['matter_id', 'user_id', 'category_id', 'responsibility_id']);
        // 截止日期
        $hour = Responsibility::where('id', $data['responsibility_id'])->value('deadline');
        $time = time() + $hour * 60 * 60;
        // 分配更新字段allocate=1
        $matter->where('id', $request->matter_id)->update([
            'allocate' => '1',
            'time_limit' => date('Y-m-d H:i:s', $time)
        ]);
        // 新增数据
        $situation->fill($data);
        $situation->save();
        // 获取用户 reg_id 推送消息
        $reg_id = $user->where('id', $request->user_id)->value('reg_id');
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
