<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'MainController@home');
Route::get('/user', 'MainController@user'); // header(id)
Route::get('/thread', 'MainController@thread'); // header(id)
Route::get('/categories', 'MainController@categories');
Route::get('/threads', 'MainController@categoryThreads'); // header(?categoryId?) // если не указать categoryId, выдаст все треды

Route::post('/auth', 'MainController@auth_check'); // body(login, password)
Route::post('/registration', 'MainController@registration_check'); // body(name, login, password)
Route::post('/change/profile', 'MainController@changeProfile'); // body(id, name, description, image)
Route::post('/change/password', 'MainController@changePassword'); // body(id, oldPassword, newPassword)
Route::delete('/delete/profilePicture', 'MainController@deleteProfilePicture'); // body(id)

Route::post('/subscribe', 'MainController@subscribe'); // body(subscriber(кто), subscription(на кого))
Route::delete('/unsubscribe', 'MainController@unsubscribe'); // body(subscriber(кто), subscription(от кого))

Route::post('create/category', 'MainController@createCategory'); // body(title, parentCategoryId) // если надо создать главную категорию, parentCategoryId = null
Route::post('create/thread', 'MainController@createThread'); // body(categoryId, userId, title, text, threadImages[])
Route::post('create/message', 'MainController@createMessage'); // body(threadId, userId, text, messageImages[])

Route::post('/closeThread', 'MainController@closeThread'); // body(userId, threadId)

Route::post('/delete/thread', 'MainController@deleteThread'); // body(userId, threadId)
Route::post('/delete/message', 'MainController@deleteMessage'); // body(userId, messageId)

Route::post('/giveRole', 'MainController@giveRole'); // body(giverId, takerId, roleId)