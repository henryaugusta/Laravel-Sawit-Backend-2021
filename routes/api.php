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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', 'CustomAuthController@login');
    Route::post('/logout', 'CustomAuthController@logout');
    Route::post('/refresh', 'CustomAuthController@refresh');
    Route::get('/user-profile', 'CustomAuthController@me');
});

Route::post('auth/register', 'CustomAuthController@register');

Route::group(['middleware' => ['auth:api']], function () {
    Route::prefix('user')->group(function () {
        Route::post('/update-photo', 'StaffController@updateProfilePhoto');
        Route::post('/update-data', 'StaffController@update');
        Route::post('/change-password', 'StaffController@updatePassword');
    });

    Route::prefix('armada')->group(function () {
        Route::get('/get', 'ArmadaController@get');
    });

    Route::prefix('request-sell')->group(function () {
        Route::post('/store', 'RequestSellController@store');
        Route::get('/{id}/detail', 'RequestSellController@viewDetail');
    });


    Route::post('save-user', 'UserController@saveUser');
    Route::put('edit-user', 'UserController@editUser');
});
Route::prefix('price')->group(function () {
    Route::get('/', 'PriceController@getAll');
});

Route::prefix('news')->group(function () {
    Route::get('/get', 'NewsController@get');
});

Route::prefix('user')->group(function () {
    Route::get('{id}/request-sell', 'RequestSellController@getByUser');
});

