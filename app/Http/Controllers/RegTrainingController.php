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
        $request->validate([
            'name_pic' => 'required|string|regex:/^[A-Za-z\s]+$/',
            'name_company' => 'required|string',
            'email_pic' => 'required|email',
            'phone' => 'required|regex:/^\+?[1-9]\d{1,14}$/', 
        ]);

        // Simpan data ke database
        $registration = new RegTraining();
        $registration->name_pic = $request->name_pic;
        $registration->name_company = $request->name_company;
        $registration->email_pic = $request->email;
        $registration->phone = $request->phone; 
        // Jika perlu, simpan data pelatihan
        $registration->training_activity = $request->activity; // Contoh untuk data kegiatan pelatihan


        $registration->save();
        session([
            'name_pic' => $registration->name_pic,
            'name_company' => $registration->name_company,
            'email_pic' => $registration->email_pic,
            'phone' => $registration->phone,
            'activity' => $registration->training_activity,
        ]);

        // Redirect atau tampilkan pesan sukses
        return redirect()->route('dashboard.user.registertraining.formreg'); // Ganti dengan rute yang sesuai
    }
}
