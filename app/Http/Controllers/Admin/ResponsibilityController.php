<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Responsibility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResponsibilityController extends Controller
{
    public function index()
    {
        $responsibility = Responsibility::with('category')->paginate();
        return view('admin.responsibility.index', compact('responsibility'));
    }

    public function create(Responsibility $responsibility)
    {
        $category = Category::all();
        return view('admin.responsibility.create_and_edit', compact('responsibility', 'category'));
    }

    public function store(Request $request, Responsibility $responsibility)
    {
        $this->rules($request);
        $data = $request->all();
        if ($data['subject_duty'] == 0) {
            $data['cooperate_duty'] = 1;
        } else {
            $data['cooperate_duty'] = 0;
        }
        $responsibility->fill($data);
        $responsibility->save();

        return redirect()->route('admin.responsibility.index');
    }

    public function edit(Responsibility $responsibility)
    {
        $category = Category::all();
        return view('admin.responsibility.create_and_edit', compact('responsibility', 'category'));
    }

    public function update(Request $request, Responsibility $responsibility)
    {
        $this->rules($request);
        $data = $request->all();
        if ($data['subject_duty'] == 0) {
            $data['cooperate_duty'] = 1;
        } else {
            $data['cooperate_duty'] = 0;
        }
        $responsibility->update($data);

        return redirect()->route('admin.responsibility.index');
    }

    public function destroy(Responsibility $responsibility)
    {
        $responsibility->delete();

        return response()->json(['status' => '1', 'msg' => '删除成功']);
    }

    public function rules(Request $request)
    {
        $this->validate($request, [
            'item' => 'required|string|min:2',
            'county' => 'required|string|min:3',
            'town' => 'required|string|min:3',
            'legal_doc' => 'required|string',
        ], [
            'item.required' => '请输入具体事项',
            'item.min' => '具体事项最少2个字符',
            'county.required' => '请输入县级部门职责',
            'county.min' => '县级部门职责最少3个字符',
            'town.required' => '请输入乡镇街道职责',
            'town.min' => '乡镇街道职责最少3个字符',
            'legal_doc' => '请输入法律法规及文件依据',
        ]);
    }
}
