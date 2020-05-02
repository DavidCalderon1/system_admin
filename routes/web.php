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

Route::group(['prefix' => 'config'], function () {

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

//Terceros CRUD
Route::group(['prefix' => 'thirds'], function () {
    Route::get('/', 'Third\ThirdListController@index')->name('thirds.index');
    Route::get('/list', 'Third\ThirdListController@list')->name('thirds.list');
    Route::get('/filterAllByType/{type}', 'Third\ThirdListController@filterAllByType')->name('thirds.filterAllByType');
    Route::get('/create', 'Third\ThirdCreateController@create')->name('thirds.create');
    Route::post('/store', 'Third\ThirdCreateController@store')->name('thirds.store');
    Route::get('/edit/{thirdId}', 'Third\ThirdEditController@edit')->name('thirds.edit');
    Route::post('/update/{third}', 'Third\ThirdEditController@update')->name('thirds.update');
    Route::delete('/destroy/{third}', 'Third\ThirdDeleteController')->name('thirds.destroy');
});

//Location
Route::group(['prefix' => 'location'], function () {
    Route::get('/countries', 'Location\LocationController@getAllCountries')
        ->name('countries.all');
    Route::get('/statesByCountryCode/{country_code}', 'Location\LocationController@getStatesByCountryCode')
        ->name('states.statesByCountryCode');
    Route::get('/citiesByStateId/{state_id}', 'Location\LocationController@getCitiesByStateId')
        ->name('cities.citiesByStateId');
});

//Inventory
Route::group(['prefix' => 'inventory'], function () {
    //Products Category
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'Inventory\Category\CategoryListController@index')->name('inventory.category.index');
        Route::get('/list', 'Inventory\Category\CategoryListController@list')->name('inventory.category.list');
        Route::get('/all', 'Inventory\Category\CategoryListController@getAllCategories')->name('inventory.category.all');
        Route::get('/create', 'Inventory\Category\CategoryCreateController@create')->name('inventory.category.create');
        Route::post('/store', 'Inventory\Category\CategoryCreateController@store')->name('inventory.category.store');
        Route::get('/edit/{id}', 'Inventory\Category\CategoryEditController@edit')->name('inventory.category.edit');
        Route::post('/update/{category}', 'Inventory\Category\CategoryEditController@update')->name('inventory.category.update');
        Route::delete('/destroy/{id}', 'Inventory\Category\CategoryDeleteController')->name('inventory.category.destroy');
    });

    //Products
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'Inventory\Product\ProductListController@index')->name('inventory.products.index');
        Route::get('/list', 'Inventory\Product\ProductListController@list')->name('inventory.products.list');
        Route::get('/filter', 'Inventory\Product\ProductListController@filter')->name('inventory.products.filter');
        Route::get('/create', 'Inventory\Product\ProductCreateController@create')->name('inventory.products.create');
        Route::post('/store', 'Inventory\Product\ProductCreateController@store')->name('inventory.products.store');
        Route::get('/edit/{id}', 'Inventory\Product\ProductEditController@edit')->name('inventory.products.edit');
        Route::post('/update/{category}', 'Inventory\Product\ProductEditController@update')->name('inventory.products.update');
        Route::delete('/destroy/{id}', 'Inventory\Product\ProductDeleteController')->name('inventory.products.destroy');
    });

    //Invoices
    Route::group(['prefix' => 'invoices'], function () {
        Route::get('/create', 'Inventory\Invoice\InvoiceCreateController@create')->name('inventory.invoices.create');

    });
});

Route::group(['prefix' => 'warehouses'], function () {
    Route::get('/', 'Warehouse\WarehouseListController@index')->name('warehouses.index');
    Route::get('/list', 'Warehouse\WarehouseListController@list')->name('warehouses.list');
    Route::get('/all', 'Warehouse\WarehouseListController@getAllWarehouses')->name('warehouses.all');
    Route::get('/create', 'Warehouse\WarehouseCreateController@create')->name('warehouses.create');
    Route::post('/store', 'Warehouse\WarehouseCreateController@store')->name('warehouses.store');
    Route::get('/edit/{id}', 'Warehouse\WarehouseEditController@edit')->name('warehouses.edit');
    Route::post('/update/{category}', 'Warehouse\WarehouseEditController@update')->name('warehouses.update');
    Route::delete('/destroy/{id}', 'Warehouse\WarehouseDeleteController')->name('warehouses.destroy');
});
