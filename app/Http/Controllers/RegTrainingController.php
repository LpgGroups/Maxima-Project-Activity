<?php

namespace App\Http\Controllers;

use App\Models\RegTraining;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.user.registertraining.index', [
            'title' => 'Daftar Training',
        ]);
    }
    public function formReg()
    {
        $user = Auth::user();
        $training = RegTraining::where('user_id', $user->id) // Mengambil user_id dari pengguna yang sedang login
            ->latest() // Mengurutkan berdasarkan waktu pembuatan terbaru
            ->first(); // Ambil data pertama (terbaru)

        return view('dashboard.user.registertraining.formreg', [
            'title' => 'Form Register',
            'training' => $training, // Mengirim data trainingke view
        ]);
    }
    public function selectDate()
    {
        return view('dashboard.user.registertraining.selectdate', [
            'title' => 'Daftar Training',
        ]);
    }
    public function saveForm1(Request $request)
    {
        // Validasi input
        $request->validate([
            'name_pic' => 'required|string|regex:/^[A-Za-z\s]+$/',
            'name_company' => 'required|string',
            'email_pic' => 'required|email',
            'phone_pic' => 'required',
        ]);
    
        // Cek apakah sudah ada data berdasarkan email atau user_id yang ada di session
        $user = Auth::user(); // Ambil data user yang sedang login
        $existingRegistration = RegTraining::where('user_id', $user->id)->first();
    
        // Jika data sudah ada, update
        if ($existingRegistration) {
            // Update data yang sudah ada
            $existingRegistration->name_pic = $request->name_pic;
            $existingRegistration->name_company = $request->name_company;
            $existingRegistration->phone_pic = $request->phone_pic;
            $existingRegistration->email_pic = $request->email_pic;
    
            // Simpan perubahan
            if ($existingRegistration->save()) {
                // Return success response
                return response()->json(['success' => true]);
            }
        } else {
            // Jika tidak ada data, buat data baru
            $registration = new RegTraining();
            $registration->user_id = $user->id;
            $registration->name_pic = $request->name_pic;
            $registration->name_company = $request->name_company;
            $registration->email_pic = $request->email_pic;
            $registration->phone_pic = $request->phone_pic;
    
            // Simpan data baru
            if ($registration->save()) {
                // Save session data
                session([
                    'name_pic' => $registration->name_pic,
                    'name_company' => $registration->name_company,
                    'email_pic' => $registration->email_pic,
                    'phone_pic' => $registration->phone_pic,
                    'activity' => $registration->activity,
                    'date' => $registration->date,
                ]);
    
                // Return success response
                return response()->json(['success' => true]);
            }
        }
    
        // Return error response jika gagal
        return response()->json(['success' => false]);
    }
    
}
