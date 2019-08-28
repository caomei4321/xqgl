<?php

namespace App\Http\Controllers\Admin;

use App\Handler\Curl;
use App\Models\Part;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function store(Request $request, Part $part)
    {
        $this->rules($request, $part);
        $part->fill($request->all());
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
                Rule::unique('city_parts')->ignore($part->id)
            ],
            'info' => 'required|string|min:3',
        ], [
            'things.required' => '请输入物品',
            'num.required' => '请输入编号',
            'num.unique' => '编号重复，请修改',
            'info.required' => '请输入物品信息',
            'info.min' => '物品信息至少三个字符'
        ]);
    }

    public function grid()
    {
        return view('admin.parts.grid');
    }
}
