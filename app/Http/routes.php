<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::get('about', 'AboutController@index');

Route::get('profile', 'ProfileController@index');

Route::resource('profile/nickname', 'Profile\NicknameController',
    ['only' => ['index', 'store', 'destroy']]);

Route::resource('profile/signature', 'Profile\SignatureController',
    ['only' => ['index', 'store', 'update', 'destroy']]);

Route::resource('profile/goal', 'Profile\GoalController',
    ['only' => ['index', 'store', 'update']]);

Route::resource('task/family', 'Task\FamilyController',
    ['only' => ['index', 'store', 'update', 'destroy']]);

Route::resource('task/rgtask', 'Task\RegularTaskController',
    ['only' => ['index', 'store', 'update', 'destroy']]);

Route::resource('task/ogtask', 'Task\OngoingTaskController',
    ['only' => ['update', 'destroy']]);

Route::resource('task/tasksign', 'Task\TaskSignController',
    ['only' => ['index', 'create', 'store']]);

Route::get('task/tasksign/check', 'Task\TaskSignController@check');

Route::post('task/tasksign/reset', 'Task\TaskSignController@reset');

Route::resource('essay', 'EssayController',
    ['only' => ['index', 'store']]);

// 认证路由...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// 注册路由...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// 密码重置链接的路由...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// 密码重置的路由...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');