<?php

namespace App\Http\Controllers\Admin;

//use App\Models\Station;
//use App\Observers\AdministratorObservers;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Http\Requests\AdministratorRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
{
    public function index(Admin $administrator)
    {
        $administrators = $administrator->all();
        return view('admin.admin.index', compact('administrators'));
    }

    public function create(Admin $administrator, Role $role)
    {
        $roles = $role->all();
        //dd($stations);
        return view('admin.admin.create_and_edit', compact('administrator', 'roles'));
    }

    public function store(Request $request, Admin $administrator)
    {
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ];
        $administrator = $administrator->create($data);

        $administrator->syncRoles($request->administrator_roles);
        return redirect()->route('admin.admin.index');
    }


    public function show(Admin $administrator)
    {
        //dd($administrator->getRoleNames());
        //return view('admin.administrators.show', compact('administrator'));
    }


    public function edit(Admin $administrator, Role $role)
    {
        $roles = $role->all();
        $administrator_roles = $administrator->getRoleNames()->toArray();
        //dd($administrator_roles);
        return view('admin.admin.create_and_edit', compact('administrator', 'roles', 'administrator_roles'));
    }


    public function update(Request $request, Admin $administrator)
    {
        if (Hash::check($request->password,$administrator->password)) {
            $administrator->update($request->only(['name', 'phone']));
        } else {
            $administrator->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);
        }
        $administrator->syncRoles($request->administrator_roles);
        return redirect()->route('admin.admin.index');
    }

    public function destroy(Admin $administrator)
    {
        $administrator->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
}
