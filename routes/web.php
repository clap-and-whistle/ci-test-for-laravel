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

/**
 * 認証不要なページ群
 */
use App\Http\Controllers\Open\HomeController;
use App\Http\Controllers\Uam\UserOperation\LoginController;

Route::namespace('OpenWeb')->group(function () {
    Route::get('/', [HomeController::class, 'indexAction'])->name('SiteTop');
    Route::get('/logout/', [LoginController::class, 'logoutAction']);
    Route::get('/e', [HomeController::class, 'ErrorAction'])->name('FallbackAll');
});

/**
 * ユーザによるアカウント操作系のページ群
 */
use App\Http\Controllers\Uam\UserOperation\CreateAccountController;

Route::prefix('uam')->group(function () {
    Route::get('/create-account/new', [CreateAccountController::class, 'newAction']);
    Route::post('/create-account/', [CreateAccountController::class, 'storeAction']);
    Route::get('/login/input', [LoginController::class, 'inputAction'])->name('login');
    Route::post('/login/', [LoginController::class, 'execAction']);
});

use App\Http\Controllers\Desk\MyWorkController;

Route::prefix('desk')->name('desk.')->group(function () {
        Route::get('/index', [MyWorkController::class, 'indexAction'])
            ->middleware('auth');
});
