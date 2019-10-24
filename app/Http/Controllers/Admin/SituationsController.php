<?php

namespace App\Http\Controllers\Admin;

use App\Models\Situation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class SituationsController extends Controller
{
    public function index(Situation  $situation)
    {
        $situations = Situation::with(['Matter', 'User', 'Category'])->whereDoesntHave('Matter', function ($query){
            $query->where('form', '=', '3');
        })->paginate();
        return view('admin.situation.index', compact('situations'));
    }

    public function export(Request $request, Situation $situation)
    {
        $situations = $situation->with(['Matter', 'User', 'Category'])->find($request->id);
        $data = [
            'title' => '12345承办单',
            'accept_num' => $situations->matter->accept_num,
            'time_limit' => $situations->matter->time_limit,
            'work_num' => $situations->matter->work_num,
            'level' => $situations->matter->level,
            'type' => $situations->matter->type,
            'source' => $situations->matter->source,
            'is_reply' => $situations->matter->is_reply,
            'is_secret' => $situations->matter->is_secret,
            'contact_name' => $situations->matter->contact_name,
            'contact_phone' => $situations->matter->contact_phone,
            'address' => $situations->matter->address,
            'reply_remark' => $situations->matter->reply_remark,
            'category' => $situations->matter->category,
            'content' => $situations->matter->content,
            'suggestion' => $situations->matter->suggestion,
            'approval' => $situations->matter->approval,
            'result' => $situations->matter->result,
            'user' => $situations->user->name,
            'see_image' => $situations->see_image,
            'information' => $situations->information,
            'see_images' => $situations->see_images,
        ];
        $phpword = new PhpWord();
        $path = 'word/temp.docx';
        $filePath = 'word/import.docx';
        $templateProcessor = new TemplateProcessor($path);
        $templateProcessor->setValue('accept_num', $data['accept_num']);
        $templateProcessor->setValue('time_limit', $data['time_limit']);
        $templateProcessor->setValue('work_num', $data['work_num']);
        $templateProcessor->setValue('level', $data['level']);
        $templateProcessor->setValue('type', $data['type']);
        $templateProcessor->setValue('source', $data['source']);
        $templateProcessor->setValue('is_reply', $data['is_reply']);
        $templateProcessor->setValue('is_secret', $data['is_secret']);
        $templateProcessor->setValue('contact_name', $data['contact_name']);
        $templateProcessor->setValue('contact_phone', $data['contact_phone']);
        $templateProcessor->setValue('address', $data['address']);
        $templateProcessor->setValue('reply_remark', $data['reply_remark']);
        $templateProcessor->setValue('category', $data['category']);
        $templateProcessor->setValue('content', $data['content']);
        $templateProcessor->setValue('suggestion', $data['suggestion']);
        $templateProcessor->setValue('approval', $data['approval']);
        $templateProcessor->setValue('result', $data['result']);
        $templateProcessor->setValue('user', $data['user']);
        if ($data['see_image']) {
            $templateProcessor->setImageValue('see_image', [
                'path' => "http://".$_SERVER['HTTP_HOST'].$data['see_image'],
            ]);
        }else{
            $templateProcessor->setValue('see_image', '');
        }
        if ($data['see_images']) {
            $data['see_images'] = explode(';', $data['see_images']);
            if ($data['see_images'][0]) {
                $templateProcessor->setImageValue('see_images1', [
                    'path' => "http://".$_SERVER['HTTP_HOST'].$data['see_images'][0],
                ]);
            }else{
                $templateProcessor->setValue('see_images1', '');
            }
            if ($data['see_images'][1]) {
                $templateProcessor->setImageValue('see_images2', [
                    'path' => "http://".$_SERVER['HTTP_HOST'].$data['see_images'][1],
                ]);
            }else{
                $templateProcessor->setValue('see_images2', '');
            }
        }
        $templateProcessor->setValue('information', $data['information']);
        $templateProcessor->saveAs($filePath);
        return response()->download($filePath, mt_rand(111111,999999).date('YmdHis',time()).'bd.doc');
    }

}
