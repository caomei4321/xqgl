<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\Word2007;
use PhpOffice\PhpWord\Shared\ZipArchive;
use PHPUnit\Util\Exception;

class NewsController extends Controller
{
    public function index(News $news)
    {
        $news = $news->orderBy('created_at', 'desc')->paginate();

        return view('admin.news.index', compact('news'));
    }

    public function create(News $news)
    {
        return view('admin.news.create', compact('news'));
    }

    public function store(Request $request, News $news)
    {
        $this->rule($request);
        $news->fill($request->all());
        $news->save();

        return redirect()->route('admin.news.index');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $this->rule($request);
        $news->fill($request->all());
        $news->save();

        return redirect()->route('admin.news.index');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return response()->json(['status' => '1', 'msg' => '删除成功']);
    }

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


    public function rule(Request $request)
    {
        $this->validate($request, [
            'header' => 'required|string|min:2',
            'sentence' => 'required|string|min:3',
        ], [
            'header.required' => '标题不能为空',
            'header.min' => '标题至少两个字符',
            'sentence.required' => '内容不能为空',
            'sentence.min' => '内容至少三个字符',
        ]);
    }

    public function import(Request $request)
    {
        $filePath = $this->uploadFile($request->import_file);
        $path = $filePath['path'];
        $phpWord = new PhpWord();
        $S1 = IOFactory::load($path)->getSections();
//        dd($S1);
        $arr = [];
        foreach ($S1 as $S) {
            $elements = $S->getElements();
            $arr=$this->copyElement($elements, $section);
        }
        $word = [];
        foreach ($arr['text'] as $value) {
            foreach ($value as $text){
                $text = $text[0]['text'];
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
        dd($wordData);

    }

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

    public function textarr($e)
    {
        $textArr['text']=$e->getText();
        return $textArr;
    }


}
