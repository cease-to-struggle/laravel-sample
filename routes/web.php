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

Route::get('/',['as'=>'home','uses'=>'Pages\StaticPagesController@home']);
Route::get('/help',['as'=>'help','uses'=>'Pages\StaticPagesController@help']);
Route::get('/about',['as'=>'about','uses'=>'Pages\StaticPagesController@about']);


Route::get('signup',['as'=>'signup','uses'=>'UsersController@create']);
//route解析是时候从前到后查找，找到第一个就会终止，所以容易引起歧义的需要放在前面
//forexample  /users/create 和 /users/{id}  如果create放后面则解析时会使用/users/{id}  
//create被当做参数报错
Route::resource('users','UsersController');
Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');


Route::resource('statuses','StatusesController',['only'=>['store','destroy']]);


