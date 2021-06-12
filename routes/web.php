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

Route::namespace('OpenWeb')->group(function () {
    Route::get('/', [HomeController::class, 'indexAction'])->name('SiteTop');
    Route::get('/e', [HomeController::class, 'ErrorAction'])->name('FallbackAll');
});

/**
 * ユーザによるアカウント操作系のページ群
 */
use App\Http\Controllers\UserOperation\CreateAccountController;

Route::prefix('uam')->group(function () {

        Route::get('/create-account/new', [CreateAccountController::class, 'newAction']);
});
