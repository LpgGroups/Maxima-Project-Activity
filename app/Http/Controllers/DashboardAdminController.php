<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegParticipant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\RegTraining;
use App\Models\TrainingNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Notifications\TrainingUpdatedNotification;
use Illuminate\Support\Facades\Notification;


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
    public function getLiveTraining()
    {
        $trainings = RegTraining::with(['user', 'trainingNotifications.user'])->get();

        $data = $trainings->map(function ($training) {
            
            $adminNotifications = $training->trainingNotifications->filter(function ($notif) {
                return $notif->viewed_at &&
                    $notif->user &&
                    $notif->user->role === 'admin';
            });

            $adminSeen = $adminNotifications->isNotEmpty();
            $lastAdminViewedAt = $adminNotifications->max('viewed_at');

            $isNew = !$adminSeen;
            $isUpdated = $adminSeen && $training->updated_at > $lastAdminViewedAt;

            return [
                'id' => $training->id,
                'user' => $training->user,
                'name_pic' => $training->name_pic,
                'name_company' => $training->name_company,
                'activity' => $training->activity,
                'date' => $training->date,
                'date_end' => $training->date_end,
                'isprogress' => $training->isprogress,
                'isNew' => $isNew,
                'isUpdated' => $isUpdated,
            ];
        });

        return response()->json([
            'data' => $data,
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

        $user = Auth::user();
        $trainingId = $training->id;

        // Jika role admin, tandai semua notifikasi admin terkait training ini sebagai terbaca
        if ($user->role === 'admin') {
            $adminUsers = User::where('role', 'admin')->get();

            foreach ($adminUsers as $admin) {
                $admin->unreadNotifications
                    ->where('data.training_id', $trainingId)
                    ->each(function ($notif) {
                        $notif->markAsRead();
                    });
            }
        } else {
            // Kalau bukan admin (misalnya user biasa), tandai notifikasi miliknya saja
            $notification = $user->unreadNotifications
                ->where('data.training_id', $trainingId)
                ->first();

            if ($notification) {
                $notification->markAsRead();
            }
        }

        return view('dashboard.admin.cektraining.showtraining', [
            'title' => 'Detail Pelatihan',
            'training' => $training
        ]);
    }


    public function update(Request $request, $id)
    {
        // $training = RegTraining::findOrFail($id);
        $training = RegTraining::where('id', $id)->firstOrFail();

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


        $targetUser = $training->user;

        if ($targetUser) {
            $targetUser->notify(new TrainingUpdatedNotification($training, 'admin', 'Daftar Pelatihan'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.'
        ]);
    }

    public function updateForm2User(Request $request)
    {
        $validated = $request->validate([
            'participants' => 'required|array',
            'participants.*.name' => 'required|string|max:255',
            'participants.*.status' => 'required|in:0,1,2',
            'participants.*.reason' => 'nullable|string|max:255',
        ]);

        // Update semua peserta yang diberikan
        foreach ($validated['participants'] as $id => $data) {
            $participant = RegParticipant::find($id);
            if ($participant) {
                $participant->update($data);
            }
        }

        // Ambil salah satu peserta untuk ambil relasi training & user
        $firstParticipant = RegParticipant::find(array_key_first($validated['participants']));
        $training = $firstParticipant?->training;

        if ($training && $training->user) {
            $training->user->notify(new TrainingUpdatedNotification(
                $training,
                'admin',
                'Daftar Peserta',
                'Data peserta pada pelatihan "' . $training->activity . '" telah diperbarui. Silakan cek kembali.',
                'info'
            ));
        }

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

        $training = $participant->training;
        if ($training && $training->user) {
            $training->user->notify(new TrainingUpdatedNotification(
                $training,
                'admin',
                'Daftar Peserta',
                'Admin telah menambahkan peserta baru ke pelatihan ' . $training->activity,
                'update'
            ));
        }

        return response()->json(['success' => true]);
    }

    public function trainingFinish(Request $request, $id)
    {
        $training = RegTraining::where('id', $id)->firstOrFail();
        $currentProgress = $training->isprogress;
        $newProgress = $request->input('isprogress');
        $training->update([
            'isprogress' => max($currentProgress, $newProgress),
        ]);

        $targetUser = $training->user;

        if ($targetUser) {
            $activity = $training->activity;
            $customMessage = "Selamat, Pelatihan {$activity} Telah Disetujui!";

            $targetUser->notify(new TrainingUpdatedNotification(
                $training,
                'admin',
                'Daftar Pelatihan',
                $customMessage,
                'success'
            ));
        }

        return response()->json(['success' => true, 'message' => 'Progress berhasil diperbarui.']);
    }

    public function destroyParticipant($id)
    {
        $participant = RegParticipant::findOrFail($id);
        $training = $participant->training;

        $participant->delete();

        // Kirim notifikasi ke pemilik pelatihan
        if ($training && $training->user) {
            $training->user->notify(new TrainingUpdatedNotification(
                $training,
                'admin',
                'Daftar Peserta',
                'Satu peserta telah dihapus dari pelatihan "' . $training->activity . '".',
                'update'
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil dihapus.'
        ]);
    }

    // public function showDashboard()
    // {
    //     $notifications = Auth::user()->unreadNotifications;

    //     return view('dashboard.admin.index', compact('notifications'));
    // }
}
