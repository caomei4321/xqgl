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

    public function endMatter(Request $request)
    {
        if ('是巡查员发现问题') {

        } elseif ('后台添加') {

        } elseif ('12345导入') {

        }
    }

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

        return $this->success('提交成功');
    }
}
