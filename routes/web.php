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
Auth::routes();
Route::get('/', function () {
    Debugbar::addMessage('route-message', 'ini lagi di web:route');
    return view('welcome');
});
Route::post('/', 'ProductController@storeData');
