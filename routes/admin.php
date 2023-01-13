<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\admin\AuthController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace'=>'admin', 'prefix'=>'adminpanel', 'as'=>'admin.'], function() {

    // Route::get('/', ['as'=>'login', 'uses'=>'AuthController@login']);

    // Route::get('books',['as'=>'books.index','uses'=>'BOOKController@index']);
    // Route::post('books/create',['as'=>'books.store','uses'=>'BOOKController@store']);
    // Route::get('books/edit/{id}',['as'=>'books.edit','uses'=>'BOOKController@edit']);
    // Route::patch('books/{id}',['as'=>'books.update','uses'=>'BOOKController@update']);
    // Route::delete('books/{id}',['as'=>'books.destroy','uses'=>'BOOKController@destroy']);
    // Route::get('books/{id}',['as'=>'books.view','uses'=>'BOOKController@view']);

    Route::controller(AuthController::class)->group(function() {
        Route::any('/', ['as'=>'auth.login', 'uses'=>'index']);
        Route::any('/forgot-password', ['as'=>'auth.forgot-password', 'uses' => 'forgotPassword']);

        // Route::get('/', 'login')->name('login');
    });

});

