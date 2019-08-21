<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Models\Matter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MattersController extends Controller
{
    public function index(Matter $matter)
    {
        $matters = $matter->paginate(15);

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
