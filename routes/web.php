<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\UserAccess;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\RegTrainingController;

Route::get('/', [LoginController::class, 'index'])->name('login.index');
Route::post('/', [LoginController::class, 'authenticate'])->name('login');
Route::get("/test",function(){
    return 'test clear';
});
Route::middleware(['auth'])->group(function () {
    Route::middleware([UserAccess::class . ':admin'])->group(function () {
        Route::get('/dashboard/admin', function () {
            return 'Halaman Admin';
        })->name('dashboard.admin.index');
    });
    Route::middleware([UserAccess::class . ':user'])->group(function () {
         // user
        Route::get('/dashboard/user', [DashboardUserController::class, 'index'])->name('dashboard.user.index');
        Route::post('/dashboard/booking', [DashboardUserController::class, 'bookingDate'])->name('dashboard.user.booking');
        
        // registertraining
        Route::get('/dashboard/user/pelatihan', [RegTrainingController::class, 'index'])->name('dashboard.training');
        Route::get('/dashboard/user/pelatihan/selectdate', [RegTrainingController::class, 'selectDate'])->name('dashboard.selectDate');
        Route::get('/dashboard/user/pelatihan/form', [RegTrainingController::class, 'formReg'])->name('dashboard.form');
        
        // Route::post('/dashboard/user/pelatihan/form/send',[RegTrainingController::class,'formReg'])->name('dashboard.form.send');
    });
});

