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
     * Team
     */
    Route::prefix('team')->group(function () {
        Route::post('list', [TeamController::class, 'list'])->name('team.list');

        Route::middleware('can:team.manage')->group(function () {
            Route::get('list', [TeamController::class, 'list'])->name('team.list');
            Route::match(['get', 'post'], 'create', [TeamController::class, 'create'])->name('team.create');
            Route::match(['get', 'post'], 'update/{id}', [TeamController::class, 'update'])->name('team.update');
            Route::delete('/', [TeamController::class, 'delete'])->name('team.delete');
        });
    });

    /**
     * Role
     */
    Route::prefix('role')->middleware('can:role.manage')->group(function () {
        Route::get('list', [RoleController::class, 'list'])->name('role.list');
        Route::match(['get', 'post'], 'create', [RoleController::class, 'create'])->name('role.create');
        Route::match(['get', 'post'], 'update/{id}/{tab?}', [RoleController::class, 'update'])->name('role.update');
        Route::delete('/', [RoleController::class, 'delete'])->name('role.delete');
    });

    /**
     * User
     */
    Route::prefix('user')->group(function () {
        Route::match(['get', 'post'], 'account', [UserController::class, 'account'])->name('user.account');
        Route::post('list', [UserController::class, 'list'])->name('user.list');

        Route::middleware('can:user.manage')->group(function () {
            Route::get('list', [UserController::class, 'list'])->name('user.list');
            Route::match(['get', 'post'], 'create', [UserController::class, 'create'])->name('user.create');
            Route::match(['get', 'post'], 'update/{id}', [UserController::class, 'update'])->name('user.update');
            Route::post('invite/{id}', [UserController::class, 'invite'])->name('user.invite');
            Route::delete('/', [UserController::class, 'delete'])->name('user.delete');
        });
    });

    
    /**
     * File
     */
    Route::prefix('file')->group(function () {
        Route::match(['get', 'post'], 'list', [FileController::class, 'list'])->name('file.list');
        Route::post('upload', [FileController::class, 'upload'])->name('file.upload');
        Route::post('store/{id}', [FileController::class, 'store'])->name('file.store');
        Route::delete('/', [FileController::class, 'delete'])->name('file.delete');
    });
});

/**
 * Web routes
 */
Route::get('{any?}', [PageController::class, 'show'])->where(['any' => '.*'])->name('page.show');
