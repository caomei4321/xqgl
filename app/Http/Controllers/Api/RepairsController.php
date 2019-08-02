<?php

namespace App\Http\Controllers\Api;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RepairsController extends Controller
{
    public function thisUser(Request  $request)
    {
        return $request->user();
    }

    public function repairs(Repair $repair)
    {
        $repairs = $repair->where('status',0)->paginate(7);
        return  $repairs;
    }

    public function show(Repair $repair)
    {
        return $repair;
    }

    public function eventReport(Request $request)
    {
        $path = Storage::disk('public')->putfile('badImg',$request->file('bad_img'));
        $url = Storage::url($path);

        $request->user()->repairs()->create([
            'address' => $request->address,
            'description' => $request->description,
            'bad_img'   => $url
        ]);

        return $this->response->created('',['msg' => '添加成功']);
    }

    public function completeRepair(Request $request, Repair $repair)
    {return $repair;
        $path = Storage::disk('public')->putfile('goodImg', $request->file('good_img'));
        $url = Storage::url($path);


        $repair->update([
            'status' => 1,
            'good_img' => $url
        ]);

        return $this->response()->accepted('',['msg' => '维修完成']);

    }
}
