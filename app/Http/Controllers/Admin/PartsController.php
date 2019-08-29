<?php

namespace App\Http\Controllers\Admin;

use App\Handler\Curl;
use App\Handlers\ImageUploadHandler;
use App\Models\Part;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PartsController extends Controller
{
    public function index(Part $part)
    {
        $parts = $part->paginate(15);

        return view('admin.parts.index', compact('parts'));
    }

    public function create(Part $part)
    {
        return view('admin.parts.create_and_edit', compact('part'));
    }

    public function store(Request $request, Part $part, ImageUploadHandler $uploader)
    {
        $this->rules($request, $part);
        $data = $request->all();
        if (!empty($request->image)){
            $result = $uploader->save($request->image, 'parts', 'pt');
            if ($result) {
                $data['image'] = $result['path'];
            }
        }
//        经纬度 120.61990712,31.31798737
        $part->fill($data);
        $part->save();

        return redirect()->route('admin.part.index');
    }

    public function edit(Part $part)
    {
        return view('admin.parts.create_and_edit', compact('part'));
    }

    public function update(Request $request, Part $part)
    {
        $this->rules($request, $part);
        $part->update($request->all());

        return redirect()->route('admin.part.index');
    }

    public function destroy(Part $part)
    {
        $part->delete();

        return response()->json(['status' => '1', 'msg' => '删除成功']);
    }

    public function rules(Request $request, Part $part)
    {
        $this->validate($request, [
            'things' => 'required|string',
            'num' => [
                'required',
                'string',
                Rule::unique('parts')->ignore($part->id)
            ],
            'address' => 'string|min:2',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'info' => 'required|string|min:3',
        ], [
            'things.required' => '请输入物品',
            'address.min' => '地址至少两个字符',
            'longitude.required' => '请输入经度',
            'longitude.numeric' => '经度格式错误',
            'latitude.required' => '请输入维度',
            'latitude.numeric' => '纬度格式错误',
            'num.required' => '请输入编号',
            'num.unique' => '编号重复，请修改',
            'info.required' => '请输入物品信息',
            'info.min' => '物品信息至少三个字符'
        ]);
    }

    // 地图标注
    public function mapInfo()
    {
        $parts = Part::all();
        return view('admin.parts.map_info', compact('parts'));
    }
}
