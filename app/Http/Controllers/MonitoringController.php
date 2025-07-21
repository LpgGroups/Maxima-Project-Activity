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
    public function index(Request $request)
    {
        $query = RegTraining::with(['user', 'participants']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name_company', 'like', '%' . $search . '%')
                    ->orWhere('name_pic', 'like', '%' . $search . '%')
                    ->orWhere('activity', 'like', '%' . $search . '%')
                    ->orWhere('no_letter', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('sort') && $request->sort == 'az') {
            $query->orderBy('name_company', 'asc');
        } else {
            $query->latest();
        }
        $perPage = $request->get('per_page', 15);
        $trainings = $query->paginate($perPage)->appends(request()->query());

        $allTrainings = $query->get();
        $totalParticipants = $allTrainings->sum(fn($training) => $training->participants->count());

        return view('dashboard.monitoring.index', [
            'title' => 'Monitoring',
            'trainings' => $trainings,
          
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
