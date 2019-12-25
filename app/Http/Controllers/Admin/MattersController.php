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
        $matters = $matter->where('form', [1,2])->orderBy('created_at', 'desc')->orderBy('allocate','asc')->paginate();

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
        $this->validate($request, [
            'matter_id' => 'unique:user_has_matters'
        ],[
            'matter_id.unique' => '此工单已被分配'
        ]);
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
        iconv('UTF-8', 'GBK', $path);
        try{
            $phpWord = new PhpWord();
            $zip = new ZipArchive();
            if ($zip->open($path) !== true) {
                try {
                    $content = shell_exec('/usr/local/bin/antiword -w 0 -m UTF-8.txt ' . $path);
                    $DBC = array('0','1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U','V', 'W', 'X', 'Y', 'Z','ａ','ｂ','ｃ', 'ｄ' ,'ｊ', 'ｋ' , 'ｌ' , 'ｍ' , 'ｎ' ,'ｏ' , 'ｐ' , 'ｑ' , 'ｒ' , 'ｓ' ,'ｔ' , 'ｕ' , 'ｖ' , 'ｗ' , 'ｘ' ,'ｙ' , 'ｚ' , '－' , '　' , '：' ,'．' , '，' , '／' , '％' , '＃' , '！' , '＠' , '＆' , '（' , '）' ,'＜' , '＞' , '＂' , '＇' , '？' ,'［' , '］' , '｛' , '｝' , '＼' ,'｜' , '＋' , '＝' , '＿' , '＾' ,'￥' , '￣' , '｀' );

                    $SBC = Array('0', '1', '2', '3', '4','5', '6', '7', '8', '9','A', 'B', 'C', 'D', 'E','F', 'G', 'H', 'I', 'J','K', 'L', 'M', 'N', 'O','P', 'Q', 'R', 'S', 'T','U', 'V', 'W', 'X', 'Y','Z', 'a', 'b', 'c', 'd','e', 'f', 'g', 'h', 'i','j', 'k', 'l', 'm', 'n','o', 'p', 'q', 'r', 's','t', 'u', 'v', 'w', 'x','y', 'z', '-', ' ', ':','.', ',', '/', '%', '#','!', '@', '&', '(', ')','<', '>', '"', '\'','?','[', ']', '{', '}', '\\','|', '+', '=', '_', '^','$', '~', '`');
                    $content = str_replace($DBC, $SBC, $content);
                    file_put_contents('d.txt', $content);
                    $str = file_get_contents('d.txt');
                    preg_match_all('/[\x{4e00}-\x{9fa5}a-zA-Z0-9;|-]/u',$str,$result);
                    $temp =join('',$result[0]);
                    $word = explode("|", $temp);
                    $docArray = [
                        array_search('受理员编号', $word) .'-'. array_search('受理员', $word),
                        array_search('办结时限', $word) .'-'. array_search('工单编号', $word),
                        array_search('工单编号', $word) .'-'. array_search('紧急程度', $word),
                        array_search('紧急程度', $word) .'-'. array_search('来电类别', $word),
                        array_search('来电类别', $word) .'-'. array_search('信息来源', $word),
                        array_search('信息来源', $word) .'-'. array_search('是否回复', $word),
                        array_search('是否回复', $word) .'-'. array_search('是否保密', $word),
                        array_search('是否保密', $word) .'-'. array_search('联系人', $word),
                        array_search('联系人', $word) .'-'. array_search('联系电话', $word),
                        array_search('联系电话', $word) .'-'. array_search('联系地址', $word),
                        array_search('联系地址', $word) .'-'. array_search('回复备注', $word),
                        array_search('回复备注', $word) .'-'. array_search('问题分类', $word),
                        array_search('问题分类', $word) .'-'. array_search('问题描述', $word),
                        array_search('问题描述', $word) .'-'. array_search('转办意见', $word),
                        array_search('转办意见', $word) .'-'. array_search('领导批示', $word),
                        array_search('领导批示', $word) .'-'. array_search('办理结果', $word),
                        array_search('办理结果', $word) .'-'. (count($word) - 1),
                    ];
                    $doc = array();
                    for ($i=0; $i < count($docArray); $i++) {
                        $key = explode('-',$docArray[$i]);
                        if ($key['1'] - $key['0'] > 0) {
                            $tmp = [];
                            for ($j =  $key['0']+1; $j < $key['1']; $j++) {
                                array_push($tmp, $word[$j]);
                            }
                            array_push($doc, implode(';', array_filter($tmp)));
                        } else {
                            array_push($doc, ' ');
                        }
                    }
                    $wordData = [
                        'title' => '12345承办单',
                        'accept_num' => $doc[0],
                        'acceptor' => $doc[1],
                        'work_num' => $doc[2],
                        'level' => $doc[3],
                        'type' => $doc[4],
                        'source' => $doc[5],
                        'is_reply' => $doc[6],
                        'is_secret' => $doc[7],
                        'contact_name' => $doc[8],
                        'contact_phone' => $doc[9],
                        'address' => $doc[10],
                        'reply_remark' => $doc[11],
                        'category' => $doc[12],
                        'content' => $doc[13],
                        'suggestion' => $doc[14],
                        'approval' => $doc[15],
                        'result' => $doc[16],
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
