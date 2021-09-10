<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FileController;

Route::prefix('app')->middleware('auth')->group(function () {
    /**
     * Dashboard
     */
    Route::inertia('/', 'dashboard')->name('dashboard');

    /**
     * User
     */
    Route::prefix('user')->group(function () {
        Route::name('user.account')->match(['get', 'post'], 'account', [UserController::class, 'account']);
        Route::name('user.list')->post('list', [UserController::class, 'list']);

        Route::middleware('can:settings-user.manage')->group(function () {
            Route::name('user.list')->get('list', [UserController::class, 'list']);
            Route::name('user.create')->get('create', [UserController::class, 'create']);
            Route::name('user.edit')->get('edit/{id}', [UserController::class, 'edit']);
            Route::name('user.invite')->post('invite/{id}', [UserController::class, 'invite']);
            Route::name('user.store')->post('store/{id?}', [UserController::class, 'store']);
            Route::name('user.delete')->delete('/', [UserController::class, 'delete']);
        });
    });

    /**
     * Role
     */
    Route::prefix('role')->middleware('can:settings-role.manage')->group(function () {
        Route::name('role.list')->get('list', [RoleController::class, 'list']);
        Route::name('role.create')->get('create', [RoleController::class, 'create']);
        Route::name('role.edit')->get('edit/{id}/{tab?}', [RoleController::class, 'edit']);
        Route::name('role.store')->post('store/{id?}', [RoleController::class, 'store']);
        Route::name('role.delete')->delete('/', [RoleController::class, 'delete']);
    });

    /**
     * Team
     */
    Route::prefix('team')->group(function () {
        Route::name('team.list')->post('list', [TeamController::class, 'list']);

        Route::middleware('can:settings-team.manage')->group(function () {
            Route::name('team.list')->get('list', [TeamController::class, 'list']);
            Route::name('team.create')->get('create', [TeamController::class, 'create']);
            Route::name('team.edit')->get('edit/{id}', [TeamController::class, 'edit']);
            Route::name('team.store')->post('store/{id?}', [TeamController::class, 'store']);
            Route::name('team.delete')->delete('/', [TeamController::class, 'delete']);
        });
    });

    /**
     * File
     */
    Route::prefix('file')->group(function () {
        Route::name('file.list')->get('list', [FileController::class, 'list']);
        Route::name('file.upload')->post('upload', [FileController::class, 'upload']);
        Route::name('file.store')->post('store/{id}', [FileController::class, 'store']);
        Route::name('file.delete')->delete('/', [FileController::class, 'delete']);
    });
});