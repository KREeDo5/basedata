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
Route::get('/user', 'MainController@user'); // передаёшь в header id
Route::get('/thread', 'MainController@thread'); // передаёшь в header id
Route::get('/categories', 'MainController@categories');
//Route::get('/categories/{parentCategoryId}', 'MainController@categories');
//Route::get('/threads', 'MainController@allThreads');
Route::get('/threads', 'MainController@categoryThreads'); // можешь передавать в header categoryId

Route::post('/auth', 'MainController@auth_check'); // login, password
Route::post('/registration', 'MainController@registration_check'); // name, login, password
Route::post('/profile/change', 'MainController@changeProfile'); // id, name, description, image_path
//Route::post('/change/profile/image', 'MainController@changeProfileImage');
Route::post('/password/change', 'MainController@changePassword'); // id, password


Route::post('/subscribe', 'MainController@subscribe'); // subscriber(кто), subscription(на кого)
Route::delete('/unsubscribe', 'MainController@unsubscribe'); // subscriber(кто), subscription(от кого)