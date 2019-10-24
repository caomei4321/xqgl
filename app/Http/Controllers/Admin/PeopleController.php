<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Models\Matter;
use App\Models\Responsibility;
use App\Models\Situation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Handlers\JPushHandler;
use App\Models\Category;
use Maatwebsite\Excel\Excel;

class PeopleController extends Controller
{
    public function index(Matter $matter)
    {
        $matters = $matter->where('form', '3')->orderBy('allocate', 'asc')->paginate();

        return view('admin.matters.people', compact('matters'));
    }

    public function open(Request $request)
    {
        $data = $request->all();
        DB::table('matters')->where('id', $data['id'])->update($data);
        return redirect()->route('admin.people.index');
    }

    public function allocate(Request $request, Matter $matter, Responsibility $responsibility,  User $user)
    {
        $matterInfo =  $matter->find($request->id);
        $users = $user->all();
        $responsibility = $responsibility->all();
        return view('admin.matters.allocatewx', compact('matterInfo', 'users', 'responsibility'));
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
            return redirect()->route('admin.people.index');
        }

        return redirect()->route('admin.people.index');
    }


    public function edit(Request $request, Matter $matter)
    {
        $category = Category::all();
        $matter = $matter->find($request->id);
        return view('admin.matters.people_edit', compact('matter', 'category'));
    }

    public function update(Request $request, ImageUploadHandler $uploader)
    {
        $this->rules($request);
        $data = [
          'id' => $request->id,
          'title' => $request->title,
          'address' => $request->address,
          'content' => $request->content,
          'category_id' => $request->category_id
        ];
        if (!empty($request->image)){
            $result = $uploader->save($request->image, 'matters', 'mt');
            if ($result) {
                $data['image'] = $result['path'];
            }
        }
        DB::table('matters')->where('id', $data['id'])->update($data);
        return redirect()->route('admin.people.index');
    }

    public function rules(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|min:2',
            'address' => 'required|string|min:2',
            'content' => 'required|string|min:3',
            'image' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=200, min_height=200',
        ], [
            'title.required' => '标题不能为空',
            'title.min' => '标题至少两个字符',
            'address.required' => '地址不能为空',
            'address.min' => '地址至少两个字符',
            'content.required' => '内容不能为空',
            'content.min' => '内容至少三个字符',
            'image.mimes' => '必须是jpeg, bmp, png, gif格式的图片',
            'image.dimensions' => '图片清晰度不够， 宽和高需要 200px 以上'
        ]);
    }

    // 群众上报导出导出
    public function export(Request $request, Matter $matter, Excel $excel)
    {
        $timeStart = $request->timeStart ? "$request->timeStart 00:00:00" : '2019-01-01 00:00:00';
        $timeEnd = $request->timeEnd ? "$request->timeEnd 23:59:59" : date('Y-m-d H:i:s', time());
        $matters = $matter->where('form','3')->whereBetween('created_at', [$timeStart, $timeEnd])->get()->toArray();
        $cellData = [];
        $firstRow = ['标题','地址','问题描述', '创建时间'];
        foreach ($matters as $matter) {
            $data = [
                $matter['title'],
                $matter['address'],
                $matter['content'],
                $matter['created_at']
            ];
            array_push($cellData, $data);
        }
        $excel->create('群众上报清单', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('list', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    public function peopleSituation(Situation $situation)
    {
        $situations = Situation::with(['Matter', 'User', 'Category'])->whereDoesntHave('Matter', function ($query){
            $query->where('form', '<', '3');
        })->paginate();

        return view('admin.situation.people', compact('situations'));
    }
}
