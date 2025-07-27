<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Management\DashboardController;
use App\Http\Controllers\Management\LoginController;
use App\Http\Controllers\Management\TeamController;
use App\Http\Controllers\Management\EmployeeController;
use App\Http\Middleware\AuthenticateEmployee;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('management')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('management.showLogin');
    Route::post('login', [LoginController::class, 'authenticate'])->name('management.login');
    Route::post('logout', [LoginController::class, 'logout'])->name('management.logout');

    Route::middleware(AuthenticateEmployee::class)->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('manage.dashboard');

        Route::prefix('team')->controller(TeamController::class)->group(function () {
            Route::get('/', 'index')->name('team.index');
            Route::get('add', 'add')->name('team.add');
            Route::post('add_confirm', 'add_confirm')->name('team.add_confirm');
            Route::get('edit/{id}', 'edit')->name('team.edit');
            Route::post('edit_confirm', 'edit_confirm')->name('team.edit_confirm');
            Route::get('search', 'search')->name('team.search');
            Route::get('delete/{id}', 'delete')->name('team.delete');
            Route::get('recover/{id}', 'recover')->name('team.recover');
        });

        Route::prefix('employee')->controller(EmployeeController::class)->group(function () {
            Route::get('/', 'index')->name('employee.index');
            Route::get('add', 'add')->name('employee.add');
            Route::post('add_confirm', 'add_confirm')->name('employee.add_confirm');
            Route::get('edit/{id}', 'edit')->name('employee.edit');
            Route::post('edit_confirm', 'edit_confirm')->name('employee.edit_confirm');
            Route::get('search', 'search')->name('employee.search');
            Route::get('delete/{id}', 'delete')->name('employee.delete');
            Route::get('recover/{id}', 'recover')->name('employee.recover');
            Route::get('export', 'export')->name('employee.export');
        });
    });
});

