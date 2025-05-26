<?php

use App\Http\Controllers\DashboardAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\UserAccess;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\RegTrainingController;
use Illuminate\Support\Facades\Storage;
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

    Route::get('/notification', function () {
        $user = Auth::user();
        $notifications = $user->notifications->sortByDesc('created_at');
        return view('dashboard.notification.index', ['title' => 'Notifikasi',], compact('notifications'));
    })->name('notification');


    Route::middleware([UserAccess::class . ':admin'])->group(function () {
        Route::get('/admin/training/live', [DashboardAdminController::class, 'getLiveTraining'])->name('admin.training.live');
        Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin.index');
        Route::get('/dashboard/admin/training/{id}', [DashboardAdminController::class, 'show'])->name('dashboard.admin.training.show');
        // Rute untuk memperbarui data training menggunakan POST
        Route::post('/dashboard/admin/training/{id}/update', [DashboardAdminController::class, 'update'])->name('dashboard.training.update');
        Route::post('/dashboard/admin/training/{id}/update2', [DashboardAdminController::class, 'updateFrom2'])->name('dashboard.training.update2');
        Route::post('/dashboard/admin/training/update-participant', [DashboardAdminController::class, 'updateForm2User']);
        Route::post('/dashboard/admin/training/add-participant', [DashboardAdminController::class, 'addParticipant']);
        Route::post('/dashboard/admin/training/finish/{id}', [DashboardAdminController::class, 'trainingFinish']);

        Route::get('/download/file-mou/{id}', function ($id) {
            $file = \App\Models\FileRequirement::findOrFail($id);

            // Pastikan file_mou tidak kosong
            if (!$file->file_mou || !Storage::disk('public')->exists($file->file_mou)) {
                abort(404, 'File tidak ditemukan.');
            }

            return Storage::disk('public');
        })->name('download.file.mou');

        Route::delete('/dashboard/admin/training/delete-participant/{id}', [DashboardAdminController::class, 'destroyParticipant']);
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
