<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Models\Matter;
use App\Models\ProgramUser;
use App\Models\Situation;
use App\Models\User;
use Doctrine\DBAL\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'contents' => 'required',
            'image' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'address' => $request->address,
            'content' => $request->contents,
            'form' => 3,
        ];

        $path = Storage::disk('public')->putFile('miniProgramImg',$request->file('image'));
        $data['image'] = '/storage/' . $path;

        $this->user()->matters()->create($data);

        return $this->success('上报成功');
    }

    public function matterStoreInfo(Request $request)
    {
        $data = [
            'title' => $request->title,
            'address' => $request->address,
            'content' => $request->contents,
            'form' => 3,
            'image' => explode(',',$request->many_images)[0],
            'many_images' => $request->many_images
        ];

        $this->user()->matters()->create($data);

        return $this->success('上报成功');
    }

    public function matterStoreImage(Request $request)
    {
        $path = Storage::disk('public')->putFile('miniProgramImg',$request->file('image'));
        $data['url'] = '/storage/' . $path;
        return response()->json($data);
    }

    /*
     * 用户历史上报记录
     * */
    public function historyMatters()
    {
        $matters = $this->user()->matters()->get();

        return $matters;
    }

    public function historyMattersDetail(Request $request, Matter $matter, Situation $situation, User $user)
    {
        $matters = Matter::with(['Situation'])->where('id', $request->id)->first();
        $data = [
            'title' => $matters->title,
            'address' => $matters->address,
            'content' => $matters->content,
            'status' => $matters->status,
            'created_at' => $matters->created_at->toDateTimeString(),
            'image' => $matters->image,
            'many_images' => explode(',', $matters->many_images),
            'userName' => $matters['situation']['user']['name'],
            'see_image' => $matters['situation']['see_image'],
            'information' => $matters['situation']['information'],
            'process' => $matters['situation']['status'],
            'see_images' => explode(';', $matters['situation']['see_images'])
        ];

        return $data;
    }

    public function weappUser()
    {
        return $this->user();
    }
}