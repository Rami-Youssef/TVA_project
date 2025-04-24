<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\CnssController;
use App\Http\Controllers\EtatsController;
use App\Http\Controllers\SuiviController;

Route::get('/', function () {
    return redirect(route('user.getAllUsers'));
});

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
    Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
    Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
    Route::get('user/getAllUsers', ['as' => 'user.getAllUsers', 'uses' => 'App\Http\Controllers\UserController@getAllUsers']);
    Route::get('entreprise', ['as' => 'entreprise.getAllEntreprises', 'uses' => 'App\Http\Controllers\EntrepriseController@getAllEntreprises']);
    
    // TVA Declarations view routes - accessible to all authenticated users
    Route::get('tva-mensuelle', ['as' => 'tva-declaration.mensuelle', 'uses' => 'App\Http\Controllers\TvaDeclarationController@getMensuelle']);
    Route::get('tva-trimestrielle', ['as' => 'tva-declaration.trimestrielle', 'uses' => 'App\Http\Controllers\TvaDeclarationController@getTrimestrielle']);
    Route::get('tva-annuelle', ['as' => 'tva-declaration.annuelle', 'uses' => 'App\Http\Controllers\TvaDeclarationController@getAnnuelle']);

    // CNSS Routes
    Route::get('cnss', [CnssController::class, 'index'])->name('cnss.index');
    Route::get('cnss/create', [CnssController::class, 'create'])->name('cnss.create');
    Route::post('cnss', [CnssController::class, 'store'])->name('cnss.store');
    Route::get('cnss/{cnss}/edit', [CnssController::class, 'edit'])->name('cnss.edit');
    Route::put('cnss/{cnss}', [CnssController::class, 'update'])->name('cnss.update');
    Route::delete('cnss/{cnss}', [CnssController::class, 'destroy'])->name('cnss.delete');
    
    // Etats and Suivi Routes
    Route::get('etats', [EtatsController::class, 'index'])->name('etats.index');
    Route::get('suivi', [SuiviController::class, 'index'])->name('suivi.index');
    Route::get('suivi/{entreprise}', [SuiviController::class, 'show'])->name('suivi.show');
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

    // TVA Declaration management routes - only for admins
    Route::get('tva-declaration/create', ['as' => 'tva-declaration.create', 'uses' => 'App\Http\Controllers\TvaDeclarationController@create']);
    Route::post('tva-declaration/store', ['as' => 'tva-declaration.store', 'uses' => 'App\Http\Controllers\TvaDeclarationController@store']);
    Route::get('tva-declaration/edit/{tvaDeclaration}', ['as' => 'tva-declaration.edit', 'uses' => 'App\Http\Controllers\TvaDeclarationController@edit']);
    Route::put('tva-declaration/update/{tvaDeclaration}', ['as' => 'tva-declaration.update', 'uses' => 'App\Http\Controllers\TvaDeclarationController@update']);
    Route::get('tva-declaration', ['as' => 'tva-declaration.getAllDeclarations', 'uses' => 'App\Http\Controllers\TvaDeclarationController@getAllDeclarations']);
});

Route::group(['middleware' => ['auth', 'SuperAdminPrivilege']], function () {
    Route::delete('user/delete/{user}', ['as' => 'user.delete', 'uses' => 'App\Http\Controllers\UserController@destroy']);
    Route::delete('entreprise/delete/{entreprise}', ['as' => 'entreprise.delete', 'uses' => 'App\Http\Controllers\EntrepriseController@destroy']);
    Route::delete('tva-declaration/delete/{tvaDeclaration}', ['as' => 'tva-declaration.delete', 'uses' => 'App\Http\Controllers\TvaDeclarationController@destroy']);
});
