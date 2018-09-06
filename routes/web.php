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