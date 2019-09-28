<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Patrol;
use App\Models\Situation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CountsController extends Controller
{
    public function index(Situation  $situation, Patrol $patrol)
    {
        // 今日未完成任务
        $unfinished = DB::table('user_has_matters')->where('status', '0')->whereBetween('updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])->count();
        // 今日所有任务
        $all = DB::table('user_has_matters')->whereBetween('updated_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])->count();
        // 总任务
        $AllMatter = $this->allMatter();
        return view('admin.count.index', compact('unfinished', 'all', 'AllMatter'));
    }
    // 总任务量
    public function allMatter()
    {
        $all = DB::table('user_has_matters')->count();
        $unfinish = DB::table('user_has_matters')->where('status', '0')->count();
        $data = [
            'all' => $all,
            'unfinished' => $unfinish,
            'finished' => $all - $unfinish
        ];
        return $data;
    }

    // 总人数在巡查
    public function allUserPatrols(Curl $curl)
    {
        $data = [
            'ak' => env('BAIDU_MAP_AK',''),
            'service_id' => env('BAIDU_MAP_SERVICE_ID', ''),
            'mcode'     => (string)env('BAIDU_MAP_MCODE')
        ];
        $entityList = $curl->curl('http://yingyan.baidu.com/api/v3/entity/list',$data);
        $entityList = json_decode($entityList);

        if ($entityList->status === 0) {
//            $entities = $entityList->entities;
            $entities = [
                [
                    'sbh' => '63cebe085ca51e6c',
                    'ms' => '小骆',
                    'time' => '2019-09-27 13:19:18',
                ],
                [
                    'sbh' => '5dfed1ade92f7fb1',
                    'ms' => '小唐',
                    'time' => '2019-09-27 13:19:18'
                ],
                [
                    'sbh' => '059f7a2ea9a07319',
                    'ms' => '邵琴',
                    'time' => '2019-09-27 14:43:42'
                ],
                [
                    'sbh' => '469229d2d97e5928',
                    'ms' => '倪晨晔',
                    'time' => '2019-09-27 16:40:30'
                ],
                [
                    'sbh' => '61b5156ec0917d69',
                    'ms' => '蒋颀宽',
                    'time' => '2019-09-27 18:45:30'
                ]
            ];
            return response()->json($entities);
        } else {
            $entities = [
                [
                    'sbh' => '63cebe085ca51e6c',
                    'ms' => '小骆',
                    'time' => '2019-09-27 13:19:18',
                    'time2' => '2019-09-27 14:19:18'
                ],
                [
                    'sbh' => '5dfed1ade92f7fb1',
                    'ms' => '小唐',
                    'time' => '2019-09-27 13:19:18'
                ],
                [
                    'sbh' => '059f7a2ea9a07319',
                    'ms' => '邵琴',
                    'time' => '2019-09-27 14:43:42',
                    'time2' => '2019-09-27 15:19:18'
                ],
                [
                    'sbh' => '469229d2d97e5928',
                    'ms' => '倪晨晔',
                    'time' => '2019-09-27 16:40:30'
                ],
                [
                    'sbh' => '61b5156ec0917d69',
                    'ms' => '蒋颀宽',
                    'time' => '2019-09-27 18:45:30'
                ]
            ];
            return response()->json($entities);
        }
    }

    public function allUserPatrol(Patrol $patrol)
    {
        $patrols = $patrol->select('user_id', 'end_at', 'created_at')->whereBetween('created_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:i:s', time())])->get();
        $info = [];
        foreach ($patrols as $patrol) {
            $data = [
                'name'=> $patrol->user->name,
                'created_at' => $patrol->created_at->toDateTimeString(),
                'end_at' => $patrol->end_at
            ];
            if (!$data['end_at']) {
                array_push($info, $data);
            }
        }
        return response()->json($info);

    }


    // 巡查总量
    public function guiJi(Patrol $patrol, User $user)
    {
        $patrols = Patrol::with('User')->whereBetween('created_at', [date('Y-m-d 00:00:00', time()), date('Y-m-d H:s:i', time())])->get();
        $info = [];
        foreach ($patrols as $patrol) {
            $data['name'] = $patrol->user->name;
            array_push($info, $data['name']);
        }
        $ret = array_count_values($info);

        return response()->json($ret);
    }
}
