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
}
