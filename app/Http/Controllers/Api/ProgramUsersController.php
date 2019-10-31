<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Models\Matter;
use App\Models\ProgramUser;
use Doctrine\DBAL\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramUsersController extends Controller
{
    /*
     * 小程序上报问题
     * */
    public function matterStore(Request $request, ImageUploadHandler $uploader)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'address' => 'required|max:255',
            'contents' => 'required',
            'image' => 'required',
        ]);
        $data = [
            'title' => $request->title,
            'address' => $request->address,
            'content' => $request->contents,
            'form' => 3
        ];
        $file = $request->file('image');
        $filePath = [];
        foreach ($file as $key=>$value) {
            if (!$value->isValid()) {
                return '上传错误';
            }
            if (!empty($value)) {
                $path = $uploader->save($value, 'matters', 'mt');
                array_push($filePath, $path['path']);
            }
        }
        if (count($filePath) == 1) {
            $data['image'] = $filePath['0'];
        } else {
            $data['image'] = $filePath['0'];
            unset($filePath['0']);
            $data['many_images'] = implode(';', $filePath);
        }
        $this->user()->matters()->create($data);
        return $this->success('上报成功');
    }

    /*
     * 用户历史上报记录
     * */
    public function historyMatters()
    {
        $matters = $this->user()->matters()->get();

        return $matters;
    }

    public function weappUser()
    {
        return $this->user();
    }
}