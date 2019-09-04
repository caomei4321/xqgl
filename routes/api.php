<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {
    $api->get('version',function () {
        return response('this is version v1');
    });

    // 登录
    $api->post('authorizations', 'AuthorizationsController@store')
        ->name('api.authorizations.store');
    // 小程序登录
    $api->post('weappAuthorizations', 'AuthorizationsController@weappStore');
    // 刷新token
    $api->put('authorizations/current', 'AuthorizationsController@update')
        ->name('api.authorizations.update');
    // 删除token
    $api->delete('authorizations/destroy', 'AuthorizationsController@delete')
        ->name('api.authorizations.delete');

    $api->post('wuthorizations', 'AuthorizationsController@weappStore');

    $api->group(['middleware' => 'auth:api'], function ($api) {

        $api->get('user', 'RepairsController@thisUser');

        $api->get('repairs', 'RepairsController@repairs');  // 报修列表

        $api->post('eventReport', 'RepairsController@eventReport');  //报修

        $api->get('repairDetail/{id}', 'RepairsController@repairDetail');  // 报修详情

        $api->post('completeRepair', 'RepairsController@completeRepair'); //完成报修

        $api->get('userHasMatters', 'MattersController@userHasMatters'); //用户任务

        $api->get('userCompleteMatters', 'MattersController@userCompleteMatters'); //用户任务

        $api->get('matter', 'MattersController@matter');   //任务详情

        $api->get('categories', 'CategoryController@categories');  //责任清单分类

        $api->post('endMatter', 'MattersController@endImportMatter');   //完成12345任务

        $api->post('importMatter', 'MattersController@findMatterAndEnd');  //发现并提交问题


        $api->post('startAndEndPatrol', 'PatrolController@startAndEndPatrol');  //开始和结束巡逻

    });

});
