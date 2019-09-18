<?php

namespace App\Http\Controllers\Api;

use App\Models\ProgramUser;
use Illuminate\Http\Request;

class ProgramUsersController extends Controller
{
    /*
     * 小程序上报问题
     * */
    public function matterStore(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'address' => 'required|max:255',
            'content' => 'required',
            'image' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'address' => $request->address,
            'content' => $request->contents,
            'from' => 3
        ];

        $this->user()->matters()->create($data);

        return $this->success('上报成功');
    }

    /*
     * 用户历史上报记录
     * */
    public function historyMatters()
    {
        $matters = $this->user()->matters();

        return $matters;
    }
}