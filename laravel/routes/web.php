<?php

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
Route::middleware(['auth.v2'])->group(function() {
    Route::prefix('dacita_dashboard')->group(function(){
        Route::get('/', 'activityController@loginPage')->name('dacita.dashboard');
        Route::get('/detail/{ttc}/lantai/{lantai}', 'activityController@detail')->name('dacita.detail');
    });

    Route::prefix('user_management')->group(function(){
        Route::get('/','settingController@userManageDashboard')->name('user.dashboard');
        Route::get('/user','settingController@user')->name('user.user');
        Route::get('/role','settingController@Role')->name('user.role');
    });

    Route::prefix('gita_dashboard')->group(function(){
        // Route::get('/','gitaController@index')->name('gita.dashboard');
        Route::get('/guest_non_tsel','gitaController@guestNonTsel')->name('gita.guest.non_tsel');
        Route::get('/guest_tsel','gitaController@guestTsel')->name('gita.guest.tsel');
        Route::get('/guest_vip','gitaController@guestVip')->name('gita.guest.vip');
        Route::get('/frontdesk','gitaController@index')->name('gita.frontdesk');
    });

    Route::get('/please-wait', 'loginController@pleaseWait')->name('login.redirect');
    Route::get('/logout','loginController@logout');
});

Route::get('/gita_dashboard','gitaController@index')->name('gita.dashboard');
Route::get('/', 'loginController@loginPage')->name('default');
Route::get('/login', 'loginController@loginPage')->name('login.page');
Route::post('/login','loginController@login')->name('login');