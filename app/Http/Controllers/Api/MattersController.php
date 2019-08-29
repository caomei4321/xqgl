<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\MatterResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Matter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Situation;

class MattersController extends Controller
{
    public function userHasMatters()
    {
        return new UserResource($this->user());
    }

    public function matter(Request $request)
    {
        $matter = Matter::find($request->id);

        return new MatterResource($matter);
    }


    /*
     * 巡查发现的问题处理
     * */
    public function endMatter(Request $request)
    {

    }


    /*
     * 12345导入的问题处理
     * */
    public function endImportMatter(Request $request, Situation $situation)
    {
        $situation = $situation->where('matter_id', $request->id)->first();

        $imgdata = $request->img;
        //$base64_str = substr($imgdata, strpos($imgdata, ",") + 1);
        $image = base64_decode($imgdata);

        $imgname = 'mt' . '_' . time() . '_' . str_random(10) . '.jpg';
        Storage::disk('public')->put($imgname, $image);
        $imagePath = '/storage/' . $imgname;

        if ($request->result === 1) {  // result 1表示处理完成，0表示无权处理
            $status = 2;
        } else {
            $status = 1;
        }

        $situation->update([
            'see_image' => $imagePath,
            'information' => $request->suggest,
            'status' => $status
        ]);

        Matter::find($request->id)->update([
            'status' => 1
        ]);

        return $this->success('提交成功');
    }
}
