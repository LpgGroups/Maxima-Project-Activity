<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegTraining;
use App\Models\TrainingNotification;



class DashboardAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user(); // Admin yang login

        // Mengubah eager loading untuk menggunakan 'trainingNotifications'
        $trainingAll = RegTraining::with('trainingNotifications') // Ganti 'notification' jadi 'trainingNotifications'
            ->latest()
            ->get();

        $totalTraining = RegTraining::count();

        return view('dashboard.admin.index', [
            'title' => 'Dashboard Admin',
            'trainingAll' => $trainingAll,
            'totalTraining' => $totalTraining,
            'admin' => $admin, // opsional kalau mau pakai di blade
        ]);
    }
    public function show($id)
    {
        $training = RegTraining::with(['participants', 'trainingNotifications'])
            ->findOrFail($id);

        $userId = Auth::id();

        TrainingNotification::updateOrCreate(
            ['user_id' => $userId, 'reg_training_id' => $training->id],
            ['viewed_at' => now()]
        );

        return view('dashboard.admin.cektraining.showtraining', [
            'title' => 'Detail Pelatihan',
            'training' => $training
        ]);
    }
    public function showDashboard()
    {
        $notifications = Auth::user()->unreadNotifications;

        return view('dashboard.admin.index', compact('notifications'));
    }
}
