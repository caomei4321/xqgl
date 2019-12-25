<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::redirect('/', 'admin/login' );
Route::get('/', function (){
    return redirect()->route('admin.index');
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {

    Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.index');
    Route::post('login', 'Admin\Auth\LoginController@login')->name('admin.login');
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');

    Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/', 'Admin\IndexController@index');

            Route::group(['middleware' => 'checkPermission'], function () {

            Route::get('user', 'Admin\UsersController@index')->name('admin.users.index');
            Route::get('user/{user}/edit', 'Admin\UsersController@edit')->name('admin.users.edit');
            Route::delete('user/{user}', 'Admin\UsersController@destroy')->name('admin.users.destroy');
            Route::post('user', 'Admin\UsersController@store')->name('admin.users.store');
            Route::put('user/{user}', 'Admin\UsersController@update')->name('admin.users.update');
            Route::get('user/create', 'Admin\UsersController@create')->name('admin.users.create');
            Route::get('user/{user}', 'Admin\UsersController@show')->name('admin.users.show');
            Route::get('users/address', 'Admin\UsersController@address')->name('admin.users.address');

            Route::get('users/ajaxAddress', 'Admin\UsersController@ajaxAddress')->name('admin.users.ajaxAddress');
            Route::post('users/latestPoint', 'Admin\UsersController@latestPoint')->name('admin.users.latestPoint');

            Route::get('users/export', 'Admin\UsersController@export')->name('admin.users.export');

            //Route::get('convenientTask','Admin\ConvenientTaskController@index')->name('admin.convenientTask.index');
            //Route::get('convenientTask/create','Admin\ConvenientTaskController@create')->name('admin.convenientTask.create');

            Route::resource('entities', 'Admin\EntitiesController',  ['except' => ['destroy', 'show', 'create', 'update', 'edit']])->names([
                'index' => 'admin.entities.index',
                'store' => 'admin.entities.store',
                //'create' => 'admin.entities.create',
                //'update' => 'admin.entities.update',
                //'show' => 'admin.entities.show',
                //'edit' => 'admin.entities.edit',
            ]);

            Route::get('entities/{entity_name}/destroy', 'Admin\EntitiesController@destroy')->name('admin.entities.destroy');





            Route::resource('administrators', 'Admin\AdminsController')->names([
                'index' => 'admin.administrators.index',
                'store' => 'admin.administrators.store',
                'create' => 'admin.administrators.create',
                'destroy' => 'admin.administrators.destroy',
                'update' => 'admin.administrators.update',
                'show' => 'admin.administrators.show',
                'edit' => 'admin.administrators.edit',
            ]);
            Route::resource('permissions', 'Admin\PermissionsController')->names([
                'index' => 'admin.permissions.index',
                'store' => 'admin.permissions.store',
                'create' => 'admin.permissions.create',
                'destroy' => 'admin.permissions.destroy',
                'update' => 'admin.permissions.update',
                'show' => 'admin.permissions.show',
                'edit' => 'admin.permissions.edit',
            ]);
            Route::resource('roles', 'Admin\RolesController')->names([
                'index' => 'admin.roles.index',
                'store' => 'admin.roles.store',
                'create' => 'admin.roles.create',
                'destroy' => 'admin.roles.destroy',
                'update' => 'admin.roles.update',
                'show' => 'admin.roles.show',
                'edit' => 'admin.roles.edit',
            ]);
            // 责任类别
            Route::resource('categories', 'Admin\CategoriesController', ['except' => ['show']])->names([
                'index'     =>  'admin.categories.index',
                'create'    =>  'admin.categories.create',
                'store'     =>  'admin.categories.store',
                'edit'      =>  'admin.categories.edit',
                'update'    =>  'admin.categories.update',
                'destroy'   =>  'admin.categories.destroy',
            ]);
            // 责任清单
            Route::resource('responsibility', 'Admin\ResponsibilityController', ['except' => ['show']])->names([
                'index'     =>  'admin.responsibility.index',
                'create'    =>  'admin.responsibility.create',
                'store'     =>  'admin.responsibility.store',
                'edit'      =>  'admin.responsibility.edit',
                'update'    =>  'admin.responsibility.update',
                'destroy'   =>  'admin.responsibility.destroy',
            ]);
            // 任务清单
            Route::resource('matters', 'Admin\MattersController', ['except' => ['show']])->names([
                'index'     =>  'admin.matters.index',
                'create'    =>  'admin.matters.create',
                'store'     =>  'admin.matters.store',
                'edit'      =>  'admin.matters.edit',
                'update'    =>  'admin.matters.update',
                'destroy'   =>  'admin.matters.destroy',
            ]);

            Route::get('people', 'Admin\PeopleController@index')->name('admin.people.index');
            Route::get('people/edit', 'Admin\PeopleController@edit')->name('admin.people.edit');
            Route::post('people/update', 'Admin\PeopleController@update')->name('admin.people.update');
            Route::get('peopleSituation', 'Admin\PeopleController@peopleSituation')->name('admin.people.peopleSituation');
            Route::get('people/open', 'Admin\PeopleController@open')->name('admin.people.open');
            Route::get('people/allocate', 'Admin\PeopleController@allocate')->name('admin.people.allocate');
            Route::post('people/allocates', 'Admin\PeopleController@allocates')->name('admin.people.allocates');
            Route::get('people/export', 'Admin\PeopleController@export')->name('admin.people.export');
            Route::get('people/show', 'Admin\PeopleController@showPeopleSituation')->name('admin.people.show');

            // 公开隐藏
            Route::get('matters/open', 'Admin\MattersController@open')->name('admin.matters.open');
            // 分配
            Route::get('matters/allocate', 'Admin\MattersController@allocate')->name('admin.matters.allocate');
            Route::post('matters/allocates', 'Admin\MattersController@allocates')->name('admin.matters.allocates');

            // 导入导出
            Route::get('matters/export', 'Admin\MattersController@export')->name('admin.matters.export');
            Route::post('matters/import', 'Admin\MattersController@import')->name('admin.matters.import');
            Route::get('matters/download', 'Admin\MattersController@download')->name('admin.matters.download');

            // 鼠标绘制点线面
            Route::get('matters/mouse', 'Admin\MattersController@mouse')->name('admin.matters.mouse');
            Route::post('matters/ajax', 'Admin\MattersController@ajaxData')->name('admin.matters.ajax');

            // 城市部件信息
            Route::resource('part', 'Admin\PartsController', ['except' => ['show']])->names([
                'index'     =>  'admin.part.index',
                'create'    =>  'admin.part.create',
                'store'     =>  'admin.part.store',
                'edit'      =>  'admin.part.edit',
                'update'    =>  'admin.part.update',
                'destroy'   =>  'admin.part.destroy',
            ]);
            Route::get('partInfo', 'Admin\PartsController@mapJsonInfo')->name('admin.part.partInfo');
            // 城市部件地图路由
            Route::get('part/mapInfo', 'Admin\PartsController@mapInfo')->name('admin.part.mapInfo');

            // 任务情况
            Route::resource('situations', 'Admin\SituationsController', ['except' => ['show','create','store','edit', 'update']])->names([
                'index'     =>  'admin.situations.index',
            ]);
            Route::get('showSituation', 'Admin\SituationsController@showSituation')->name('admin.situations.show');
            Route::get('situationsExport', 'Admin\SituationsController@export')->name('admin.situations.export');


            // 网格划分图
            Route::resource('coordinates', 'Admin\CoordinatesController', ['except' => ['edit', 'update']])->names([
                'index'     =>  'admin.coordinates.index',
                'create'    =>  'admin.coordinates.create',
                'store'     =>  'admin.coordinates.store',
                'show'      =>  'admin.coordinates.show',
                'destroy'   =>  'admin.coordinates.destroy',
            ]);
            Route::get('all', 'Admin\CoordinatesController@all')->name('admin.coordinates.all');

            // 巡查上报事件
            Route::resource('patrolMatter', 'Admin\PatrolMattersController', ['except' => ['create', 'store', 'edit', 'update']])->names([
                'index'     =>  'admin.patrolMatters.index',
                'show'      =>  'admin.patrolMatters.show',
                'destroy'   =>  'admin.patrolMatters.destroy',
            ]);
            //巡查发现事件导出
            Route::get('export/patrolMatter', 'Admin\PatrolMattersController@export')->name('admin.patrolMatters.export');
            Route::get('search/patrolMatter', 'Admin\PatrolMattersController@search')->name('admin.patrolMatters.search');

            // 巡查记录
            Route::resource('patrol', 'Admin\PatrolsController', ['except' => ['create', 'store', 'edit', 'update']])->names([
                'index'     =>  'admin.patrols.index',
                'show'      =>  'admin.patrols.show',
                'destroy'   =>  'admin.patrols.destroy',
            ]);
            Route::get('export/patrol', 'Admin\PatrolsController@export')->name('admin.patrols.export');
            Route::get('personExport/patrol', 'Admin\PatrolsController@personExport')->name('admin.patrols.personExport');

            // 分配任务到人
            Route::get('matters/users', 'Admin\MattersController@getUser')->name('admin.matters.users');
            // 此路由为分配到人，表格头的按钮，以注释，后面不需要则删除
            Route::post('matters/mtu', 'Admin\MattersController@mattersToUser')->name('admin.matters.mtu');


            // News
            Route::resource('news', 'Admin\NewsController', ['except' => ['show']])->names([
                'index' => 'admin.news.index',
                'create' => 'admin.news.create',
                'store' => 'admin.news.store',
                'edit' => 'admin.news.edit',
                'update' => 'admin.news.update',
                'destroy' => 'admin.news.destroy',
            ]);
            Route::post('import/news', 'Admin\NewsController@import')->name('admin.news.import');

            // 告警
            Route::get('alarm', 'Admin\AlarmsController@index')->name('admin.alarm.index');
            Route::get('alarm/allocate', 'Admin\AlarmsController@allocate')->name('admin.alarm.allocate');
            Route::post('alarm/allocates', 'Admin\AlarmsController@allocates')->name('admin.alarm.allocates');
            Route::get('alarm/export', 'Admin\AlarmsController@export')->name('admin.alarm.export');
            Route::get('alarmSituation', 'Admin\AlarmsController@alarmSituation')->name('admin.alarm.alarmSituation');
            Route::get('alarm/edit', 'Admin\AlarmsController@edit')->name('admin.alarm.edit');
            Route::post('alarm/update', 'Admin\AlarmsController@update')->name('admin.alarm.update');

            Route::resource('hats', 'Admin\HatsController')->names([
                'index' => 'admin.hats.index',
                'destroy' => 'admin.hats.destroy'
            ]);

            Route::resource('programUser', 'Admin\ProgramUsersController', ['except' => ['create', 'store', 'edit', 'update']])->names([
                'index'     =>  'admin.programUsers.index',
                'show'      =>  'admin.programUsers.show',
                'destroy'   =>  'admin.programUsers.destroy',
            ]);

            Route::resource('programImage', 'Admin\ProgramImagesController', ['except' => ['create', 'show', 'edit', 'update']])->names([
                'index'     =>  'admin.programImages.index',
                'store'     =>  'admin.programImages.store',
                'destroy'   =>  'admin.programImages.destroy',
            ]);
            Route::resource('version', 'Admin\VersionController')->names([
                'index' => 'admin.version.index',
                'store' => 'admin.version.store',
                'create' => 'admin.version.create',
                'destroy' => 'admin.version.destroy',
                'update' => 'admin.version.update',
                'show' => 'admin.version.show',
                'edit' => 'admin.version.edit',
            ]);

        });
        Route::resource('governanceStandard', 'Admin\GovernanceStandardsController')->names([
            'index' => 'admin.governanceStandard.index',
            'store' => 'admin.governanceStandard.store',
            'create' => 'admin.governanceStandard.create',
            'destroy' => 'admin.governanceStandard.destroy',
            'update' => 'admin.governanceStandard.update',
            'show' => 'admin.governanceStandard.show',
            'edit' => 'admin.governanceStandard.edit',
        ]);

        // 统计
        Route::get('count', 'Admin\CountsController@index')->name('admin.counts.index');

        // 导出报表
        Route::get('export', 'Admin\CountsController@export')->name('admin.counts.export');

        Route::get('allUserPatrol', 'Admin\CountsController@allUserPatrol')->name('admin.counts.allUserPatrol');

        Route::get('dataInfo', 'Admin\CountsController@dataInfo')->name('admin.counts.dataInfo');

    });

});
