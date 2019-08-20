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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {

    Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.index');
    Route::post('login', 'Admin\Auth\LoginController@login')->name('admin.login');
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/', 'Admin\IndexController@index');

        Route::get('user', 'Admin\UsersController@index')->name('admin.users.index');
        Route::get('user/{user}/edit', 'Admin\UsersController@edit')->name('admin.users.edit');
        Route::delete('user/{user}', 'Admin\UsersController@destroy')->name('admin.users.destroy');
        Route::post('user', 'Admin\UsersController@store')->name('admin.users.store');
        Route::put('user/{user}', 'Admin\UsersController@update')->name('admin.users.update');
        Route::get('user/create', 'Admin\UsersController@create')->name('admin.users.create');

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

        Route::resource('categories', 'Admin\CategoriesController', ['except' => ['show']])->names([
            'index'     =>  'admin.categories.index',
            'create'    =>  'admin.categories.create',
            'store'     =>  'admin.categories.store',
            'edit'      =>  'admin.categories.edit',
            'update'    =>  'admin.categories.update',
            'destroy'   =>  'admin.categories.destroy',
        ]);

        Route::resource('responsibility', 'Admin\ResponsibilityController', ['except' => ['show']])->names([
            'index'     =>  'admin.responsibility.index',
            'create'    =>  'admin.responsibility.create',
            'store'     =>  'admin.responsibility.store',
            'edit'      =>  'admin.responsibility.edit',
            'update'    =>  'admin.responsibility.update',
            'destroy'   =>  'admin.responsibility.destroy',
        ]);

        Route::resource('tasks', 'Admin\TasksController')->names([
            'index'     =>  'admin.tasks.index',
            'create'    =>  'admin.tasks.create',
            'store'     =>  'admin.tasks.store',
            'edit'      =>  'admin.tasks.edit',
            'update'    =>  'admin.tasks.update',
            'show'      =>  'admin.tasks.show',
            'destroy'   =>  'admin.tasks.destroy',
        ]);
    });

});
