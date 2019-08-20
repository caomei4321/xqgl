<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoriesRequest;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Category $category)
    {
        $categories = $category->paginate();

        return view('admin.category.index', compact('categories'));
    }

    public function create(Category $category)
    {
        return view('admin.category.create_and_edit', compact('category'));
    }

    public function store(Request $request, Category $category)
    {
        $this->rules($request);
        $category->fill($request->all());
        $category->save();

        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        return view('admin.category.create_and_edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if (!$category->getOriginal('name')){
            $this->rules($request);
        }
        $category->update($request->all());

        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['status' => 1, 'msg' => '删除成功' ]);
    }

    public function rules(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories|string|min:2',
            'description' => 'string|nullable'
        ], [
            'name.required' => '分类名不能为空',
            'name.unique' => '分类名重复，请重新输入',
            'name.min' => '分类名不能少于两个字符'
        ]);
    }
}
