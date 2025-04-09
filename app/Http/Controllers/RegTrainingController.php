<?php

namespace App\Http\Controllers;

use App\Models\RegTraining;
use App\Http\Controllers\Controller;
use App\Models\RegParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        'phone_pic' => 'required|numeric',    
    ]);

    try {
        $user = Auth::user(); // Pastikan user terautentikasi

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated or found.']);
        }

        // Cek apakah record sudah ada berdasarkan ID dan user_id
        $trainingData = RegTraining::where('user_id', $user->id)
            ->where('id', $request->input('id'))
            ->first();

        if ($trainingData) {
            // Update data jika sudah ada
            $trainingData->update([
                'name_pic' => $request->input('name_pic'),
                'name_company' => $request->input('name_company'),
                'email_pic' => $request->input('email_pic'),
                'phone_pic' => $request->input('phone_pic'),
               
            ]);
        } else {
            // Simpan data baru jika belum ada
            RegTraining::create([
                'user_id' => $user->id,
                'name_pic' => $request->input('name_pic'),
                'name_company' => $request->input('name_company'),
                'email_pic' => $request->input('email_pic'),
                'phone_pic' => $request->input('phone_pic'),
              
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan/diupdate!']);
    } catch (\Exception $e) {
        // Log error jika terjadi masalah
        Log::error("Error while saving/updating form: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan, coba lagi.']);
    }
}

public function saveForm2(Request $request)
{
    // Validasi data
    $request->validate([
        'participants' => 'required|array',
        'participants.*.name' => 'required|string', // Validasi nama peserta
        'form_id' => 'required|integer', // Validasi form_id
    ]);

    // Proses data peserta
    foreach ($request->participants as $participantData) {
        // Simpan data peserta ke database
        $participant = new RegParticipant(); // Ganti dengan model yang sesuai
        $participant->name = $participantData['name'];
        $participant->form_id = $request->form_id; // Menggunakan form_id yang dikirim dari frontend
        $participant->save(); // Simpan ke database
    }

    return response()->json(['message' => 'Data berhasil disimpan']);
}

}
