<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class PatrolController extends Controller
{
    public function startAndEndPatrol(Request $request)
    {
        if ($request->start_time) {
            $patrol = $this->user()->patrols()->create();

            return $this->success([
                'id' =>  $patrol->id
            ]);
        } elseif ($request->end_time) {

            $patrol = $this->user()->patrols()->find($request->id);

            $patrol->end_at = $request->end_time;
            $patrol->save();

            return $this->success('结束成功');
        }
    }
}
