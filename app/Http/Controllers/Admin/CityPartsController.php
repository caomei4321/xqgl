<?php

namespace App\Http\Controllers\Admin;

use App\Models\CityPart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityPartsController extends Controller
{
    public function index(CityPart $cityPart)
    {
        $cityParts = $cityPart->paginate(15);

        return view('admin.part.index', compact('cityParts'));
    }

    public function create(CityPart $cityPart)
    {
        return view('admin.part.create_and_edit', compact('cityPart'));
    }

    public function store(Request $request, CityPart $cityPart)
    {
        $this->rules($request);
        $cityPart->fill($request->all());
        $cityPart->save();

        return redirect()->route('admin.parts.index');
    }

    public function edit(CityPart $cityPart)
    {
        return view('admin.part.create_and_edit', compact('cityPart'));
    }

    public function update(Request $request, CityPart $cityPart)
    {
        $this->rules($request);
        $cityPart->update($request->all());

        return redirect()->route('admin.parts.index');
    }

    public function destroy(CityPart $cityPart)
    {
        $cityPart->delete();
        return response()->json(['status'=> '1', 'msg' => '删除成功']);
    }

    public function rules(Request $request)
    {
        $this->validate($request, [
            'things' => 'required|string',
            'num' => 'required|string|unique:city_parts',
            'info' => 'required|string|min:3'
        ], [
            'things.required' => '请输入物品',
            'num.required' => '请输入编号',
            'num.unique' => '编号重复，请修改',
            'info.required' => '请输入物品信息',
            'info.min' => '物品信息至少三个字符'
        ]);
    }
}
