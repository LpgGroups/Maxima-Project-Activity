<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\RegTraining;
use App\Notifications\NewTrainingRegistered;
use App\Models\User;

class DashboardUserController extends Controller
{
    public function index()
    {
        $trainings = RegTraining::where('user_id', Auth::id())->get();

        // Ambil semua participants dari training-training tersebut
        $participants = RegParticipant::whereIn('form_id', $trainings->pluck('id'))->get();

        // Optional: Hitung total peserta
        $totalParticipants = $participants->count();
        $totalTrainings = RegTraining::where('user_id', Auth::id())->count();
        $trainings = RegTraining::where('user_id', Auth::id())
            ->latest()  // Mengurutkan berdasarkan 'created_at' secara menurun (terbaru)
            ->take(10)  // Membatasi hanya 10 data pelatihan
            ->get();

        return view('dashboard.user.index', [
            'title' => 'Dashboard User',
            'trainings' => $trainings,
            'totalTrainings' => $totalTrainings,
            'totalParticipants' => $totalParticipants,
        ]);
    }

    public function bookingDate(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'activity' => 'required',
            'place' => 'required',
            'isprogress' => 'required'
        ]);

        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not authenticated.']);
            }

            // Map activity ke durasi (hari)
            $durationMap = [
                'TKPK1' => 6,
                'TKPK2' => 6,
                'TKBT1' => 4,
                'TKBT2' => 4,
                'BE' => 2,
            ];

            $duration = $durationMap[$validated['activity']] ?? 1; // default 1 hari
            $startDate = \Carbon\Carbon::parse($validated['date']);
            $endDate = $startDate->copy()->addDays($duration - 1); // -1 agar 1-6 itu = 6 hari

            // Simpan data
            $booking = new RegTraining();
            $booking->date = $startDate->toDateString();
            $booking->date_end = $endDate->toDateString(); // Simpan tanggal akhir
            $booking->user_id = $user->id;
            $booking->username = $user->name;
            $booking->activity = $validated['activity'];
            $booking->place = $validated['place'];
            $booking->isprogress = max($booking->isprogress, $validated['isprogress']);

            $booking->save();
            $booking->load('user');

            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new NewTrainingRegistered($booking));
            }

            return response()->json([
                'success' => true,
                'id' => $booking->id
            ]);
        } catch (\Exception $e) {
            Log::error("Error while creating booking: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function showProfile() {}
}
