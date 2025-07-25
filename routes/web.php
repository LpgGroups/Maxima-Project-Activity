<?php

use App\Http\Controllers\CodeTrainingController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardDevController;
use App\Http\Controllers\DashboardManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\UserAccess;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\DashboardViewersController;

use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RegTrainingController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\carrousel\CarrouselController;

Route::get('/', [LoginController::class, 'index'])->name('login.index');
Route::post('/', [LoginController::class, 'authenticate'])->name('login');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendReset'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
Route::get('/code/training', [CodeTrainingController::class, 'index'])->name('code.index');
Route::get('/code/training/{id}', [CodeTrainingController::class, 'show'])->name('code.show');
Route::middleware(['auth'])->group(function () {
    Route::middleware([UserAccess::class . ':admin'])->group(function () {
        Route::get('/admin/training/live', [DashboardAdminController::class, 'getLiveTraining'])->name('admin.training.live');
        Route::get('/dashboard/admin/training/alltraining', [DashboardAdminController::class, 'trainingAll'])->name('admin.training.alltraining');
        Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin.index');
        Route::get('/dashboard/admin/training/{id}', [DashboardAdminController::class, 'show'])->name('dashboard.admin.training.show');
        Route::get('/dashboard/admin/users', [LoginController::class, 'userList'])->name('users.index');
        Route::get('/dashboard/admin/users/{id}/edit', [LoginController::class, 'editUser'])->name('users.edit');
        Route::put('/dashboard/admin/users/{id}', [LoginController::class, 'updateUser'])->name('users.update');

        Route::delete('/dashboard/admin/users/{id}', [LoginController::class, 'destroyUser'])->name('users.destroy');
        // Rute untuk memperbarui data training menggunakan POST
        Route::post('/dashboard/admin/training/{id}/update', [DashboardAdminController::class, 'update'])->name('dashboard.training.update');
        Route::post('/dashboard/admin/training/{id}/update2', [DashboardAdminController::class, 'updateFrom2'])->name('dashboard.training.update2');
        Route::post('/dashboard/admin/training/update-participant', [DashboardAdminController::class, 'updateForm2User']);
        Route::post('/dashboard/admin/training/add-participant', [DashboardAdminController::class, 'addParticipant']);
        Route::post('/dashboard/admin/training/finish/{id}', [DashboardAdminController::class, 'trainingFinish']);
        Route::post('/dashboard/admin/upload-files', [DashboardAdminController::class, 'uploadFileForAdmin']);
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
        Route::get('/dashboard/user/live-data', [DashboardUserController::class, 'getLiveDataUser'])
            ->name('dashboard.user.liveData');

        // registertraining
        Route::get('/dashboard/user/training', [RegTrainingController::class, 'index'])->name('dashboard.training');
        Route::get('/dashboard/user/training/selectdate', [RegTrainingController::class, 'selectDate'])->name('dashboard.selectDate');
        Route::get('/dashboard/user/training/form/{id}', [RegTrainingController::class, 'formReg'])->name('dashboard.form');

        Route::get('/dashboard/user/training/form2/add/{form_id}', [RegTrainingController::class, 'pageAddParticipant'])->name('dashboard.addparticipant');
        Route::post('/dashboard/user/training/form2/add/{form_id}/save', [RegTrainingController::class, 'saveForm2'])->name('dashboard.addparticipant.save');

        Route::post('/dashboard/user/training/form/save', [RegTrainingController::class, 'saveForm1'])->name('dashboard.form.save');
        Route::post('/dashboard/user/training/form3/save', [RegTrainingController::class, 'saveForm3'])->name('dashboard.form3.save');
        Route::delete('/dashboard/user/training/form2/{id}', [RegTrainingController::class, 'destroyUser'])->name('dashboard.form2.destroy');
        Route::post('/dashboard/user/training/participant/delete/{id}', [RegTrainingController::class, 'deleteParticipant'])
            ->name('dashboard.participant.delete');
    });

    Route::middleware([UserAccess::class . ':management'])->group(function () {
        Route::get('/dashboard/management', [DashboardManagementController::class, 'index'])->name('dashboard.management.index');
        Route::get('/dashboard/management/get', [DashboardManagementController::class, 'getData'])->name('dashboard.management.getdata');
        Route::get('/dashboard/management/detail/{id}', [DashboardManagementController::class, 'showDetail']);
        Route::get('/dashboard/management/training/{id}/detail', [DashboardManagementController::class, 'detailView'])
            ->name('management.training.detail');
        Route::put('/dashboard/management/approve/{id}', [DashboardManagementController::class, 'approve']);
    });

    Route::middleware([UserAccess::class . ':dev'])->group(function () {
        Route::get('/dashboard/developer', [DashboardDevController::class, 'index'])
            ->name('dashboard.dev.index');

        Route::get('/dashboard/developer/account', [DashboardDevController::class, 'allUser'])
            ->name('dashboard.dev.alluser');

        Route::get('/dashboard/developer/account/{id}/edit', [DashboardDevController::class, 'editUser'])
            ->name('dashboard.dev.edit');

        Route::get('/dashboard/developer/account/add', [DashboardDevController::class, 'homeaddUser'])
            ->name('dashboard.dev.add');
        Route::post('/dashboard/developer/account/add', [DashboardDevController::class, 'addUser'])->name('dashboard.dev.store');

        Route::put('/dashboard/developer/account/{id}', [DashboardDevController::class, 'updateUser'])
            ->name('dashboard.dev.update');

        Route::prefix('dashboard/dev/carrousel')->name('carrousel.')->group(function () {
            Route::get('/', [CarrouselController::class, 'index'])->name('index');
            Route::get('/create', [CarrouselController::class, 'create'])->name('create');
            Route::post('/', [CarrouselController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CarrouselController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CarrouselController::class, 'update'])->name('update');
            Route::delete('/{id}', [CarrouselController::class, 'destroy'])->name('destroy');
        });
        Route::delete('/dashboard/developer/account/{id}', [DashboardDevController::class, 'destroyUser'])->name('user.destroy');
    });

    Route::middleware([UserAccess::class . ':viewer'])->group(function () {
        Route::get('/dashboard/viewers', [DashboardViewersController::class, 'index'])
            ->name('dashboard.viewers.index');
        Route::get('/dashboard/viewers/detail/{id}', [DashboardViewersController::class, 'show'])
            ->name('dashboard.viewers.show');
    });

    Route::get('/dashboard/schedule', [MonitoringController::class, 'schedule'])->name('dashboard.shcedule');

    Route::prefix('dashboard/monitoring')->middleware(['auth'])->group(function () {
        Route::get('/', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/{id}', [MonitoringController::class, 'show'])->name('monitoring.show');
    });

    Route::get('/tutorial', function () {
        return view('dashboard.layouts.faq.tutorial', ["title" => ' Tutorial']); // ini akan memuat resources/views/tutorial.blade.php
    });

    Route::get('/notification', function () {
        $user = Auth::user();
        $notifications = $user->notifications->sortByDesc('created_at');
        return view('dashboard.notification.index', ['title' => 'Notifikasi',], compact('notifications'));
    })->name('notification');

    Route::get('/fetch-notifications', function () {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }

        $notifications = $user->unreadNotifications
            ->sortByDesc('created_at')
            ->take(10);

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'message' => $notif->data['message'] ?? 'Notifikasi baru',
                    'url' => $notif->data['url'] ?? '#',
                    'type' => $notif->data['type'] ?? 'default',
                    'created_at' => $notif->created_at->diffForHumans(),
                ];
            })->values(),
        ]);
    })->middleware('auth');

    Route::get('/download-confidential/{type}/{file}', [FileDownloadController::class, 'download'])
        ->name('download.confidential')
        ->middleware(['auth']);

    Route::get('/profile/settings', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/settings', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
