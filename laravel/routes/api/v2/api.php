<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:api'])->group(function() {
    Route::prefix('user')->group( function() {
        // Seting User
        Route::get('/get','settingController@getAllUser');
        Route::get('/lokasi','settingController@getListLokasiTTC');
        Route::post('/','settingController@insertUser');
        Route::delete('/{id}','settingController@deleteUser')->name('user.delete');
        Route::put('/','settingController@updateUser');
        Route::post('/ldap','settingController@getDataFromLDAP');

        Route::get('/chartHis', 'activityController@getChartHistory');
        Route::get('/alarmAct', 'activityController@getAlarmActive');
        Route::get('/sumPerDataCenter', 'activityController@getSensorSummaryPerDatacenter');
        Route::get('/assetHeader', 'activityController@getAssetHeader');
        Route::get('/getSensor', 'activityController@getSensor');
        Route::get('/getAssetPerlevel', 'activityController@getAssetPerlevel');
        Route::get('/getAssetRak', 'activityController@getAssetRak');
        Route::get('/getSensorRak', 'activityController@getSensorRak');
        Route::get('/role/get', 'settingController@getAlldataUserRole');
        Route::post('/sensorPerCategory', 'activityController@getSensorDashboardPercategory');
        
    });

    Route::prefix('role')->group( function() {
        Route::get('/get', 'settingController@getAlldataRole');
    });

    Route::prefix('menu')->group( function() {
        Route::get('/get', 'settingController@getAlldataMenu');
    });
});