<?php

namespace App\Http\Controllers\Admin;

use App\Models\Situation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;

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
            'title' => '临沂12345承办单',
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
            'content' => $situations->matter->content,
            'suggestion' => $situations->matter->suggestion,
            'approval' => $situations->matter->approval,
            'result' => $situations->matter->result,
            'user' => $situations->user->name,
            'see_image' => $situations->see_image,
            'information' => $situations->information
        ];

        $excel->create('list', function ($excel) use ($data) {
            $excel->sheet('list', function ($sheet) use ($data) {
                $drawing = new \PHPExcel_Worksheet_Drawing();
                $drawing->setName('image');
                $drawing->setDescription('image');
                $drawing->setPath(public_path($data['see_image']));
                $drawing->setCoordinates('F15');
                $drawing->setHeight(80);
                $drawing->setOffsetX(1);
                $drawing->setRotation(1);
                $drawing->setWorksheet($sheet);
                $sheet->loadView('admin/situation/export')->with('data', $data);
                $sheet->setFontSize(10);
            });
        })->export('xls');
    }

}
