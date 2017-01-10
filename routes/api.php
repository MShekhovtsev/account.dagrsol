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

Route::get('{model}/get', 'RestfulController@get')->name('api.get');
Route::get('{model}/find/{id}', 'RestfulController@get')->name('api.find');
Route::post('{model}/filter', 'RestfulController@filter')->name('api.filter');
Route::post('{model}/create', 'RestfulController@create')->name('api.create');
Route::put('{model}/update', 'RestfulController@update')->name('api.update');
Route::delete('{model}/delete/{ids}', 'RestfulController@delete')->name('api.delete');

Route::bind('model', function($model){
    $class = 'App\Models\\' . ucfirst($model);
    if(class_exists($class)){
        return new $class;
    }
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException($class);
});