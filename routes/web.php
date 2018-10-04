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
use App\User;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('conversations', 'ConversationsController');
Route::resource('messages', 'MessagesController');


Route::get('ajax', 'ConversationsController@ajax')->name('ajax');


Route::get('/sendchat', function(){
    $users = User::all();
    return view('chat.sendchat')->with(compact('users'));
});
