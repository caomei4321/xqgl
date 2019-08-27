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
use Maatwebsite\Excel\Excel;

class MattersController extends Controller
{
    public function index(Matter $matter, Request $request)
    {
        $matters = $matter->orderBy('status', 'asc')->paginate(10);

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

    public function export(Matter $matter, Excel $excel)
    {
        $matters = $matter->all()->toArray();
        $cellData = [
            ['受理员编号','办结时限','工单编号','紧急程度','来电类别','信息来源','是否回复','是否保密','联系人','联系电话','回复备注','问题分类','特办意见','领导批示','办理结果','标题', '地址', '内容', '创建时间']
        ];
        foreach ($matters as $matter) {
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
        $excel->create(iconv('UTF-8', 'GBK', '任务清单'), function ($excel) use ($cellData) {
            $excel->sheet('matter', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    public function import(Request $request, Excel $excel)
    {
        $filePath = $this->uploadFile($request->import_file, 'import', 'im');
        $path = $filePath['path'];
        $excel->load($path, function ($reader) {
            dd($reader);
            //            $reader->noHeading()
            //            $reader = $reader->getSheet(0);
            //            $result = $reader->toArray();
            //            unset($result[0]);
            $data = $reader->all();
            dd($data);
            $value = [];
            $count = '';
            foreach ($data as $k => $v) {
                $count++;
                $value['accept_num'] = $v['0'];
            }

            throw  new \Exception("成功导入了".$count."条数据");
        });
    }

    public function uploadFile($file, $folder, $file_prefix)
    {
        $folder_name = "uploads/file/$folder/" . date("Ym/d", time());

        $upload_path = public_path() . '/' . $folder_name;

        $extension = strtolower($file->getClientOriginalExtension()) ?: 'xls';

        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        if ( ! in_array($extension, ['xls', 'xlsx'])) {
            return false;
        }

        $file->move($upload_path, $filename);

        return [
            'path' => "$folder_name/$filename"
        ];
    }
}
