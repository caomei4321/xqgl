<?php

namespace App\Http\Controllers\Api;

use App\Models\Responsibility;
use App\Models\Version;
use Illuminate\Http\Request;

class VersionsController extends Controller
{
    public function version(Version $version)
    {
        $data = $version->limit(1)->orderBy('created_at','desc')->get();
        return $this->response->array([
            'data' => $data,
            'success' => 1
        ]);
    }

    public function zr(Responsibility $responsibility ,Request $request)
    {
        $data = $request->all();
        if ($data['subject_duty'] == 0) {
            $data['cooperate_duty'] = 1;
        } else {
            $data['cooperate_duty'] = 0;
        }
        $responsibility->fill($data);
        $responsibility->save();

        return $this->success(['success' => 1]);
    }
}
