<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\RegTraining;
use App\Notifications\NewTrainingRegistered;
use App\Notifications\TrainingUpdatedNotification;
use App\Models\User;
use Illuminate\Support\Str;

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

        $fullQuotaDates = RegTraining::select('date')
            ->groupBy('date')
            ->havingRaw('COUNT(*) >= 2')
            ->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
            ->toArray();
        return view('dashboard.user.index', [
            'title' => 'Dashboard User',
            'trainings' => $trainings,
            'totalTrainings' => $totalTrainings,
            'fullQuotaDates' => $fullQuotaDates,
            'totalParticipants' => $totalParticipants,
        ]);
    }

    public function bookingDate(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'activity' => 'required',
            'place' => 'required',
            'isprogress' => 'required',
            'city' => 'nullable|string|max:100'
        ]);

        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not authenticated.']);
            }

            $activity = $validated['activity'];
            $place = $validated['place'];

            $duration = match ($activity) {
                'TKPK1', 'TKPK2' => 6,
                'TKBT1', 'TKBT2' => 4,
                'AK3U' => 12,
                'P3K' => 3,
                'BE' => match ($place) {
                    'On-Site' => 3,
                    'Online' => 1,
                    default => 3,
                },
                default => 1
            };
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
            $booking->city = $validated['city'] ?? null;
            $booking->isprogress = max($booking->isprogress, $validated['isprogress']);
            $booking->code_training = strtoupper(Str::random(6)) . '-' . strtoupper(Str::random(6));
            $countSameDay = RegTraining::where('date', $startDate->toDateString())
                ->where('user_id', $user->id)
                ->count();

            if ($countSameDay >= 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota pendaftaran Anda pada tanggal tersebut sudah penuh.'
                ]);
            }

            // Cek kuota umum (global full)
            $countAllSameDay = RegTraining::whereDate('date', $startDate->toDateString())
                ->count();

            if ($countAllSameDay >= 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelatihan pada tanggal tersebut sudah penuh. Silakan pilih tanggal lain.'
                ]);
            }
            $now = now();
            $month = $now->format('m'); // bulan saat ini
            $year = $now->format('Y');  // tahun saat ini

            // Hitung jumlah pendaftaran tahun ini untuk activity yang sama
            $sequence = RegTraining::where('activity', $booking->activity)
                ->whereYear('created_at', $year)
                ->count() + 1;

            $noUrut = str_pad($sequence, 4, '0', STR_PAD_LEFT);
            $noLetter = "{$booking->activity}/{$noUrut}/{$month}/{$year}";

            $booking->no_letter = $noLetter;

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
    public function getLiveDataUser()
    {
        $user = Auth::user();

        $trainings = RegTraining::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $data = $trainings->map(function ($training, $index) use ($user) {
            $notification = $training->trainingNotifications
                ->where('user_id', $user->id)
                ->first();

            $isNew = !$notification || !$notification->viewed_at;
            $isUpdated = $notification && $notification->viewed_at && $training->updated_at > $notification->viewed_at;

            // Format tanggal
            $start = \Carbon\Carbon::parse($training->date)->locale('id');
            $end = \Carbon\Carbon::parse($training->date_end)->locale('id');

            if ($start->equalTo($end)) {
                // Tanggal sama persis (1 hari)
                $date = $start->translatedFormat('d F Y');
            } elseif ($start->year != $end->year) {
                $date = $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
            } elseif ($start->month == $end->month) {
                $date = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
            } else {
                $date = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
            }


            $progressMap = [
                1 => ['percent' => 10, 'color' => 'bg-red-600'],
                2 => ['percent' => 30, 'color' => 'bg-orange-500'],
                3 => ['percent' => 50, 'color' => 'bg-yellow-400'],
                4 => ['percent' => 75, 'color' => 'bg-[#bffb4e]'],
                5 => ['percent' => 100, 'color' => 'bg-green-600'],
            ];

            if ((int) $training->isfinish === 2) {
                $progress['color'] = 'bg-red-600';
                $progress['percent'] = 100;
            }

            $progress = $progressMap[$training->isprogress] ?? ['percent' => 0, 'color' => 'bg-gray-400'];

            return [
                'no' => $index + 1,
                'activity' => $training->activity,
                'isNew' => $isNew,
                'isUpdated' => $isUpdated,
                'status' => 'Aktif',
                'training_date_raw' => $start->toDateString(),
                'date' => $date,
                'progress_percent' => $progress['percent'],
                'progress_color' => $progress['color'],
                'isfinish' => (int) $training->isfinish,
                'isprogress' => (int) $training->isprogress,

                'url' => route('dashboard.form', ['id' => $training->id])
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function showProfile() {}
}
