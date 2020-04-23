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
    Route::get('/users', 'User\UserListController@index')->name('users.index');
    Route::get('/users/list', 'User\UserListController@list')->name('user.list');
    Route::get('/users/create', 'User\UserCreateController@create')->name('user.create');
    Route::post('/users/store', 'User\UserCreateController@store')->name('user.store');
    Route::get('/users/edit/{userId}', 'User\UserUpdateController@edit')->name('user.edit');
    Route::post('/users/update/{user}', 'User\UserUpdateController@update')->name('user.update');
    Route::delete('/users/destroy/{user}', 'User\UserDeleteController')->name('user.destroy');
});
