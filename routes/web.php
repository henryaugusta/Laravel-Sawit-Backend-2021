<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SummerNoteController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::view('/template/home', 'template');
Route::view('/download/', 'download');

Auth::routes();


Route::redirect('/', '/login');

Route::get('/registerz', 'CustomAuthController@register');
Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/admin', [App\Http\Controllers\HomeController::class, 'admin']);
    Route::get('/staff', [App\Http\Controllers\HomeController::class, 'index']);


    Route::post('/user/store', [App\Http\Controllers\StaffController::class, 'store']);
    Route::post('/user/update', [App\Http\Controllers\StaffController::class, 'update']);
    Route::get('/user/{id}/delete', [App\Http\Controllers\StaffController::class, 'destroy']);





    Route::get('cust/my-cmc-request', [App\Http\Controllers\NewCMCController::class, 'myRequestView']);
    Route::get('commercial/my-cmc-request', [App\Http\Controllers\NewCMCController::class, 'commercialRequestView']);
    Route::get('commercial/proc-my-cmc-request', [App\Http\Controllers\NewCMCController::class, 'commercialPOInputtedView']);
    Route::get('warehouse/my-cmc-request', [App\Http\Controllers\NewCMCController::class, 'warehouseRequestView']);
    Route::get('warehouse/proc-my-cmc-request', [App\Http\Controllers\NewCMCController::class, 'warehouseInputtedView']);


    Route::prefix('cmc')->group(function () {
        Route::get('create-new', 'NewCMCController@newRequest');
        Route::post('store-new', 'NewCMCController@storeNewRequest')->name("new_po_request.store");
        Route::get('{id}/edit', [App\Http\Controllers\NewCMCController::class, 'editCMCView']);
        Route::post('{id}/update', [App\Http\Controllers\NewCMCController::class, 'updateRequest'])->name("new_po_request.update");
        Route::get('{id}/cancel', 'OutbondController@cancelKeluar');
        Route::post('/update', 'OutbondController@update');
        Route::get('{id}/delete', 'OutbondController@destroy');
        Route::get('manage', 'OutbondController@viewManage');
    });

    Route::prefix('armada')->group(function () {
        $cr = "ArmadaController";
        Route::get('create', "$cr@viewCreate");
        Route::post('store', "$cr@store");
        Route::get('{id}/edit', "$cr@viewUpdate");
        Route::post('{id}/update', "$cr@update");
        Route::get('{id}/delete', "$cr@delete");
        Route::get('manage', "$cr@viewManage");
    });


    Route::prefix('news')->group(function () {
        $cr = "NewsController";
        Route::get('create', "$cr@viewCreate");
        Route::post('store', "$cr@store");
        Route::get('{id}/edit', "$cr@viewUpdate");
        Route::post('{id}/update', "$cr@update");
        Route::get('{id}/delete', "$cr@delete");
        Route::get('manage', "$cr@viewManage");
    });

    Route::get('/admin/user/create', [App\Http\Controllers\StaffController::class, 'viewAdminCreate']);
    Route::get('/admin/user/manage', [App\Http\Controllers\StaffController::class, 'viewAdminManage']);
    Route::get('/admin/user/{id}/edit', [App\Http\Controllers\StaffController::class, 'viewAdminEdit']);
    Route::get('my-profile', [App\Http\Controllers\StaffController::class, 'viewMyProfile']);
});

Route::get('logout', function () {
    auth()->logout();
    Session()->flush();

    return Redirect::to('/');
})->name('logout');

Route::get('mobile_raz/request-sell/{id}/edit/', "RequestSellController@viewDetail");

