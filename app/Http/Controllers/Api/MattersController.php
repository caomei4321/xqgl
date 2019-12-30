<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\MatterCollection;
use App\Http\Resources\Api\MatterResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Matter;
use App\Models\PatrolMatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Situation;

class MattersController extends Controller
{
    public function userHasMatters()
    {
        /*return $this->user()->whereHas('situation', function ($query) {
            $query->where('user_has_matters.status','0');
        })->get();*/
        return new MatterCollection($this->user()->situation()->whereIn('user_has_matters.status',[0,3])->orderBy('created_at', 'desc')->get());
    }

    public function userCompleteMatters()
    {
        /*return $this->user()->whereHas('situation', function ($query) {
            $query->where('user_has_matters.status','1');
        })->get();*/
        //return $this->user()->patrolMatters()->where('status', 1)->get();
        $matters = $this->user()->situation()->where('user_has_matters.status',1)->get(['title', 'content', 'matters.created_at','user_has_matters.see_image'])->toArray();

        $patrolMatters = $this->user()->patrolMatters()->where('status', 1)->get()->toArray();

        foreach ($patrolMatters as $patrolMatter) {
            $data = [
                'title' => $patrolMatter['title'],
                'content' => $patrolMatter['content'],
                'created_at' => $patrolMatter['created_at'],
                'pivot' => [
                    'see_image' => $patrolMatter['image'],
                ]
            ];
            $matters[] = $data;
        }

        $result = [
            'data' => $matters
        ];

        return $result;

        //return $matters;
        //return new MatterCollection($this->user()->situation()->where('user_has_matters.status',1)->get());
    }

    public function userMatters()
    {
        /*return $this->user()->whereHas('situation', function ($query) {
            $query->where('user_has_matters.status','1');
        })->get();*/
        return new MatterCollection($this->user()->situation()->orderBy('user_has_matters.status', 'asc')->get());
    }

    public function matter(Request $request)
    {
        $matter = Matter::find($request->id);

        return new MatterResource($matter);
    }

    public function patrolMatters()
    {
        return new MatterCollection($this->user()->patrolMatters()->orderBy('status', 'desc')->get());
    }
    /*
     * 巡查发现的问题处理
     * */
    public function findMatterAndEnd(Request $request,PatrolMatter $patrolMatter)
    {


        if ($request->id) {  // 更新已有数据
            // 更新数据不需要更新经纬度信息
            $data = $request->only(['title', 'content', 'suggest']);

            $patrolMatter = $patrolMatter->find($request->id);
            if ($patrolMatter->images) {
                $data['images'] = $patrolMatter->images . ';'; //拼接 分号 ，统一格式后面截掉
            } else {
                $data['images'] = '';
            }

            $imgdata = $request->img;
            if (is_array($imgdata)) {

                for ($i = 0; $i < count($imgdata); $i++) {
                    $image = base64_decode($imgdata[$i]);

                    $imgname = 'mt' . '_' . time() . '_' . str_random(10) . '.jpg';
                    Storage::disk('public')->put($imgname, $image);

                    $data['images'] = $data['images'] . '/storage/' . $imgname . ';';

                }
            }
            if (!empty($data['images'])) {
                $data['images'] = substr($data['images'],0,-1);
            }
            /*
            * status 表示任务状态
            *      0：默认状态，表示未处理
            *      1：表示无权处理
            *      2：表示处理完成
            *      3：表示处理中
            * */
            if ($request->result == 1) {  // result  1表示处理完成  0表示无权处理，  2表示处理中
                $data['status'] = 2;
            } elseif ($request->result == 2) {
                $data['status'] = 3;
            } else {
                $data['status'] = 1;
            }
            $patrolMatter->update($data);

        } else {  // 添加新记录
            $data = $request->only(['title', 'content', 'latitude', 'longitude', 'suggest']);

            $imgdata = $request->img;
            $data['images'] = '';

            if (is_array($imgdata)) {

                for ($i = 0; $i < count($imgdata); $i++) {
                    $image = base64_decode($imgdata[$i]);

                    $imgname = 'mt' . '_' . time() . '_' . str_random(10) . '.jpg';
                    Storage::disk('public')->put($imgname, $image);

                    if ($i == 0) {
                        $data['image'] = '/storage/' . $imgname;
                    } else {
                        $data['images'] = $data['images'] . '/storage/' . $imgname . ';';
                    }
                }
            }
            if (!empty($data['images'])) {
                $data['images'] = substr($data['images'],0,-1);
            }

            /*
            * status 表示任务状态
            *      0：默认状态，表示未处理
            *      1：表示无权处理
            *      2：表示处理完成
            *      3：表示处理中
            * */
            if ($request->result == 1) {  // result  1表示处理完成  0表示无权处理，  2表示处理中
                $data['status'] = 2;
            } elseif ($request->result == 2) {
                $data['status'] = 3;
            } else {
                $data['status'] = 1;
            }
            $data['patrol_id'] = $request->patrol_id ? $request->patrol_id : null;

            $this->user()->patrolMatters()->create($data);
        }


        return $this->success('提交成功');
    }
    /*public function findMatterAndEnd(Request $request, Matter $matter, Situation $situation)
    {
        $data['patrol_id'] = $request->id ? $request->id : null;

        $data = $request->only(['title', 'content', 'latitude', 'longitude',]);

        $data['suggestion'] = $request->suggest;

        $data['patrol_id'] = $request->id ? $request->id : null;

        $imgdata = $request->img;  // 提交的所有图片

        $data['many_images'] = '';   // 需要保存的多张图片

        if (is_array($imgdata)) {

            for ($i = 0; $i < count($imgdata); $i++) {
                $image = base64_decode($imgdata[$i]);

                $imgname = 'mt' . '_' . time() . '_' . str_random(10) . '.jpg';
                Storage::disk('public')->put($imgname, $image);

                if ($i == 0) {
                    $data['image'] = '/storage/' . $imgname;
                } else {
                    $data['many_images'] = $data['images'] . '/storage/' . $imgname . ';';
                }
            }
        }
        if (!empty($data['images'])) {
            $data['images'] = substr($data['images'],0,-1);
        }

        $matter = $this->user()->matters()->create($data);

        return $matter;
        $situation->create([
            ''
        ]);

        return $this->success('提交成功');
    }*/


