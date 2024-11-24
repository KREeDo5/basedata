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

Route::get('/', 'MainController@home');
Route::get('/user/{id}', 'MainController@user');
Route::get('/thread/{id}', 'MainController@thread');

Route::get('/auth', 'MainController@auth');
Route::post('/auth/check', 'MainController@auth_check');

Route::get('/registration', 'MainController@registration');
Route::post('/registration/check', 'MainController@registration_check');
