<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------cls----
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {

    // Admin Dashboard Routes
    Route::middleware('permission:view-admin-dashboard')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard.index');

        Route::prefix('area')->name('area.')->controller(AreaController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/index/ajax', 'indexAjax')->name('index.ajax');
            Route::get('/{area}', 'show')->name('show');
            Route::post('/', 'store')->name('store');
            Route::put('/{area}', 'update')->name('update');
            Route::delete('/{area}', 'destroy')->name('destroy');
        });
    });

    // Agent Dashboard Routes
    Route::middleware('permission:view-agent-dashboard')->name('agent.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    });

    Route::post('logout', LogoutController::class)->name('logout');
});

// Guest Routes like, Login, password_reset etc.
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->prefix('login')->name('login')->group(function () {
        Route::get('/', 'create');
        Route::post('/', 'authenticate')->name('.authenticate');
    });
});