    /*
     * 12345导入的问题处理
     * */
    public function endImportMatter(Request $request, Situation $situation)
    {
        $situation = $situation->where('matter_id', $request->id)->first();

        $imgdata = $request->img;
        //$base64_str = substr($imgdata, strpos($imgdata, ",") + 1);

        $see_image = '';
        $see_images = '';

        if ($situation->see_image) {  // 如果已经有数据则追加，不更新 see_img
            if ($situation->see_images) {
                $see_images = $situation->see_images . ';'; //拼接 分号 ，统一格式后面截掉
            }
            if (is_array($imgdata)) {
                for ($i = 0; $i < count($imgdata); $i++) {
                    $image = base64_decode($imgdata[$i]);

                    $imgname = 'mt' . '_' . time() . '_' . str_random(10) . '.jpg';
                    Storage::disk('public')->put($imgname, $image);

                    $see_images = $see_images . '/storage/' . $imgname . ';';
                }
            }
            $see_image = $situation->see_image;
        } else {
            if (is_array($imgdata)) {
                for ($i = 0; $i < count($imgdata); $i++) {
                    $image = base64_decode($imgdata[$i]);

                    $imgname = 'mt' . '_' . time() . '_' . str_random(10) . '.jpg';
                    Storage::disk('public')->put($imgname, $image);

                    if ($i == 0) {
                        $see_image = '/storage/' . $imgname;
                    } else {
                        $see_images = $see_images . '/storage/' . $imgname . ';';
                    }
                }
            }
        }

        if (!empty($see_images)) {
            $see_images = substr($see_images,0,-1);
        }

        /*
         * status 表示任务状态
         *      0：默认状态，表示未处理
         *      1：表示处理完成
         *      2：表示无权处理
         *      3：表示处理中
         * */
        if ($request->result == 1) {  // result  0表示处理完成 1表示无权处理，  2表示处理中
            $status = 2;
        } elseif ($request->result == 2) {
            $status = 3;
        } else {
            $status = 1;
        }

        $situation->update([
            'see_image' => $see_image,
            'see_images' => $see_images,
            'information' => $request->suggest,
            'status' => $status
        ]);

        Matter::find($request->id)->update([
            'status' => 1
        ]);

        return $this->success('提交成功');
    }
}
