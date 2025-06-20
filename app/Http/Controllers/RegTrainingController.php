<?php

namespace App\Http\Controllers;

use App\Models\RegTraining;
use App\Http\Controllers\Controller;
use App\Models\FileRequirement;
use App\Models\RegParticipant;
use App\Models\TrainingNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\TrainingUpdatedNotification;


class RegTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RegTraining::with('participants')
            ->where('user_id', Auth::id());

        // Filter: Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('activity', 'like', '%' . $search . '%');
            });
        }

        // Filter: Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Sort: A-Z berdasarkan nama pelatihan
        if ($request->filled('sort') && $request->sort == 'az') {
            $query->orderBy('activity', 'asc');
        } else {
            $query->latest();
        }

        $perPage = $request->get('per_page', 10);
        $trainings = $query->paginate($perPage)->appends(request()->query());

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

        $notification = TrainingNotification::firstOrCreate([
            'user_id' => $user->id,
            'reg_training_id' => $training->id,
        ]);

        if (!$notification->viewed_at || $training->updated_at > $notification->viewed_at) {
            $notification->update(['viewed_at' => now()]);
        }

        $notifications = $user->unreadNotifications
            ->where('data.training_id', $training->id)
            ->first();

        if ($notifications) {
            $notifications->markAsRead();
        }

        // Tentukan max tab berdasarkan status form
        $maxTab = 1;
        if ($training->isComplete()) {
            $maxTab = 2;
        }
        if ($training->isComplete() && $training->isLinkFilled()) {
            $maxTab = 3;
        }
        session(['maxTab' => $maxTab]);

        return view('dashboard.user.registertraining.formreg', [
            'title' => 'Form Register',
            'training' => $training,
            'trainingId' => $id,
            'maxTab' => $maxTab,
            'fileRequirement' => $training->files->first(),
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

            // Normalisasi nomor WA: ubah awalan 0 menjadi 62
            $phone = $request->input('phone_pic');
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            }

            // Cek apakah record sudah ada berdasarkan ID dan user_id
            $trainingData = RegTraining::where('user_id', $user->id)
                ->where('id', $request->input('id'))
                ->first();

            // Setelah proses upload, simpan nama file ke session
            $uploaded_files = [
                'photo' => $request->file('photo') ? $request->file('photo')->getClientOriginalName() : null,
                'ijazah' => $request->file('ijazah') ? $request->file('ijazah')->getClientOriginalName() : null,
                'letter_employee' => $request->file('letter_employee') ? $request->file('letter_employee')->getClientOriginalName() : null,
                'letter_health' => $request->file('letter_health') ? $request->file('letter_health')->getClientOriginalName() : null,
                'cv' => $request->file('cv') ? $request->file('cv')->getClientOriginalName() : null,
            ];
            session()->flash('uploaded_files', $uploaded_files);


            if ($trainingData) {
                // Update data jika sudah ada
                $currentProgress = $trainingData->isprogress;
                $newProgress = $request->input('isprogress');

                // Cek apakah progress baru lebih tinggi
                $trainingData->update([
                    'name_pic' => $request->input('name_pic'),
                    'name_company' => $request->input('name_company'),
                    'email_pic' => $request->input('email_pic'),
                    'phone_pic' => $phone,
                    'isprogress' => max($currentProgress, $newProgress),
                ]);


                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new TrainingUpdatedNotification($trainingData, 'user', 'Daftar Pelatihan'));
                }
            } else {

                RegTraining::create([
                    'user_id' => $user->id,
                    'name_pic' => $request->input('name_pic'),
                    'name_company' => $request->input('name_company'),
                    'email_pic' => $request->input('email_pic'),
                    'phone_pic' => $phone,
                    'isprogress' => $request->input('isprogress'),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan/diupdate!']);
        } catch (\Exception $e) {
            Log::error('Error in saveForm1: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan, coba lagi.']);
        }
    }

    public function pageAddParticipant($form_id)
    {
        $participants = RegParticipant::where('form_id', $form_id)->get();
        return view('dashboard.user.participants.addparticipants', [
            'title' => 'Tambah Peserta',
            'form_id' => $form_id,
            'participants' => $participants
        ]);
    }

    public function saveForm2(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'nik'               => 'nullable|string|min:16|max:20',
            'date_birth'        => 'nullable|date',
            'photo'             => 'nullable|file|image|max:2048',
            'ijazah'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'letter_employee'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'letter_health'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cv'                => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'reason'            => 'nullable|string',
            'form_id'           => 'required|exists:reg_training,id',
            'participant_id'    => 'nullable|exists:reg_participants,id', // untuk edit
        ]);

        $data = $request->only([
            'name',
            'nik',
            'date_birth',
            'reason',
            'form_id'
        ]);
        $prefix = strtolower(str_replace(' ', '_', $request->name)) ?: 'file';

        // Siapkan array untuk nama file yang baru saja di-upload (buat feedback jika mau)
        $uploaded_files = [];

        // Handle upload (helper closure biar DRY)
        $handleFile = function ($field, $folder) use ($request, $prefix, &$data, &$uploaded_files) {
            if ($request->hasFile($field)) {
                $ext = $request->file($field)->getClientOriginalExtension();
                $filename = "{$prefix}_{$field}." . $ext;
                $path = $request->file($field)->storeAs("uploads/participanst/{$folder}", $filename, 'public');
                $data[$field] = $path;
                $uploaded_files[$field] = $filename;
            }
        };

        // === Jika EDIT (update) ===
        if ($request->filled('participant_id')) {
            $participant = RegParticipant::findOrFail($request->participant_id);

            // Upload baru: hapus file lama (jika ada), lalu simpan file baru
            foreach (
                [
                    'photo' => 'photo',
                    'ijazah' => 'ijazah',
                    'letter_employee' => 'letter_employee',
                    'letter_health' => 'letter_health',
                    'cv' => 'cv'
                ] as $field => $folder
            ) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($participant->$field && Storage::disk('public')->exists($participant->$field)) {
                        Storage::disk('public')->delete($participant->$field);
                    }
                    $handleFile($field, $folder);
                } else {
                    // Jangan update kolom file jika tidak upload baru (biar data lama tetap)
                    unset($data[$field]);
                }
            }

            // Status: tetapkan ke 1 (sukses), atau bisa disesuaikan
            $data['status'] = 1;

            $participant->update($data);
        } else {
            // === Jika TAMBAH (create) ===
            foreach (
                [
                    'photo' => 'photo',
                    'ijazah' => 'ijazah',
                    'letter_employee' => 'letter_employee',
                    'letter_health' => 'letter_health',
                    'cv' => 'cv'
                ] as $field => $folder
            ) {
                $handleFile($field, $folder);
            }

            $data['status'] = 1;
            $participant = RegParticipant::create($data);
        }

        // Training: update progress dsb
        $training = RegTraining::find($data['form_id']);
        if ($training) {
            $training->isprogress = max($training->isprogress ?? 0, 3);
            $training->save();

            // Notifikasi admin (optional)
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new TrainingUpdatedNotification($training, 'user', 'Peserta Terdaftar'));
            }
            $training->touch();
        }

        return redirect()->back()->with('success', 'Participant registered successfully.');
    }


    public function destroyUser($id)
    {
        $participant = RegParticipant::findOrFail($id);
        $participant->delete();

        return back()->with('success', 'Peserta berhasil dihapus.');
    }
}
