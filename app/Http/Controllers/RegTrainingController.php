<?php

namespace App\Http\Controllers;

use App\Models\RegTraining;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
         $training = RegTraining::where('user_id', auth()->user()->id) // Mengambil user_id dari pengguna yang sedang login
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
}
