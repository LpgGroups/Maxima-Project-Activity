<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegTraining;
use Illuminate\Http\Request;
use App\Models\TrainingNotification;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class MonitoringController extends Controller
{
    public function index()
    {
        $trainings = RegTraining::with('user')->latest()->paginate(15);
        return view('dashboard.monitoring.index', [
            'title' => 'Monitoring',
            'trainings' => $trainings
        ]);
    }
    public function show($id)
    {
        $training = RegTraining::with('user')->findOrFail($id);

        $rawNotifications = DatabaseNotification::where('notifiable_type', User::class)
            ->where('data->training_id', $id)
            ->orderByDesc('created_at')
            ->get();


        // Deduplicate berdasarkan kombinasi unik
        $notifications = $rawNotifications->unique(function ($notif) {
            return $notif->data['training_id'] . $notif->data['message'] . $notif->created_at;
        });

        return view('dashboard.monitoring.show', [
            'training' => $training,
            'notifications' => $notifications,
            'title' => 'Monitoring Pelatihan',
        ]);
    }
}
