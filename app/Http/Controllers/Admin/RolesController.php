<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    public function index(Role $role)
    {
        $roles = $role->all();
        return view('admin.role.index', compact('roles'));
    }

    public function create(Role $role, Permission $permission)
    {
        $permissions = $permission->all();
        return view('admin.role.create_and_edit', compact('role', 'permissions'));
    }

    public function store(Request $request, Role $role)
    {
        $role = $role->create(['name' => $request->name]);
        $role->syncPermissions($request->permission);
        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        //dd($role->permissions()->get());
        $rolePermissions = $role->permissions()->get();
        return view('admin.role.show', compact('role', 'rolePermissions'));
    }

    public function edit(Role $role, Permission $permission)
    {
        $permissions = $permission->all();
        $role_permission = $role->permissions()->get()->toArray();
        return view('admin.role.create_and_edit', compact('role', 'permissions', 'role_permission'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->only(['name']));
        $role->syncPermissions($request->permission);
        return redirect()->route('admin.roles.index');
    }

    public function destroy(Role $role)
    {
        if ($role->id == 1) { //ID 为 1 的角色为平台超级管理员
            return response()->json(['status' => 0, 'msg' => '请求错误']);
        }
        $role->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
}
