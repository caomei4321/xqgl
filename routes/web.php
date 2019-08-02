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

        Route::get('repair', 'Admin\RepairsController@index')->name('admin.repairs.index');
        Route::get('repair/{repair}', 'Admin\RepairsController@show')->name('admin.repairs.show');
        Route::delete('repair/{repair}', 'Admin\RepairsController@destroy')->name('admin.repairs.destroy');

    });

});
