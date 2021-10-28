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
use App\Http\Controllers\Uam\SysAdminOperation\LoginController as SysAdminLoginController;

Route::namespace('OpenWeb')->group(function () {
    Route::get('/', [HomeController::class, 'indexAction'])->name('SiteTop');
    Route::get('/logout/', [LoginController::class, 'logoutAction']);
    Route::get('/admin-logout/', [SysAdminLoginController::class, 'logoutAction']);
    Route::get('/e', [HomeController::class, 'ErrorAction'])->name('FallbackAll');
});

/**
 * ユーザによるアカウント操作系のページ群
 */
use App\Http\Controllers\Uam\UserOperation\CreateAccountController;

Route::prefix('uam')->group(function () {
    Route::get('/create-account/new', [CreateAccountController::class, 'newAction']);
    Route::post('/create-account/', [CreateAccountController::class, 'storeAction']);

    Route::get('/login/input', [LoginController::class, 'inputAction'])
        ->name(LoginController::URL_ROUTE_NAME_INPUT_ACTION);
    Route::post('/login/', [LoginController::class, 'execAction']);

    Route::get('/login/admin/input', [SysAdminLoginController::class, 'inputAction'])
        ->name(SysAdminLoginController::URL_ROUTE_NAME_INPUT_ACTION);
    Route::post('/login/admin', [SysAdminLoginController::class, 'execAction']);
});

use App\Http\Controllers\Desk\MyWorkController;

Route::middleware(['auth:user'])
    ->prefix('desk')
    ->name(MyWorkController::URL_ROUTE_NAME)
    ->group(function () {
        Route::get('index', [MyWorkController::class, 'indexAction']);
    });

use App\Http\Controllers\Admin\SystemConsoleController;
Route::middleware(['auth:admin'])
    ->prefix('admin')
    ->name(SystemConsoleController::URL_ROUTE_NAME)
    ->group(function () {
        Route::get('index', [SystemConsoleController::class, 'indexAction']);
    });

