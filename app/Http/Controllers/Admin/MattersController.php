<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Matter;
use App\Models\Situation;
use App\Models\User;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use App\Handlers\JPushHandler;

class MattersController extends Controller
{
    protected  $table = "user_ has_matters";

    public function index(Matter $matter, Request $request)
    {
        $matters = $matter->orderBy('allocate', 'asc')->paginate();

        return view('admin.matters.index', compact('matters'));
    }

    public function create(Matter $matter)
    {
        $category = Category::all();
        return view('admin.matters.create_and_edit', compact('matter', 'category'));
    }

    public function store(Request $request, Matter $matter, ImageUploadHandler $uploader)
    {
        $this->rules($request);
        $data = $request->all();
        if (!empty($request->image)){
            $result = $uploader->save($request->image, 'matters', 'mt');
            if ($result) {
                $data['image'] = $result['path'];
            }
        }
        $matter->fill($data);
        $matter->save();

        return redirect()->route('admin.matters.index');
    }

    public function edit(Matter $matter)
    {
        $category = Category::all();
        return view('admin.matters.create_and_edit', compact('matter', 'category'));
    }

    public function update(Request $request, Matter $matter, ImageUploadHandler $uploader)
    {
        $this->rules($request);
        $data = $request->all();
        if (!empty($request->image)){
            $result = $uploader->save($request->image, 'matters', 'mt');
            if ($result) {
                $data['image'] = $result['path'];
            }
        }
        $matter->update($data);

        return redirect()->route('admin.matters.index');
    }

    public function destroy(Matter $matter)
    {
        $matter->delete();

        return response()->json(['status' => '1', 'msg'=> '删除成功']);
    }

    public function getUser(User $user)
    {
        $users = $user->get();
        return response()->json($users);
    }

    // 分配到人 点击操作按钮每次单个分配
    public function allocate(Request $request, Matter $matter, User $user)
    {
        $matterInfo =  $matter->find($request->id);
        $users = $user->all();
        return view('admin.matters.allocate', compact('matterInfo', 'users'));
    }

    public function allocates(Request $request, JPushHandler $JPushHandler)
    {
        $data = $request->only(['matter_id', 'user_id', 'category_id']);
        // 将matters表中数据allocate更新为1， 代表已分配
        $matters = [
            'id' => $data['matter_id'],
            'allocate' => '1'
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
            return redirect()->route('admin.matters.index');
        }

        return redirect()->route('admin.matters.index');
    }

