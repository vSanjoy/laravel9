<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\admin\CmsController;

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
        Route::name('auth.')->group(function () {
            Route::get('/', 'login')->name('login');
            Route::patch('/', 'login')->name('login');
            Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
            Route::patch('/forgot-password', 'forgotPassword')->name('forgot-password');
        });


        // Route::any('/', ['as'=>'auth.login', 'uses'=>'index']);
        // Route::any('/forgot-password', ['as'=>'auth.forgot-password', 'uses' => 'forgotPassword']);

        // Route::get('/', 'login')->name('login');
    });

       
    


    Route::group(['middleware' => 'backend'], function () {
        Route::controller(AccountController::class)->group(function() {
            Route::name('account.')->group(function () {
                Route::get('/dashboard', 'dashboard')->name('dashboard');
                Route::get('/profile', 'profile')->name('profile');
                Route::patch('/profile', 'profile');
                Route::get('/change-password', 'changePassword')->name('change-password');
                Route::patch('/change-password', 'changePassword');
                Route::get('/settings', 'settings')->name('settings');
                Route::patch('/settings', 'settings');
            });
        });
        
        Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        
        Route::group(['middleware' => 'admin'], function () {
            Route::controller(CmsController::class)->group(function() {


                Route::prefix('cms')->name('cms.')->group(function () {
                    Route::get('/list', 'list')->name('list');
                    Route::get('/add', 'add')->name('add');
                    Route::post('/add', 'add');
                    
                });
            });
        });

        
    });

});


