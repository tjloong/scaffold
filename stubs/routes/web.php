<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PageController;

/**
 * App routes
 */
Route::prefix('app')->middleware('auth')->group(function () {
    /**
     * Dashboard
     */
    Route::inertia('/', 'dashboard')->name('dashboard');

    /**
     * Settings
     */
    Route::prefix('settings')->group(function () {
        /**
         * User settings
         */
        Route::prefix('user')->group(function () {
            Route::match(['get', 'post'], 'account', [UserController::class, 'account'])->name('settings-user.account');
            Route::post('list', [UserController::class, 'list'])->name('settings-user.list');
    
            Route::middleware('can:settings-user.manage')->group(function () {
                Route::get('list', [UserController::class, 'list'])->name('settings-user.list');
                Route::get('create', [UserController::class, 'create'])->name('settings-user.create');
                Route::get('edit/{id}', [UserController::class, 'edit'])->name('settings-user.edit');
                Route::post('invite/{id}', [UserController::class, 'invite'])->name('settings-user.invite');
                Route::post('store/{id?}', [UserController::class, 'store'])->name('settings-user.store');
                Route::delete('/', [UserController::class, 'delete'])->name('settings-user.delete');
            });
        });
    
        /**
         * Role settings
         */
        Route::prefix('role')->middleware('can:settings-role.manage')->group(function () {
            Route::get('list', [RoleController::class, 'list'])->name('settings-role.list');
            Route::get('create', [RoleController::class, 'create'])->name('settings-role.create');
            Route::get('edit/{id}/{tab?}', [RoleController::class, 'edit'])->name('settings-role.edit');
            Route::post('store/{id?}', [RoleController::class, 'store'])->name('settings-role.store');
            Route::delete('/', [RoleController::class, 'delete'])->name('settings-role.delete');
        });
    
        /**
         * Team settings
         */
        Route::prefix('team')->group(function () {
            Route::post('list', [TeamController::class, 'list'])->name('settings-team.list');
    
            Route::middleware('can:settings-team.manage')->group(function () {
                Route::get('list', [TeamController::class, 'list'])->name('settings-team.list');
                Route::get('create', [TeamController::class, 'create'])->name('settings-team.create');
                Route::get('edit/{id}', [TeamController::class, 'edit'])->name('settings-team.edit');
                Route::post('store/{id?}', [TeamController::class, 'store'])->name('settings-team.store');
                Route::delete('/', [TeamController::class, 'delete'])->name('settings-team.delete');
            });
        });

        /**
         * File settings
         */
        Route::prefix('file')->group(function () {
            Route::match(['get', 'post'], 'list', [FileController::class, 'list'])->name('settings-file.list');
            Route::post('upload', [FileController::class, 'upload'])->name('settings-file.upload');
            Route::post('store/{id}', [FileController::class, 'store'])->name('settings-file.store');
            Route::delete('/', [FileController::class, 'delete'])->name('settings-file.delete');
        });
    });
});

/**
 * Web routes
 */
Route::get('{any?}', [PageController::class, 'show'])->where(['any' => '.*'])->name('page.show');