    // （已废弃） 一次多个分配到人逻辑， 页面上隐藏了分配到人按钮
    public function mattersToUser(Request $request, Situation $situation, Matter $matter)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'matter_id' => 'required'
        ], [
            'matter_id.required' => '没选中任务， 请选中任务再分配',
            'user_id.required' => '分配请选择人员',
        ]);

        $data = $request->only(['user_id', 'matter_id']);
        $mt_id = explode(',', $data['matter_id']);
        $newArr = [];
        foreach ($mt_id as $item) {
            $newArr[] = [
                'user_id' => $data['user_id'],
                'matter_id' => $item,
                'category_id' => $matter->where('id', $item)->value('category_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ];
        }

        foreach ($mt_id as $item) {
            $allocate = [
                'allocate' => 1,
            ];
            $matter->where('id', $item)->update($allocate);
        }
        DB::table('user_has_matters')->insert($newArr);

        return redirect()->route('admin.matters.index');

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

    // 导出
    public function export(Matter $matter, Excel $excel)
    {
        $matters = $matter->all()->toArray();
        $cellData = [];
        $firstRow = ['受理员编号','办结时限','工单编号','紧急程度','来电类别','信息来源','是否回复','是否保密','联系人','联系电话','回复备注','问题分类','特办意见','领导批示','办理结果','标题', '地址', '内容', '创建时间'];
        foreach ($matters as $matter) {

            if ($matter['is_reply'] == 1) {
                 $matter['is_reply'] = '是';
            } else $matter['is_reply'] = '否';

            if ($matter['is_secret'] == 1) {
                $matter['is_secret'] = '是';
            } else $matter['is_secret'] = '否';

            $category = Category::find($matter['category_id']);

            $matter['category_id'] = isset($category->name) ? $category->name : '';

            $data = [
                $matter['accept_num'],
                $matter['time_limit'],
                $matter['work_num'],
                $matter['level'],
                $matter['type'],
                $matter['source'],
                $matter['is_reply'],
                $matter['is_secret'],
                $matter['contact_name'],
                $matter['contact_phone'],
                $matter['reply_remark'],
                $matter['category_id'],
                $matter['suggestion'],
                $matter['approval'],
                $matter['result'],
                $matter['title'],
                $matter['address'],
                $matter['content'],
                $matter['created_at']
            ];
            array_push($cellData, $data);
        }
        $excel->create('事件记录', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('matter', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->store('xls')->export('xls');
    }
    // 导入
    public function import(Request $request, Excel $excel)
    {
        $filePath = $this->uploadFile($request->import_file);
        $path = $filePath['path'];
        iconv('UTF-8', 'GBK', $path);
        try {
            $excel->load($path, function ($reader) {
                $reader->noHeading();
                $reader = $reader->getSheet(0);
                $result = $reader->toArray();
//                unset($result[0]);    // 删除表头
                $excelData = [
                    'title' => $result[0][0],
                    'accept_num' => intval($result[3][1]),
                    'time_limit' => $result[3][3],
                    'work_num' => intval($result[4][1]),
                    'level' => intval($result[4][3]),
                    'type' => intval($result[5][1]),
                    'source' => $result[5][3],
                    'is_reply' => $result[6][1],
                    'is_secret' => $result[6][3],
                    'contact_name' => $result[7][1],
                    'contact_phone' => number_format($result[7][3],0,'',''),
                    'address' => $result[8][1],
                    'reply_remark' => $result[9][1],
                    'category_id' => intval($result[10][1]),
                    'content' => $result[11][1],
                    'suggestion' => $result[12][1],
                    'approval' => $result[13][1],
                    'result' => $result[14][1],
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),

                ];
                DB::table('matters')->insert($excelData);
//                $allDate = [];
//                $value = [];
//                $count = '';
//                foreach ($result as $k => $v) {
//                    $count++;
//                    $value['accept_num'] = $v['0'];
//                    $value['time_limit'] = $v['1'];
//                    $value['work_num'] = $v['2'];
//                    $value['level'] = $v['3'];
//                    $value['type'] = $v['4'];
//                    $value['source'] = $v['5'];
//                    $value['is_reply'] = $v['6'];
//                    $value['is_secret'] = $v['7'];
//                    $value['contact_name'] = $v['8'];
//                    $value['contact_phone'] = $v['9'];
//                    $value['reply_remark'] = $v['10'];
//                    $value['category_id'] = $v['11'];
//                    $value['suggestion'] = $v['12'];
//                    $value['approval'] = $v['13'];
//                    $value['result'] = $v['14'];
//                    $value['title'] = $v['15'];
//                    $value['address'] = $v['16'];
//                    $value['content'] = $v['17'];
//                    $value['created_at'] = date('Y-m-d H:i:s', time());
//                    $value['updated_at']= date('Y-m-d H:i:s', time());
//                    if ($value['title'] && $value['address'] && $value['content']) {
//                        array_push($allDate, $value);
//                    }
//                }
//                DB::table('matters')->insert($allDate);
            });
        }catch (\Exception $e) {
            return redirect()->route('admin.matters.index')->withErrors('导入失败，请选择正确的文件和按正确的文件填写方式导入');
        }
        return redirect()->route('admin.matters.index')->withErrors('导入成功');
    }
    // 导入上传文件
    public function uploadFile($file)
    {
        $folder_name = "uploads/import/" . date("Ym/d", time());

        $upload_path = public_path() . '/' . $folder_name;

        $extension = strtolower($file->getClientOriginalExtension()) ?: 'xls';

        $filename =   time() . '_' . str_random(10) . '.' . $extension;

        if ( ! in_array($extension, ['xls', 'xlsl'])) {
            return false;
        }

        $file->move($upload_path, $filename);

        return [
            'path' => "$folder_name/$filename"
        ];
    }

    // 导入下载模板
    public function download()
    {
        $filePath = 'excel/excel.xls';
        return response()->download($filePath, 'Excel导入模板');
    }

    // 鼠标绘制点线面
    public function mouse()
    {
        return view('admin.matters.mouse');
    }

    public function ajaxData(Request $request)
    {
        $data = $request->all();
        return response()->json(['status' => 1, 'msg' => $data]);
    }
    
}
