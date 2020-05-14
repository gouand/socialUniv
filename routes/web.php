<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', 'WelcomeController');

Auth::routes();


Route::group(['middleware' => 'auth'], function(){

    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('/user/messages','ContactsController@homeindex')->name('user.messages');

    Route::post('/user/addPost', 'PostController@createPost')->name('createPost');
    Route::post('/user/deletePost/{id}', 'PostController@deletePost')->name('deletePost');
    Route::get('/group/{id}', 'GroupController@getGroup')->name('groups.index');
    Route::post('/group/addPost/{id}', 'GroupController@createPost')->name('group.createPost');
    Route::get('/groups', 'GroupController@index')->name('groups.home');
    Route::get('profile','ProfileController')->name('profile.home');
    Route::post('/profile/createPost', 'ProfileController@createPost')->name('profile.createPost');
    Route::get('/users','UserController')->name('user.home');
    Route::get('/user/{id}','ProfileController@getUserProfile')->name('user.index');
    Route::post('/user/addFriend/{id}', 'UserController@addFriend')->name('user.addFriend');
    Route::post('/user/removeFriend/{id}','UserController@removeFriend')->name('user.removeFriend');
    Route::get('/profile/friends', 'UserController@getFriends')->name('user.getFriends');
    Route::post('/post/addLike', 'PostController@addLike')->name('like.add');
    Route::post('/post/edit/{id}', 'PostController@editPost')->name('post.edit');
    Route::post('/group/add', 'UserController@addGroup')->name('addGroup');
});

