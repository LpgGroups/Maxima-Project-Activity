<?php

use App\Http\Controllers\DashboardAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\UserAccess;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\RegTrainingController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [LoginController::class, 'index'])->name('login.index');
Route::post('/', [LoginController::class, 'authenticate'])->name('login');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::middleware([UserAccess::class . ':admin'])->group(function () {
        Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin.index');
        Route::get('/dashboard/admin/training/{id}', [DashboardAdminController::class, 'show'])->name('dashboard.admin.training.show');
    });
    Route::middleware([UserAccess::class . ':user'])->group(function () {
        // user
        Route::get('/dashboard/user', [DashboardUserController::class, 'index'])->name('dashboard.user.index');
        Route::post('/dashboard/booking', [DashboardUserController::class, 'bookingDate'])->name('dashboard.user.booking');

        // registertraining
        Route::get('/dashboard/user/training', [RegTrainingController::class, 'index'])->name('dashboard.training');
        Route::get('/dashboard/user/training/selectdate', [RegTrainingController::class, 'selectDate'])->name('dashboard.selectDate');
        Route::get('/dashboard/user/training/form/{id}', [RegTrainingController::class, 'formReg'])->name('dashboard.form');
        Route::post('/dashboard/user/training/form/save', [RegTrainingController::class, 'saveForm1'])->name('dashboard.form.save');
        Route::post('/dashboard/user/training/form2/save', [RegTrainingController::class, 'saveForm2'])->name('dashboard.form2.save');
        Route::post('/dashboard/user/training/form3/save', [RegTrainingController::class, 'saveForm3'])->name('dashboard.form3.save');
        Route::delete('/dashboard/user/training/form2/{id}', [RegTrainingController::class, 'destroyUser'])->name('dashboard.form2.destroy');


        // Route::post('/dashboard/user/pelatihan/form/send',[RegTrainingController::class,'formReg'])->name('dashboard.form.send');
    });
});
