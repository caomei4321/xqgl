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
use PhpOffice\PhpWord\Shared\ZipArchive;

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
        return view('admin.matters.create_and_edit', compact('matter'));
    }

    public function store(Request $request, Matter $matter)
    {
        $this->rules($request);
        $data = $request->all();
        $matter->fill($data);
        $matter->save();

        return redirect()->route('admin.matters.index');
    }

    public function edit(Matter $matter, Situation $situation, User $user)
    {
        // 修改已分配执行人
        $user_id= $matter->situation['user_id'];
        $users = $user->all();
        return view('admin.matters.create_and_edit', compact('matter', 'user_id', 'users'));
    }

    public function update(Request $request, Matter $matter, JPushHandler $JPushHandler)
    {
        $this->rules($request);

        if ($request->user_id !== $matter->situation['user_id']) {
            $user_id = [
                'user_id' => $request->user_id
            ];
            $reg_id = User::where('id',$request->user_id)->value('reg_id');
            try{
                $JPushHandler->testJpush($reg_id);
            }catch (\Exception $e) {

            }
            $matter->situation->update($user_id);
        }

        $data = $request->all();
        $matter->fill($data);
        $matter->save();

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

    public function allocates(Request $request, Matter $matter, Situation $situation, User $user, JPushHandler $JPushHandler)
    {
        $data = $request->only(['matter_id', 'user_id', 'category_id']);
        // 将matters表中数据allocate更新为1， 代表已分配
        $matter->where('id', $request->matter_id)->update([
            'allocate' => '1'
        ]);
        // 分配信息存入user_has_matters表中
        $situation->fill($data);
        $situation->save();
        // 获取用户 reg_id 推送消息
        $reg_id = $user->where('id', $request->user_id)->value('reg_id');
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
            'title' => 'required|min:2',
            'accept_num' => 'required',
            'time_limit' => 'required',
            'work_num' => 'required',
            'content' => 'required|min:3'
        ], [
            'title.required' => '标题必填',
            'title.min' => '标题至少两个字符',
            'accept_num.required' => '受理员编号必填',
            'time_limit.required' => '办结时限必填',
            'work_num.required' => '工单编号必填',
            'content.required' => '问题描述必填',
            'content.min' => '问题描述至少三个字符'
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
        $excel->create('12345任务报表统计', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('matter', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->setWidth(array('C' => '30'));
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    // 导入Word
    public function import(Request $request)
    {
        $filePath = $this->uploadFile($request->import_file);
        $path = $filePath['path'];
        $paths = 'word/word.doc';
        iconv('UTF-8', 'GBK', $path);
        try{
            $phpWord = new PhpWord();
            $zip = new ZipArchive();
            if ($zip->open($path) !== true) {
//                $S1 = IOFactory::load($paths)->getSections();
                try {
                    $word = new \COM("word.application", null, CP_UTF8) or die("cant start");
                    $word->Visible = 0;
                    $word->Documents->open(env('APP_URL').'/'. $path);
                    header('Content-Type:text/html; charset=utf-8');
                    $test=$word->ActiveDocument->content->Text;
                    $txt = file_put_contents('1.html', $test);
                    $string = file_get_contents('1.html');
                    preg_match_all('/[\x{4e00}-\x{9fa5}a-zA-Z0-9;-]/u',$string,$result);
                    $temp =join('',$result[0]);
                    $word = explode(";", $temp);
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
                } catch (\Exception $exception) {
                    return redirect()->route('admin.matters.index')->withErrors('导入失败，请选择正确的文件和按正确的文件填写方式导入');
                }
                return redirect()->route('admin.matters.index')->withErrors('导入成功');
            } else {
                $S1 = IOFactory::load($path)->getSections();
            }
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
        $folder_name = "word/import";
//        $folder_name = "word";
        $upload_path = public_path() . '/' . $folder_name;
        $extension = strtolower($file->getClientOriginalExtension());
        $filename =   date('YmdHis', time()). mt_rand(111111,999999) .'word' . '.' . $extension ;
//        $filename =  'word' . '.' . $extension;
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
        $filePath = 'word/word.doc';
        return response()->download($filePath, '导入模板.doc');
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
