<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Coordinate;
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

    public function create(User $user, Curl $curl, Coordinate $coordinate)
    {
        $data = [
            'ak' => env('BAIDU_MAP_AK', ''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => (string)env('BAIDU_MAP_MCODE')
        ];
        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list', $data);
        $entityList = json_decode($entityList);
        $entities = $entityList->entities;

        //dd(array_column($entities,'entity_name'));
        $userHasEntities = $user->all()->pluck('entity_name')->toArray();
        //dd($userHasEntities);
        //dd(array_diff_assoc($userHasEntities,array_column($entities,'entity_name')));
        // 未分配用户的设备
        $entities = array_diff(array_column($entities,'entity_name'),$userHasEntities);

        $coordinates = Coordinate::all();
        return view('admin.user.create_and_edit', compact('user', 'entities', 'coordinates'));
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


    public function edit(User $user, Curl $curl, Coordinate $coordinate)
    {
        $data = [
            'ak' => env('BAIDU_MAP_AK', ''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => (string)env('BAIDU_MAP_MCODE')
        ];
        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list', $data);
        $entityList = json_decode($entityList);
        $entities = $entityList->entities;

        $userHasEntities = $user->all()->pluck('entity_name')->toArray();
        //dd($userHasEntities);
        //dd(array_diff_assoc($userHasEntities,array_column($entities,'entity_name')));
        // 未分配用户的设备
        $entities = array_diff(array_column($entities,'entity_name'),$userHasEntities);
        $coordinates = Coordinate::all();
        return view('admin.user.create_and_edit', compact('user', 'entities', 'coordinates'));
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

    public function address(Curl $curl, User $user)
    {
        $data = [
            'ak' => env('BAIDU_MAP_AK', ''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => (string)env('BAIDU_MAP_MCODE')
        ];
        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list', $data);
        $entityList = json_decode($entityList);
        $entityList = $entityList->entities;
        $userHasEntities = $user->all()->pluck('entity_name')->toArray();
        //$entities = array_diff(array_column($entities,'entity_name'),$userHasEntities);
        //$entities = array_diff_assoc(array_column($entities,'entity_name'),$userHasEntities);
//dd($entities);
        $entities = [];
        foreach ($entityList as $entity) {
            if (in_array($entity->entity_name,$userHasEntities)) {
                $entities[] = $entity;
            }
        }
        return view('admin.user.address', compact('entities'));
    }
}
