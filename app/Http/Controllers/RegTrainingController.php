<?php

namespace App\Http\Controllers;

use App\Models\RegTraining;
use App\Http\Controllers\Controller;
use App\Models\FileRequirement;
use App\Models\RegParticipant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


class RegTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainings = RegTraining::with('participants') // <--- ini bagian penting
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard.user.registertraining.index', [
            'title' => 'Daftar Training',
            'trainings' => $trainings
        ]);
    }

    public function selectDate()
    {
        return view('dashboard.user.registertraining.selectdate', [
            'title' => 'Daftar Training',
        ]);
    }
    public function formReg($id)
    {
        $user = Auth::user();

        $training = RegTraining::where('user_id', $user->id)
            ->where('id', $id)
            ->with('participants')
            ->firstOrFail();

        // Ambil tab terakhir user dari session
        $sessionKey = 'active_tab_user_' . $user->id . '_training_' . $id;
        $activeTab = Session::get($sessionKey, 1); // default ke 1

        // Validasi: hanya bisa ke tab berikutnya jika sebelumnya sudah lengkap
        if ($activeTab > 1 && !$training->isComplete()) {
            $activeTab = 1;
        } elseif ($activeTab > 2 && !$training->isLinkFilled()) {
            $activeTab = 2;
        }

        return view('dashboard.user.registertraining.formreg', [
            'title' => 'Form Register',
            'training' => $training,
            'activeTab' => $activeTab,
            'trainingId' => $id
        ]);
    }
    public function saveForm1(Request $request)
    {
        // Validasi input
        $request->validate([
            'name_pic' => ['required', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'name_company' => ['required', 'string'],
            'email_pic' => ['required', 'email'],
            'phone_pic' => ['required', 'numeric', 'digits_between:10,13'],
            'isprogress' => 'required'
        ], [
            'name_pic.regex' => 'Nama PIC hanya boleh berisi huruf dan spasi.',
            'email_pic.email' => 'Format email tidak valid.',
            'phone_pic.required' => 'Nomor WhatsApp wajib diisi.',
            'phone_pic.numeric' => 'Nomor WhatsApp harus berupa angka ex:085712341234.',
            'phone_pic.digits_between' => 'Nomor WhatsApp harus berisi 10 hingga 13 digit.'
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
                $currentProgress = $trainingData->isprogress;
                $newProgress = $request->input('isprogress');

                // Cek apakah progress baru lebih tinggi
                $trainingData->update([
                    'name_pic' => $request->input('name_pic'),
                    'name_company' => $request->input('name_company'),
                    'email_pic' => $request->input('email_pic'),
                    'phone_pic' => $request->input('phone_pic'),
                    'isprogress' => max($currentProgress, $newProgress),
                ]);
            } else {
                // Simpan data baru jika belum ada
                RegTraining::create([
                    'user_id' => $user->id,
                    'name_pic' => $request->input('name_pic'),
                    'name_company' => $request->input('name_company'),
                    'email_pic' => $request->input('email_pic'),
                    'phone_pic' => $request->input('phone_pic'),
                    'isprogress' => $request->input('isprogress'),
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
        $request->validate([
            'form_id' => 'required|integer',
            'link' => 'required|string',
        ]);

        $formId = $request->form_id;

        $training = RegTraining::find($formId);
        if ($training) {
            $training->isprogress = max($training->isprogress, 3);
            $training->link = $request->link;
            $training->save();
        }

        foreach ($request->participants as $participantData) {
            $name = trim($participantData['name'] ?? '');

            // Lewati jika nama kosong
            if ($name === '') {
                continue;
            }

            // Cek apakah peserta dengan nama ini sudah ada di form ini
            $exists = RegParticipant::where('form_id', $formId)
                ->where('name', $name)
                ->exists();

            if (!$exists) {
                RegParticipant::create([
                    'form_id' => $formId,
                    'name' => $name,
                ]);
            }
        }


        return response()->json(['message' => 'Data peserta berhasil ditambahkan']);
    }

    public function saveForm3(Request $request)
    {
        $request->validate([
            'file_id' => 'required|exists:reg_training,id',
            'file_mou' => 'nullable|file|mimes:pdf|max:2048',
            'file_quotation' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $record = FileRequirement::where('file_id', $request->file_id)->first();
        $training = RegTraining::find($request->file_id); // ← ambil data untuk nama file

        // Ganti dengan field yang sesuai dari DB kamu
        $nameCompany = Str::slug($training->name_company ?? 'Company', '_');
        $nameTraining = Str::slug($training->activity ?? 'Training', '_');

        $mouPath = $record->file_mou ?? null;
        $quotationPath = $record->file_quotation ?? null;

        if ($request->hasFile('file_mou')) {
            if ($record && $record->file_mou) {
                Storage::disk('public')->delete($record->file_mou);
            }

            $fileName = "MoU_{$nameCompany}_{$nameTraining}." . $request->file('file_mou')->getClientOriginalExtension();
            $mouPath = $request->file('file_mou')->storeAs('uploads/mou', $fileName, 'public');
        }

        if ($request->hasFile('file_quotation')) {
            if ($record && $record->file_quotation) {
                Storage::disk('public')->delete($record->file_quotation);
            }

            $fileName = "Quotation_{$nameCompany}_{$nameTraining}." . $request->file('file_quotation')->getClientOriginalExtension();
            $quotationPath = $request->file('file_quotation')->storeAs('uploads/quotation', $fileName, 'public');
        }

        if ($record) {
            $record->update([
                'file_mou' => $mouPath,
                'file_quotation' => $quotationPath,
            ]);
        } else {
            FileRequirement::create([
                'file_id' => $request->file_id,
                'file_mou' => $mouPath,
                'file_quotation' => $quotationPath,
            ]);
        }

        return response()->json(['message' => 'File berhasil diperbarui!']);
    }

    public function destroyUser($id)
    {
        $participant = RegParticipant::findOrFail($id);
        $participant->delete();

        return back()->with('success', 'Peserta berhasil dihapus.');
    }
}
