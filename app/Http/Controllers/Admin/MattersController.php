<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Models\Matter;
use App\Models\Situation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MattersController extends Controller
{
    public function index(Matter $matter, Request $request)
    {
        $matters = $matter->orderBy('status', 'asc')->paginate(10);

        return view('admin.matters.index', compact('matters'));
    }

    public function create(Matter $matter)
    {
        return view('admin.matters.create_and_edit', compact('matter'));
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
        return view('admin.matters.create_and_edit', compact('matter'));
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
}
