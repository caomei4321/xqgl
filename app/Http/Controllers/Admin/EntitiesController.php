<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Handlers\Curl;

class EntitiesController extends Controller
{
    public function index(Curl $curl)
    {
        $data = [
            'ak' => env('BAIDU_MAP_AK',''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => (string)env('BAIDU_MAP_MCODE')
        ];
        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list',$data);
        $entityList = json_decode($entityList);

        if ($entityList->status === 0) {
            $entities = $entityList->entities;
            return view('admin.entity.index', compact('entities'));
        } else {
            $entities = [];
            return view('admin.entity.index', compact('entities'));
        }
    }

    public function destroy(Request $request, Curl $curl)
    {

        $data = [
            'ak' => env('BAIDU_MAP_AK',''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => env('BAIDU_MAP_MCODE'),
            'entity_name' => $request->entity_name
        ];
        $result = $curl->curl('http://yingyan.baidu.com/api/v3/entity/delete', $data, true);

        $result = json_decode($result);

        //dd($result);
        if ($result->status === 0) {
            return response()->json([
                'status' => 1,
                'msg' => '删除成功'
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => '删除失败'
            ]);
        }

    }

    public function store(Request $request, Curl $curl)
    {
        $this->validate($request,[
            'entity_name' => 'required',
            'entity_desc' => 'required'
        ]);

        $data = [
            'ak' => env('BAIDU_MAP_AK',''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => env('BAIDU_MAP_MCODE'),
            'entity_name' => $request->entity_name,
            'entity_desc' => $request->entity_desc
        ];

        $result = $curl->curl('http://yingyan.baidu.com/api/v3/entity/add', $data, true);

        $result = json_decode($result);

        if ($result->status === 0) {
            return response()->json([
                'status' => 1,
                'msg' => '添加成功'
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => '添加失败'
            ]);
        }
    }
}
