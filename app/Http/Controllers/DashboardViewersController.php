<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FileRequirement;
use App\Models\RegParticipant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\RegTraining;
use App\Models\TrainingNotification;
use App\Models\User;
use App\Notifications\TrainingUpdatedNotification;
use Illuminate\Support\Facades\Storage;

class DashboardViewersController extends Controller
{
    public function index(Request $request)
    {
        $viewers = Auth::user();

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

        $allTrainings = $query->get();
        $totalParticipants = $allTrainings->sum(fn($training) => $training->participants->count());

        $perPage = $request->get('per_page', 20);
        $trainingAll = $query->paginate($perPage)->appends(request()->query());

        return view('dashboard.viewers.index', [
            'title' => 'Dashboard Viewer',
            'trainingAll' => $trainingAll,
            'viewers' => $viewers,
            'totalParticipants' => $totalParticipants,
        ]);
    }

    public function show($id)
    {
        $training = RegTraining::with(['participants'])
            ->findOrFail($id);

        $fileRequirement = FileRequirement::where('file_id', $training->id)->first();

        return view('dashboard.viewers.show', [
            'title' => 'Detail Pelatihan',
            'training' => $training,
            'fileRequirement' => $fileRequirement
        ]);
    }
}
