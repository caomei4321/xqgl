<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class PatrolController extends Controller
{
    public function startAndEndPatrol(Request $request)
    {
        //return $request->all();
        if ($request->start_time) {
            $this->user()->patrols()->create();

            return $this->success('开始巡查');
        } elseif ($request->end_time) {
            $patrol = $this->user()->patrols()->orderBy('id', 'desc')->first();

            $patrol->update([
                'end_at' => $request->end_time,
            ]);
            return $this->success('结束成功');
        }
    }
}
