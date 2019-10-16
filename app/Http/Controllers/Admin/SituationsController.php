<?php

namespace App\Http\Controllers\Admin;

use App\Models\Situation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class SituationsController extends Controller
{
    public function index(Situation  $situation)
    {
        $situations = Situation::with(['Matter', 'User', 'Category'])->paginate(10);

        return view('admin.situation.index', compact('situations'));
    }

    public function export(Request $request, Situation $situation, Excel $excel)
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
            'category_id' => $situations->category->name,
            'category' => $situations->matter->category,
            'content' => $situations->matter->content,
            'suggestion' => $situations->matter->suggestion,
            'approval' => $situations->matter->approval,
            'result' => $situations->matter->result,
            'user' => $situations->user->name,
            'see_image' => $situations->see_image,
            'information' => $situations->information
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
        $templateProcessor->setImageValue('see_image', ['path' => "http://".$_SERVER['HTTP_HOST'].$data['see_image']]);
        $templateProcessor->setValue('information', $data['information']);
        $templateProcessor->saveAs($filePath);
        return response()->download($filePath, mt_rand(111111,999999).date('YmdHis',time()).'bd.doc');


//        $excel->create('list', function ($excel) use ($data) {
//            $excel->sheet('list', function ($sheet) use ($data) {
//                if ($data['see_image']) {
//                    $drawing = new \PHPExcel_Worksheet_Drawing();
//                    $drawing->setName('image');
//                    $drawing->setDescription('image');
//                    $drawing->setPath(public_path($data['see_image']));
//                    $drawing->setCoordinates('F15');
//                    $drawing->setHeight(80);
//                    $drawing->setOffsetX(1);
//                    $drawing->setRotation(1);
//                    $drawing->setWorksheet($sheet);
//                }
//                $sheet->loadView('admin/situation/export')->with('data', $data);
//                $sheet->setFontSize(10);
//            });
//        })->export('xls');
    }

}
