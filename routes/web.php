<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('/login');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::group(['prefix' => 'admin'], function () {
    //Users CRUD
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'User\UserListController@index')->name('users.index');
        Route::get('/list', 'User\UserListController@list')->name('user.list');
        Route::get('/create', 'User\UserCreateController@create')->name('user.create');
        Route::post('/store', 'User\UserCreateController@store')->name('user.store');
        Route::get('/edit/{userId}', 'User\UserUpdateController@edit')->name('user.edit');
        Route::post('/update/{user}', 'User\UserUpdateController@update')->name('user.update');
        Route::delete('/destroy/{user}', 'User\UserDeleteController')->name('user.destroy');
    });

    //Roles CRUD
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'Role\RoleListController@index')->name('roles.index');
        Route::get('/list', 'Role\RoleListController@list')->name('roles.list');
        Route::get('/create', 'Role\RoleCreateController@create')->name('roles.create');
        Route::post('/store', 'Role\RoleCreateController@store')->name('roles.store');
        Route::get('/edit/{roleId}', 'Role\RoleUpdateController@edit')->name('roles.edit');
        Route::post('/update/{role}', 'Role\RoleUpdateController@update')->name('roles.update');
        Route::delete('/destroy/{role}', 'Role\RoleDeleteController')->name('roles.destroy');
    });
});
