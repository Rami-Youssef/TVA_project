<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrepriseController;

Route::get('/', function () {
    return redirect(route('user.getAllUsers'));
});

Auth::routes();  // This only needs to be called once.

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
    Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
    Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
    Route::get('user/getAllUsers', ['as' => 'user.getAllUsers', 'uses' => 'App\Http\Controllers\UserController@getAllUsers']);
    Route::get('entreprise', ['as' => 'entreprise.getAllEntreprises', 'uses' => 'App\Http\Controllers\EntrepriseController@getAllEntreprises']);
});

Route::group(['middleware' => ['auth', 'AdminPrivilege']], function () {
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
    
    Route::get('user/edit/{user}', ['as' => 'user.edit', 'uses' => 'App\Http\Controllers\UserController@edit']);
    Route::put('user/update/{user}', ['as' => 'user.update', 'uses' => 'App\Http\Controllers\UserController@update']);
    Route::post('user/store', ['as' => 'user.store', 'uses' => 'App\Http\Controllers\UserController@store']);
    Route::get('user/create', ['as' => 'user.create', 'uses' => 'App\Http\Controllers\UserController@create']);

    Route::get('entreprise/create', ['as' => 'entreprise.create', 'uses' => 'App\Http\Controllers\EntrepriseController@create']);
    Route::post('entreprise/store', ['as' => 'entreprise.store', 'uses' => 'App\Http\Controllers\EntrepriseController@store']);
    Route::get('entreprise/edit/{entreprise}', ['as' => 'entreprise.edit', 'uses' => 'App\Http\Controllers\EntrepriseController@edit']);
    Route::put('entreprise/update/{entreprise}', ['as' => 'entreprise.update', 'uses' => 'App\Http\Controllers\EntrepriseController@update']);
});

Route::group(['middleware' => ['auth', 'SuperAdminPrivilege']], function () {
    Route::delete('user/delete/{user}', ['as' => 'user.delete', 'uses' => 'App\Http\Controllers\UserController@destroy']);
    Route::delete('entreprise/delete/{entreprise}', ['as' => 'entreprise.delete', 'uses' => 'App\Http\Controllers\EntrepriseController@destroy']);
});
