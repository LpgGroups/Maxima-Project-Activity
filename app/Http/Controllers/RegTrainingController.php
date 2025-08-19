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

use Illuminate\Support\Facades\Notification;

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
                $q->where('activity', 'like', '%' . $search . '%')
                    ->orWhere('no_letter', 'like', '%' . $search . '%');
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
            'isprogress' => 'required',
            'provience' => 'nullable|string|max:100',
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
                    'city' => $request->input('city'),
                    'provience' => $request->input('provience'),
                    'address' => $request->input('address'),
                    'isprogress' => max($currentProgress, $newProgress),
                ]);


                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new TrainingUpdatedNotification(
                        $trainingData,
                        'user',
                        'Daftar Pelatihan',
                        '',
                        '',
                        '',
                        'user'
                    ));
                }
            } else {
                RegTraining::create([
                    'user_id' => $user->id,
                    'name_pic' => $request->input('name_pic'),
                    'name_company' => $request->input('name_company'),
                    'email_pic' => $request->input('email_pic'),
                    'phone_pic' => $phone,
                    'city' => $request->input('city'),
                    'provience' => $request->input('provience'),
                    'address' => $request->input('address'),
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
        $training = RegTraining::findOrFail($form_id);
        $participants = RegParticipant::where('form_id', $form_id)->get();

        $participantToEdit = null;
        if (request()->has('participant_id')) {
            $participantToEdit = RegParticipant::find(request()->participant_id);
        }

        return view('dashboard.user.participants.addparticipants', [
            'title' => 'Tambah Peserta',
            'form_id' => $form_id,
            'participants' => $participants,
            'training' => $training,
            'participantToEdit' => $participantToEdit,
        ]);
    }


    public function saveForm2(Request $request)
    {
        // Validasi awal supaya bisa load training
        $request->validate([
            'name'              => 'required|string|max:255',
            'nik'               => 'nullable|string|min:16|max:16',
            'birth_place'       => 'nullable|string|max:255',
            'date_birth'        => 'nullable|date',
            'blood_type'        => 'nullable|in:A-,B-,AB-,O-,A+,B+,AB+,O+,-',
            'photo'             => 'nullable|file|image|max:2048',
            'ijazah'            => 'nullable|file|mimes:pdf|max:2048',
            'letter_employee'   => 'nullable|file|mimes:pdf|max:2048',
            'letter_health'     => 'nullable|file|mimes:pdf|max:2048',
            'cv'                => 'nullable|file|mimes:pdf|max:2048',
            'reason'            => 'nullable|string',
            'form_id'           => 'required|exists:reg_training,id',
            'participant_id'    => 'nullable|exists:reg_participants,id',
        ]);

        $training = RegTraining::findOrFail($request->form_id);

        $trainingDate = \Carbon\Carbon::parse($training->training_date ?? $training->date ?? $training->start_date);
        $deadline = $trainingDate->copy()->subDays(3); // Deadline H-3


        if (now()->greaterThanOrEqualTo($deadline)) {
            return redirect()->back()->withErrors([
                'form' => 'Pendaftaran/ubah peserta sudah ditutup pada H-3 sebelum pelatihan. Silakan hubungi admin.'
            ])->withInput();
        }
        $data = $request->only([
            'name',
            'nik',
            'birth_place',
            'date_birth',
            'blood_type',
            'reason',
            'form_id'
        ]);
        $noLetter = str_replace([' ', '/', '\\'], '-', $training->no_letter ?? 'no-surat');
        $nameParticipant = Str::slug($request->name ?? 'peserta', '_');
        $nameCompany = Str::slug($training->name_company ?? 'perusahaan', '_');

        $prefix = "{$noLetter}_{$nameParticipant}_{$nameCompany}";
        $uploaded_files = [];
        $handleFile = function ($field, $folder) use ($request, $prefix, &$data, &$uploaded_files) {
            if ($request->hasFile($field)) {
                $ext = $request->file($field)->getClientOriginalExtension();
                $filename = "{$prefix}_{$field}." . $ext;
                $path = $request->file($field)->storeAs("uploads/participants/{$folder}", $filename, 'public');
                $data[$field] = $path;
                $uploaded_files[$field] = $filename;
            }
        };

        // === Jika EDIT (update) ===
        if ($request->filled('participant_id')) {
            $participant = RegParticipant::findOrFail($request->participant_id);

            foreach (
                [
                    'photo' => 'photo',
                    'ijazah' => 'ijazah',
                    'letter_employee' => 'letter_employee',
                    'letter_statement' => 'letter_statement',
                    'form_registration' => 'form_registration',
                    'letter_health' => 'letter_health',
                    'cv' => 'cv'
                ] as $field => $folder
            ) {
                if ($request->hasFile($field)) {
                    if ($participant->$field && Storage::disk('public')->exists($participant->$field)) {
                        Storage::disk('public')->delete($participant->$field);
                    }
                    $handleFile($field, $folder);
                } else {
                    unset($data[$field]);
                }
            }
            $participant->update($data);
        } else {
            // === Jika TAMBAH (create) ===
            foreach (
                [
                    'photo' => 'photo',
                    'ijazah' => 'ijazah',
                    'letter_employee' => 'letter_employee',
                    'letter_statement' => 'letter_statement',
                    'letter_health' => 'letter_health',
                    'form_registration' => 'form_registration',
                    'cv' => 'cv'
                ] as $field => $folder
            ) {
                $handleFile($field, $folder);
            }

            $participant = RegParticipant::create($data);
        }

        $training->isprogress = max($training->isprogress ?? 0, 3);
        $training->save();

        // Notifikasi admin (optional)
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new TrainingUpdatedNotification(
                $training,
                'user',
                'Peserta Terdaftar',
                '',
                '',
                '',
                'user'
            ));
        }
        $training->touch();

        return redirect()->back()->with('success', 'Peserta Berhasil Ditambahkan.');
    }


    public function saveForm3(Request $request)
    {
        $request->validate([
            'file_id' => 'required|exists:reg_training,id',
            'file_approval' => 'nullable|file|mimes:pdf|max:2048',
            'proof_payment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'file_approval.max' => 'File melebihi batas 2MB.',
            'proof_payment.max' => 'File melebihi batas 2MB.',
        ]);

        $training = RegTraining::where('id', $request->file_id)->firstOrFail();
        $record = FileRequirement::where('file_id', $request->file_id)->first();

        $noLetter = str_replace([' ', '/', '\\'], '-', strtolower($training->no_letter ?? 'no-surat'));
        $nameCompany = Str::slug($training->name_company ?? 'Company', '_');
        $nameTraining = Str::slug($training->activity ?? 'Training', '_');


        $fileApprovalPath = $record->file_approval ?? null;
        $proofPaymentPath = $record->proof_payment ?? null;
        $approvalUploaded = false;
        $proofUploaded    = false;

        if ($request->hasFile('file_approval')) {

            if ($record && $record->file_approval) {
                Storage::disk('public')->delete($record->file_approval);
            }
            $fileName = "File_{$noLetter}_{$nameCompany}_{$nameTraining}." . $request->file('file_approval')->getClientOriginalExtension();

            $fileApprovalPath = $request->file('file_approval')->storeAs('uploads/fileapproval', $fileName, 'public');
            $approvalUploaded  = true;
        }


        if ($request->hasFile('proof_payment')) {
            if ($record && $record->proof_payment) {
                Storage::disk('public')->delete($record->proof_payment);
            }
            $proofName = "Proof_{$noLetter}_{$nameCompany}_{$nameTraining}." . $request->file('proof_payment')->getClientOriginalExtension();

            $proofPaymentPath = $request->file('proof_payment')->storeAs('uploads/proofpayment', $proofName, 'public');
            $proofUploaded = true;
        }


        if ($record) {
            $record->update([
                'file_approval' => $fileApprovalPath,
                'proof_payment' => $proofPaymentPath,
            ]);
        } else {
            FileRequirement::create([
                'file_id' => $request->file_id,
                'file_approval' => $fileApprovalPath,
                'proof_payment' => $proofPaymentPath,
            ]);
        }


        $training->isprogress = max($training->isprogress, 4);
        $training->save();

        // Sentuh updated_at
        $training->touch();


        try {
            // 1) Jika upload bukti pembayaran → ke FINANCE
            if ($proofUploaded) {
                $recipients = User::whereIn('role', ['admin', 'finance'])->get();

                if ($recipients->isNotEmpty()) {
                    Notification::sendNow(
                        $recipients,
                        new TrainingUpdatedNotification(
                            $training,
                            'user',
                            'Upload Bukti Pembayaran',
                            'Bukti pembayaran pelatihan telah diunggah.',
                            'update',
                            '',
                            'user'
                        )
                    );
                }
            }

            // 2) Jika upload file approval → ke ADMIN
            if ($approvalUploaded) {
                $adminRecipients = User::where('role', 'admin')->get();
                if ($adminRecipients->isNotEmpty()) {
                    Notification::sendNow(
                        $adminRecipients,
                        new TrainingUpdatedNotification(
                            $training,
                            'user',
                            'Upload File Persetujuan',
                            'File persetujuan pelatihan telah diunggah.',
                            'update',
                            '',
                            'user'
                        )
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim notifikasi saveForm3', [
                'training_id' => $training->id,
                'error'       => $e->getMessage(),
            ]);
            // jangan throw; biarkan upload tetap sukses
        }


        return response()->json(['message' => 'File berhasil diperbarui!']);
    }


    public function deleteParticipant($id)
    {
        $participant = RegParticipant::find($id);
        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Peserta tidak ditemukan.']);
        }
        // Hapus file terkait jika perlu (photo, ijazah, dst)
        foreach (['photo', 'ijazah', 'letter_employee', 'letter_statement', 'form_registration', 'letter_health', 'cv'] as $field) {
            if ($participant->$field && Storage::disk('public')->exists($participant->$field)) {
                Storage::disk('public')->delete($participant->$field);
            }
        }
        $participant->delete();
        return response()->json(['success' => true]);
    }
}
