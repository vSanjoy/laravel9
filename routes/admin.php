<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\AccountController;

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

       
    


    Route::group(['middleware' => 'backend'], function () {
        Route::controller(AccountController::class)->group(function() {
            Route::name('account.')->group(function () {
                Route::get('/dashboard', 'dashboard')->name('dashboard');
                Route::get('/profile', 'profile')->name('profile');
                Route::patch('/profile', 'profile');
                Route::get('/change-password', 'changePassword')->name('change-password');
                Route::patch('/change-password', 'change-password');
                Route::get('/settings', 'settings')->name('settings');
                Route::patch('/settings', 'settings');
            });

            
            
        });
        
        Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        
        

        
        // Route::any('/profile', 'AccountController@profile')->name('profile');
        // Route::post('/account/delete-uploaded-image', 'AccountController@deleteUploadedImage')->name('delete-uploaded-image');
        // Route::any('/change-password', 'AccountController@changePassword')->name('change-password');
        // Route::any('/generate-slug', 'AccountController@generateSlug')->name('generate-slug');
        

        
    });

});


