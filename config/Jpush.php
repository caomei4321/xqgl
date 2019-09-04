<?php

return [
    'appKey' => env('JPUSH_APP_KEY'),
    'masterSecret' => env('JPUSH_MASTER_SECRET'),

    // 环境 true-生产环境 false-开发环境
    'environment' => env('JPUSH_APNS_PRODUCTION', true),
];
