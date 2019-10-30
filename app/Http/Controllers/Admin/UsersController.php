<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Coordinate;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Excel;

class UsersController extends Controller
{
    public function index(User $user)
    {
        $users = $user->paginate(15);

        return view('admin.user.index', compact('users'));
    }

    public function create(User $user, Curl $curl, Coordinate $coordinate)
    {
//        $data = [
//            'ak' => env('BAIDU_MAP_AK', ''),
//            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
//            'mcode'     => (string)env('BAIDU_MAP_MCODE')
//        ];
//        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list', $data);
//        $entityList = json_decode($entityList);
//        $entities = $entityList->entities;

        //dd(array_column($entities,'entity_name'));
        //$userHasEntities = $user->all()->pluck('entity_name')->toArray();
        //dd($userHasEntities);
        //dd(array_diff_assoc($userHasEntities,array_column($entities,'entity_name')));
        // 未分配用户的设备
        //$entities = array_diff(array_column($entities,'entity_name'),$userHasEntities);

        $coordinates = Coordinate::all();
        return view('admin.user.create_and_edit', compact('user', 'coordinates'));
    }


    public function store(Request $request, User $user)
    {
        $data = $request->only(['name', 'phone', 'password', 'age', 'position', 'responsible_area', 'resident_institution']);
        $data['password'] = Hash::make($data['password']);
        $user->fill($data);
        $user->save();

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        $matters = $user->situation()->orderBy('id', 'desc')->paginate(15);

        $patrolMatters = $user->patrolMatters()->orderBy('id', 'desc')->paginate(15);

        return view('admin.user.show', compact('user','matters','patrolMatters'));
    }

    public function edit(User $user, Curl $curl, Coordinate $coordinate)
    {
        //$data = [
        //    'ak' => env('BAIDU_MAP_AK', ''),
        //    'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
        //    'mcode'     => (string)env('BAIDU_MAP_MCODE')
        //];
        //$entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list', $data);
        //$entityList = json_decode($entityList);
        //$entities = $entityList->entities;

        //$userHasEntities = $user->all()->pluck('entity_name')->toArray();
        //dd($userHasEntities);
        //dd(array_diff_assoc($userHasEntities,array_column($entities,'entity_name')));
        // 未分配用户的设备
        //$entities = array_diff(array_column($entities,'entity_name'),$userHasEntities);
        $coordinates = Coordinate::all();
        return view('admin.user.create_and_edit', compact('user', 'coordinates'));
    }

    public function update(Request $request, User $user)
    {

        if (Hash::check($request->password, $user->password)) {
            $user->update($request->only(['phone', 'name', 'age', 'position', 'responsible_area', 'resident_institution']));
        } else {
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'age' => $request->age,
                'position' => $request->position,
                'responsible_area' => $request->responsible_area,
                'resident_institution' => $request->resident_institution,
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

    public function ajaxAddress(Curl $curl, User $user)
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
        return $entities;
    }

    public function latestPoint(Curl $curl, Request $request)
    {
        //return $request->all();
        $data = [
            'ak' => env('BAIDU_MAP_AK', ''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => (string)env('BAIDU_MAP_MCODE'),
            'entity_name' => $request->entity_name,
            'process_option' => 'need_denoise=1,need_mapmatch=1,radius_threshold=0,transport_mode=auto'
        ];
        $result = $curl->curl('http://yingyan.baidu.com/api/v3/track/getlatestpoint', $data);


        //$result = json_encode($result);
        //$result = json_decode($result);
        return $result;
        $latestPoint = $result->latest_point;
        //$userHasEntities = $user->all()->pluck('entity_name')->toArray();
        //$entities = array_diff(array_column($entities,'entity_name'),$userHasEntities);
        //$entities = array_diff_assoc(array_column($entities,'entity_name'),$userHasEntities);
//dd($entities);
        /*$entities = [];
        foreach ($entityList as $entity) {
            if (in_array($entity->entity_name,$userHasEntities)) {
                $entities[] = $entity;
            }
        }*/
        return $latestPoint;
    }

    public function export(User $user, Excel $excel)
    {
        $users = $user->all();
        $cellData = [];
        $firstRow = ['姓名','手机号','责任网格', '设备名', '添加时间'];
        foreach ($users as $user) {
            $data = [
                $user->name,
                $user->phone,
                $user->responsible_area,
                $user->entity_name,
                $user->created_at,
            ];
            array_push($cellData, $data);
        }
        $excel->create('人员信息', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('users', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
