<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/users');

Route::prefix('users')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::delete('/', [UserManagementController::class, 'bulkDestroy'])->name('users.bulkDestroy');
    Route::post('/{user}/restore', [UserManagementController::class, 'restore'])->name('users.restore');
    Route::get('/export', [UserManagementController::class, 'export'])->name('users.export');
});
