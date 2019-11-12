<?php

namespace App\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JPush\Client as JPushClient;

class JPushHandler
{
    protected $appKey;

    protected $masterSecret;

    public function __construct()
    {
        $this->appKey = "a9130ea0662310ca82d5eeb7";
        $this->masterSecret = "cceb7c94a2b6263ec41c9180";
    }

    public function androidPushByAlias($params)
    {
        // 推送平台
        $platform = array_get($params, 'platform');
        // 推送标题
        $title = array_get($params, 'title');
        // 推送内容
        $content = array_get($params, 'content');
        // 通知栏样式 ID
        $builderId = array_get($params, 'builderId');
        // 附加字段 (可用于给前端返回，进行其他业务操作，例如：返回orderId，用于点击通知后跳转到订单详情页面)
        $extras = array_get($params, 'extras');
        // 推送目标 (别名)
        $alias = array_get($params, 'alias');
        // 推送目标 (注册ID)
        $registrationId = array_get($params, 'registrationId');
        // 推送类型 (1-别名 2-注册id 3-全部(ios 或 android))
        $type = array_get($params, 'type');

        $jpush = new JPushClient($this->appKey, $this->masterSecret);
        $push = $jpush->push();
        $push->setPlatform($platform);
        switch ($type) {
            // 通过别名推送
            case 1:
                $push->addAlias($alias);
                break;
            // 通过注册 ID 推送
            case 2:
                $push->addRegistrationId($registrationId);
                break;
            // 推送全部(android 或 ios)
            case 3:
                $push->addAllAudience();
                break;
        }
        $push->androidNotification($content, [
            "title" => $title,
            "builder_id" => $builderId,
            "extras" => $extras,
        ])->options([
            'apns_production' => config('Jpush.environment')
        ]);

        $response = $push->send();

        if ($response['http_code'] != 200) {
            Log::info('推送失败 by alias',
                compact('response', 'type', 'platform', 'alias', 'registrationId', 'title', 'content'));
        }

        return $response;
    }

    public function testJpush($reg_id)
    {
        // 推送平台 ios android
        $params['platform'] = 'android';
        // 推送标题
        $params['title'] = '通知！';
        // 推送内容
        $params['content'] = '您有一条新任务';
        // 通知栏样式 ID
        $params['builderId'] = 1;
        // 附加字段（这里自定义 Key / value 信息，以供业务使用）
        $params['extras'] = [
            'time' => date('Y-m-d H:i:s', time()),
        ];
        // 推送类型 1-别名 2-注册id 3-全部
        $params['type'] = 2;
        $params['registrationId'] = $reg_id;
        $data = $this->androidPushByAlias($params);

        return $data;

    }


}
