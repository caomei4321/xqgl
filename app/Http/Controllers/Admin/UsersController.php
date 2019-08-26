<?php

namespace App\Http\Controllers\Admin;

use App\Handler\Curl;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(User $user)
    {
        $users = $user->paginate(15);

        return view('admin.user.index', compact('users'));
    }

    public function create(User $user, Curl $curl)
    {
        $data = [
            'ak' => env('BAIDU_MAP_AK', ''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', '')
        ];
        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list', $data);
        $entityList = json_decode($entityList);
        $entities = $entityList->entities;
        return view('admin.user.create_and_edit', compact('user',  'entities'));
    }


    public function store(Request $request, User $user)
    {
        $data = $request->only(['name', 'phone', 'password', 'age', 'position', 'responsible_area', 'resident_institution', 'entity_name']);
        $data['password'] = Hash::make($data['password']);
        $user->fill($data);
        $user->save();

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }


    public function edit(User $user, Curl $curl)
    {
        $data = [
            'ak' => env('BAIDU_MAP_AK', ''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', '')
        ];
        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list', $data);
        $entityList = json_decode($entityList);
        $entities = $entityList->entities;
        return view('admin.user.create_and_edit', compact('user', 'entities'));
    }

    public function update(Request $request, User $user)
    {

        if (Hash::check($request->password, $user->password)) {
            $user->update($request->only(['phone', 'name', 'age', 'position', 'responsible_area', 'resident_institution', 'entity_name']));
        } else {
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'age' => $request->age,
                'position' => $request->position,
                'responsible_area' => $request->responsible_area,
                'resident_institution' => $request->resident_institution,
                'entity_name' => $request->entity_name
            ]);
        }
        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }

    public function address(Curl $curl)
    {
        $data = [
            'ak' => env('BAIDU_MAP_AK', ''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', '')
        ];
        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list', $data);
        $entityList = json_decode($entityList);
        $entities = $entityList->entities;

        return view('admin.user.address', compact('entities'));
    }
}
