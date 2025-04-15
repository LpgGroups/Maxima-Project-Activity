<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\RegTraining;
use App\Models\User;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua pelatihan milik user yang sedang login
        $trainings = RegTraining::where('user_id', Auth::id())
        ->latest() // = orderBy('created_at', 'desc')
        ->get();

        return view('dashboard.user.index', [
            'title' => 'Dashboard User',
            'trainings' => $trainings
        ]);
    }

    public function bookingDate(Request $request)
    {
        // Validasi input dari user
        $validated = $request->validate([
            'date' => 'required|date',  // pastikan 'date' valid
            'activity' => 'required',
            'place' => 'required',
            'isprogress' => 'required'
        ]);

        try {
            $user = Auth::user();  // Menggunakan Auth::user() untuk mendapatkan seluruh data user

            // Pastikan user ada, jika tidak, kirimkan response error
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not authenticated or found.']);
            }

            // Jika validasi sukses, lakukan penyimpanan data
            $booking = new RegTraining();
            $booking->date = $validated['date'];
            $booking->user_id = $user->id;  // Set user_id dengan ID user yang sedang login
            $booking->username = $user->name;  // Set name_user dengan nama pengguna
            $booking->activity = $validated['activity'];
            $booking->place = $validated['place'];
            $booking->isprogress = $validated['isprogress'];

            // Simpan ke database
            $booking->save();

            // Kirim response sukses
            return response()->json([
                'success' => true,
                'id' => $booking->id  // Ensure this contains the ID of the newly created booking
            ]);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error("Error while creating booking: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
