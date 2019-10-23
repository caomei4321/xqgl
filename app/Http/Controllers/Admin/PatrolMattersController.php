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
        $images = explode(';', $patrolMatter->images);

        $host = env('APP_URL');

        return view('admin.patrolMatter.show', compact('patrolMatter', 'images', 'host'));
    }

    public function destroy(PatrolMatter $patrolMatter)
    {
        $patrolMatter->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }

    public function export(PatrolMatter $patrolMatter, Excel $excel)
    {
        $patrolMatters = $patrolMatter->all();
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
}
