<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegParticipant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\RegTraining;
use App\Models\TrainingNotification;
use Illuminate\Support\Facades\Log;


class DashboardAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $trainingAll = RegTraining::with('trainingNotifications')->latest()->get();
        $totalTraining = RegTraining::count();

        return view('dashboard.admin.index', [
            'title' => 'Dashboard Admin',
            'trainingAll' => $trainingAll,
            'totalTraining' => $totalTraining,
            'admin' => $admin,
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

        $notification = Auth::user()->unreadNotifications
            ->where('data.training_id', $training->id)
            ->first();


        if ($notification) {
            $notification->markAsRead();
        }

        return view('dashboard.admin.cektraining.showtraining', [
            'title' => 'Detail Pelatihan',
            'training' => $training
        ]);
    }

    public function update(Request $request, $id)
    {
        $training = RegTraining::findOrFail($id);

        // Validasi dan update data
        $validated = $request->validate([
            'name_pic' => 'required|string',
            'name_company' => 'required|string',
            'email_pic' => 'required|email',
            'phone_pic' => 'required|string',
            'activity' => 'required|string',
            'date' => 'required|date',
            'place' => 'required|string',
        ]);

        $training->update([
            'name_pic' => $validated['name_pic'],
            'name_company' => $validated['name_company'],
            'email_pic' => $validated['email_pic'],
            'phone_pic' => $validated['phone_pic'],
            'activity' => $validated['activity'],
            'date' => Carbon::parse($validated['date'])->format('Y-m-d'),
            'place' => $validated['place'],
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function updateForm2User(Request $request)
    {
        $validated = $request->validate([
            'participants' => 'required|array',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.status' => 'required|in:0,1,2',
            'participants.*.reason' => 'nullable|string|max:255',
        ]);

        // Proses update
        foreach ($request->participants as $id => $data) {
            $participant = RegParticipant::find($id);
            if ($participant) {
                $participant->update($data);
            }
        }

        // ✅ Kembalikan JSON, bukan redirect atau view
        return response()->json(['success' => true]);
    }

    
    public function addParticipant(Request $request)
    {
        Log::info('Data yang diterima:', $request->all());
        $validated = $request->validate([
            'form_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        // Proses penyimpanan peserta baru
        $participant = new RegParticipant();
        $participant->form_id = $validated['form_id'];
        $participant->name = $validated['name'];
        $participant->status = 1; // Status default
        $participant->reason = null; // Alasan default
        $participant->save();

        return response()->json(['success' => true]);
    }
    // public function showDashboard()
    // {
    //     $notifications = Auth::user()->unreadNotifications;

    //     return view('dashboard.admin.index', compact('notifications'));
    // }
}
