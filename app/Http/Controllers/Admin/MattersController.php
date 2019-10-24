<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Matter;
use App\Models\Situation;
use App\Models\User;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use App\Handlers\JPushHandler;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class MattersController extends Controller
{
    protected  $table = "user_ has_matters";

    // 12345 任务管理
    public function index(Matter $matter, Request $request)
    {
        $matters = $matter->where('form', [1,2])->orderBy('allocate', 'asc')->paginate();

        return view('admin.matters.index', compact('matters'));
    }

    public function create(Matter $matter)
    {
        $category = Category::all();
        return view('admin.matters.create_and_edit', compact('matter', 'category'));
    }

    public function store(Request $request, Matter $matter, ImageUploadHandler $uploader)
    {
        $this->rules($request);
        $data = $request->all();
        if (!empty($request->image)){
            $result = $uploader->save($request->image, 'matters', 'mt');
            if ($result) {
                $data['image'] = $result['path'];
            }
        }
        $matter->fill($data);
        $matter->save();

        return redirect()->route('admin.matters.index');
    }

    public function edit(Matter $matter)
    {
        $category = Category::all();
        return view('admin.matters.create_and_edit', compact('matter', 'category'));
    }

    public function update(Request $request, Matter $matter, ImageUploadHandler $uploader)
    {
        $this->rules($request);
        $data = $request->all();
        if (!empty($request->image)){
            $result = $uploader->save($request->image, 'matters', 'mt');
            if ($result) {
                $data['image'] = $result['path'];
            }
        }
        $matter->update($data);

        return redirect()->route('admin.matters.index');
    }

    public function destroy(Matter $matter)
    {
        $matter->delete();

        return response()->json(['status' => '1', 'msg'=> '删除成功']);
    }

    public function getUser(User $user)
    {
        $users = $user->get();
        return response()->json($users);
    }

    public function open(Request $request)
    {
        $data = $request->all();
        DB::table('matters')->where('id', $data['id'])->update($data);
        return redirect()->route('admin.matters.index');
    }

    // 分配到人 点击操作按钮每次单个分配
    public function allocate(Request $request, Matter $matter, User $user)
    {
        $matterInfo =  $matter->find($request->id);
        $users = $user->all();
        return view('admin.matters.allocate', compact('matterInfo', 'users'));
    }

    public function allocates(Request $request, JPushHandler $JPushHandler)
    {
        $data = $request->only(['matter_id', 'user_id', 'category_id']);
        // 将matters表中数据allocate更新为1， 代表已分配
        $matters = [
            'id' => $data['matter_id'],
            'allocate' => '1'
        ];
        DB::table('matters')->where('id', $data['matter_id'])->update($matters);
        // 分配信息存入user_has_matters表中
        $allocate = [
            'matter_id' => $data['matter_id'],
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ];
        DB::table('user_has_matters')->insert($allocate);
        $reg_id = DB::table('users')->where('id', $data['user_id'])->value('reg_id');
        try {
            $JPushHandler->testJpush($reg_id);
        }catch (\Exception $exception) {
            return redirect()->route('admin.matters.index');
        }

        return redirect()->route('admin.matters.index');
    }

    public function rules(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|min:2',
            'content' => 'required|string|min:3',
            'image' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=200, min_height=200',
        ], [
            'title.required' => '标题不能为空',
            'title.min' => '标题至少两个字符',
            'content.required' => '内容不能为空',
            'content.min' => '内容至少三个字符',
            'image.mimes' => '必须是jpeg, bmp, png, gif格式的图片',
            'image.dimensions' => '图片清晰度不够， 宽和高需要 200px 以上'
        ]);
    }

    // 导出
    public function export(Request $request, Matter $matter, Excel $excel)
    {
        $timeStart = $request->timeStart ? "$request->timeStart 00:00:00" : '2019-01-01 00:00:00';
        $timeEnd = $request->timeEnd ? "$request->timeEnd 23:59:59" : date('Y-m-d H:i:s', time());
        $matters = $matter->where('form', '<' ,'3')->whereBetween('created_at', [$timeStart, $timeEnd])->get()->toArray();
        $cellData = [];
        $firstRow = ['受理员编号','办结时限','工单编号','紧急程度','来电类别','信息来源','是否回复','是否保密','联系人','联系电话','联系地址','回复备注','问题分类','问题描述','特办意见','领导批示','办理结果','创建时间'];
        foreach ($matters as $matter) {
            $data = [
                $matter['accept_num'],
                $matter['time_limit'],
                $matter['work_num'],
                $matter['level'],
                $matter['type'],
                $matter['source'],
                $matter['is_reply'],
                $matter['is_secret'],
                $matter['contact_name'],
                $matter['contact_phone'],
                $matter['address'],
                $matter['reply_remark'],
                $matter['category'],
                $matter['content'],
                $matter['suggestion'],
                $matter['approval'],
                $matter['result'],
                $matter['created_at']
            ];
            array_push($cellData, $data);
        }
        $excel->create('事件记录', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('matter', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    // 导入Excel
//    public function import(Request $request, Excel $excel)
//    {
//        $filePath = $this->uploadFile($request->import_file);
//        $path = $filePath['path'];
//        iconv('UTF-8', 'GBK', $path);
//        try {
//            $excel->load($path, function ($reader) {
//                $reader->noHeading();
//                $reader = $reader->getSheet(0);
//                $result = $reader->toArray();
////                unset($result[0]);    // 删除表头
//                $excelData = [
//                    'title' => $result[0][0],
//                    'accept_num' => intval($result[3][1]),
//                    'time_limit' => $result[3][3],
//                    'work_num' => $result[4][1],
//                    'level' => $result[4][3],
//                    'type' => $result[5][1],
//                    'source' => $result[5][3],
//                    'is_reply' => $result[6][1],
//                    'is_secret' => $result[6][3],
//                    'contact_name' => $result[7][1],
//                    'contact_phone' => number_format($result[7][3],0,'',''),
//                    'address' => $result[8][1],
//                    'reply_remark' => $result[9][1],
//                    'category' => $result[10][1],
//                    'content' => $result[11][1],
//                    'suggestion' => $result[12][1],
////                    'approval' => $result[13][1],
////                    'result' => $result[14][1],
//                    'created_at' => date('Y-m-d H:i:s', time()),
//                    'updated_at' => date('Y-m-d H:i:s', time()),
//
//                ];
//                DB::table('matters')->insert($excelData);
//            });
//        }catch (\Exception $e) {
//            return redirect()->route('admin.matters.index')->withErrors('导入失败，请选择正确的文件和按正确的文件填写方式导入');
//        }
//        return redirect()->route('admin.matters.index')->withErrors('导入成功');
//    }

    // 导入Word
    public function import(Request $request)
    {
        $filePath = $this->uploadFile($request->import_file);
        $path = $filePath['path'];
        iconv('UTF-8', 'GBK', $path);
        try{
            $phpWord = new PhpWord();
            $S1 = IOFactory::load($path)->getSections();
            $arr = [];
            foreach ($S1 as $S) {
                $elements = $S->getElements();
                $arr=$this->copyElement($elements, $section);
            }
            $word = [];
            foreach ($arr['text'] as $value) {
                foreach ($value as $text){
                    if ($text) {
                        $text = preg_replace('# #','',implode('',array_column($text, 'text')));
                    }
                    array_push($word, $text);
                }
            }
            $wordData = [
                'title' => '12345承办单',
                'accept_num' => $word[1],
                'time_limit' => $word[3],
                'work_num' => $word[5],
                'level' => $word[7],
                'type' => $word[9],
                'source' => $word[11],
                'is_reply' => $word[13],
                'is_secret' => $word[15],
                'contact_name' => $word[17],
                'contact_phone' => $word[19],
                'address' => $word[21],
                'reply_remark' => $word[23],
                'category' => $word[25],
                'content' => $word['27'],
                'suggestion' => $word[29],
                'approval' => $word[31],
                'result' => $word[33],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ];

            DB::table('matters')->insert($wordData);
        }catch (\Exception $exception){
            return redirect()->route('admin.matters.index')->withErrors('导入失败，请选择正确的文件和按正确的文件填写方式导入');
        }
        return redirect()->route('admin.matters.index')->withErrors('导入成功');
    }
    // 复制元素
    public function copyElement($elements, &$container)
    {
        $arrx = [];
        foreach ($elements as $el) {
            $class=get_class($el);
            $elname=explode("\\", $class)[3];

            if ($elname == "PageBreak") {
                $this->currentPage++;
            } else {
                $this->currentPage=1;
            }

            if ($elname=='TextRun') {
                $arrx['tmptext'][]=$this->getTextElement($el);
            }

            if ($elname=='Table') {
                $rows=count($el->getRows());
                $cells=$el->CountColumns();
                $arrx['rows'] = $rows;
                $arrx['cells'] = $cells;
                for($i=0;$i<$rows;$i++) {
                    $rows_a=$el->getRows()[$i];
                    for($j = 0; $j < $cells; $j++) {
                        if ($j < 2) {
                            $x=$rows_a->getCells()[$j];
                            $arrx['text'][$i+1][$j+1]=$this->getTextElement($x);
                        }else{
                            if (isset($rows_a->getCells()[$j])) {
                                $x=$rows_a->getCells()[$j];
                                $arrx['text'][$i+1][$j+1]=$this->getTextElement($x);
                            } else {
                                continue;
                            }
                        }
                    }
                }
            }

        }

        return $arrx;

    }
    // 得到文本
    public function getTextElement($E)
    {
        $elements = $E->getElements();
        $result = [];
        $inResult=[];
        foreach($elements as $inE) {
            $ns = get_class($inE);
            $elName = explode('\\', $ns)[3];
            if($elName == 'Text') {
                $result[] = $this->textarr($inE);
            } elseif (method_exists($inE, 'getElements')) {
                $inResult = $this->getTextElement($inE);
            }
            if(!is_null($inResult)) {
                $result = array_merge($result, $inResult);
            }
        }
        return count($result) > 0 ? $result : null;
    }
    // 文本数组
    public function textarr($e)
    {
        $textArr['text']=$e->getText();
        return $textArr;
    }

    // 导入上传文件
    public function uploadFile($file)
    {
        $folder_name = "word";
        $upload_path = public_path() . '/' . $folder_name;

        $extension = strtolower($file->getClientOriginalExtension()) ? 'docx' : 'docx';

        $filename =   'word' . '.' . $extension;

        if ( ! in_array($extension, ['doc', 'docx'])) {
            return false;
        }

        $file->move($upload_path, $filename);

        return [
            'path' => "$folder_name/$filename"
        ];
    }

    // 导入下载模板
    public function download()
    {
        $filePath = 'excel/word.doc';
        return response()->download($filePath, 'Word导入模板.doc');
    }

    // 鼠标绘制点线面
    public function mouse()
    {
        return view('admin.matters.mouse');
    }

    public function ajaxData(Request $request)
    {
        $data = $request->all();
        return response()->json(['status' => 1, 'msg' => $data]);
    }
}
