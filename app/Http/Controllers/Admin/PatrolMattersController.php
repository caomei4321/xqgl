<?php

namespace App\Http\Controllers\Admin;

use App\Models\Patrol;
use App\Models\PatrolMatter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;

class PatrolMattersController extends Controller
{
    public function index(PatrolMatter $patrolMatter)
    {
        $patrolMatters = $patrolMatter->orderBy('created_at', 'desc')->paginate();

        return view('admin.patrolMatter.index', compact('patrolMatters'));
    }

    public function show(PatrolMatter $patrolMatter)
    {
        if ($patrolMatter->images) {
            $images = explode(';', $patrolMatter->images);
        } else {
            $images = $patrolMatter->images;
        }


        $host = env('APP_URL');

        return view('admin.patrolMatter.show', compact('patrolMatter', 'images', 'host'));
    }

    public function destroy(PatrolMatter $patrolMatter)
    {
        $patrolMatter->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }

    public function export(Request $request, PatrolMatter $patrolMatter, Excel $excel)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-01', strtotime(date("Y-m-d")));
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', time());


        $patrolMatters = $patrolMatter->whereDate('created_at', '>=', $startTime)
                                    ->whereDate('created_at', '<=', $endTime)
                                    ->get();
        $cellData = [];
        $firstRow = ['姓名', '标题', '问题描述', '处理意见', '处理时间'];
        foreach ($patrolMatters as $matter) {
            $data = [
                $matter->user->name,
                $matter->title,
                $matter->content,
                $matter->suggest,
                $matter->created_at
            ];
            array_push($cellData, $data);
        }
        $excel->create('巡查发现事件', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('matter', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    public function search(Request $request, PatrolMatter $patrolMatter)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-01', strtotime(date("Y-m-d")));
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', time());

        $filter['start_time'] = $startTime;
        $filter['end_time'] = $endTime;
        $patrolMatters = $patrolMatter->whereDate('created_at', '>=', $startTime)
            ->whereDate('created_at', '<=', $endTime)
            ->orderBy('id', 'desc')
            ->paginate();
        //dd($patrolMatters);
        return view('admin.patrolMatter.index', [
            'patrolMatters' => $patrolMatters,
            'filter' => $filter
        ]);
    }
}
