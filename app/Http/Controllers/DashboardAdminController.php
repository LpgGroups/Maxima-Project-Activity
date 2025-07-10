<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FileRequirement;
use App\Models\RegParticipant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\RegTraining;
use App\Models\TrainingNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Notifications\TrainingUpdatedNotification;
use Illuminate\Support\Facades\Notification;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;


class DashboardAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $trainingAll = RegTraining::with('trainingNotifications')->latest()->get();
        $totalTraining = RegTraining::count();
        $totalParticipants = $trainingAll->sum(function ($training) {
            return $training->participants->count(); // ganti 'participants' dengan nama relasi kamu
        });

        return view('dashboard.admin.index', [
            'title' => 'Dashboard Admin',
            'trainingAll' => $trainingAll,
            'totalTraining' => $totalTraining,
            'totalParticipants' => $totalParticipants,
            'admin' => $admin,
        ]);
    }

    public function trainingAll(Request $request)
    {
        $admin = Auth::user();

        $query = RegTraining::with(['trainingNotifications', 'user', 'participants']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name_company', 'like', '%' . $search . '%')
                    ->orWhere('name_pic', 'like', '%' . $search . '%')
                    ->orWhere('activity', 'like', '%' . $search . '%')
                    ->orWhere('no_letter', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('sort') && $request->sort == 'az') {
            $query->orderBy('name_company', 'asc');
        } else {
            $query->latest();
        }

        // Ambil semua data untuk hitung total participants
        $allTrainings = $query->get();
        $totalParticipants = $allTrainings->sum(fn($training) => $training->participants->count());

        $perPage = $request->get('per_page', 20); // default 20 jika tidak diisi
        $trainingAll = $query->paginate($perPage)->appends(request()->query());

        return view('dashboard.admin.cektraining.tabletraining', [
            'title' => 'Dashboard Admin',
            'trainingAll' => $trainingAll,
            'admin' => $admin,
            'totalParticipants' => $totalParticipants,
        ]);
    }

    public function getLiveTraining()
    {
        $trainings = RegTraining::with(['user', 'trainingNotifications.user', 'participants'])->get();

        $data = $trainings->map(function ($training) {

            $adminNotifications = $training->trainingNotifications->filter(function ($notif) {
                return $notif->viewed_at &&
                    $notif->user &&
                    $notif->user->role === 'admin';
            });

            $adminSeen = $adminNotifications->isNotEmpty();
            $lastAdminViewedAt = $adminNotifications->max('viewed_at');

            $isNew = !$adminSeen;
            $isUpdated = $adminSeen && $training->updated_at > $lastAdminViewedAt;

            return [
                'id' => $training->id,
                'user' => $training->user,
                'noLetter' => $training->no_letter,
                'name_pic' => $training->name_pic,
                'name_company' => $training->name_company,
                'activity' => $training->activity,
                'date' => $training->date,
                'date_end' => $training->date_end,
                'isprogress' => $training->isprogress,
                'isNew' => $isNew,
                'isUpdated' => $isUpdated,
                'statusFail' => $training->reason_fail,
                'participants_count' => $training->participants->count(),
                'isfinish' => $training->isfinish,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $training = RegTraining::with(['participants', 'trainingNotifications'])
            ->findOrFail($id);

        $userId = Auth::id();

        TrainingNotification::updateOrCreate(
            ['user_id' => $userId, 'reg_training_id' => $training->id],
            ['viewed_at' => now()]
        );

        $user = Auth::user();
        $trainingId = $training->id;

        // Jika role admin, tandai semua notifikasi admin terkait training ini sebagai terbaca
        if ($user->role === 'admin') {
            $adminUsers = User::where('role', 'admin')->get();

            foreach ($adminUsers as $admin) {
                $admin->unreadNotifications
                    ->where('data.training_id', $trainingId)
                    ->each(function ($notif) {
                        $notif->markAsRead();
                    });
            }
        } else {
            // Kalau bukan admin (misalnya user biasa), tandai notifikasi miliknya saja
            $notification = $user->unreadNotifications
                ->where('data.training_id', $trainingId)
                ->first();

            if ($notification) {
                $notification->markAsRead();
            }
        }
        $fileRequirement = FileRequirement::where('file_id', $training->id)->first();

        return view('dashboard.admin.cektraining.showtraining', [
            'title' => 'Detail Pelatihan',
            'training' => $training,
            'fileRequirement' => $fileRequirement
        ]);
    }


    public function update(Request $request, $id)
    {
        // $training = RegTraining::findOrFail($id);
        $training = RegTraining::where('id', $id)->firstOrFail();

        // Validasi dan update data
        $validated = $request->validate([
            'name_pic' => 'required|string',
            'name_company' => 'required|string',
            'email_pic' => 'required|email',
            'phone_pic' => 'required|string',
            'activity' => 'required|string',
            'date' => 'required|date',
            'place' => 'required|string',
            'city' => 'nullable|string',
            'end_date' => 'nullable|date',
        ]);

        $training->update([
            'name_pic' => $validated['name_pic'],
            'name_company' => $validated['name_company'],
            'email_pic' => $validated['email_pic'],
            'phone_pic' => $validated['phone_pic'],
            'activity' => $validated['activity'],
            'date' => Carbon::parse($validated['date'])->format('Y-m-d'),
            'place' => $validated['place'],
            'city' => $validated['city'],
            'date_end' => $validated['end_date'] ? Carbon::parse($validated['end_date'])->format('Y-m-d') : null,
        ]);


        $targetUser = $training->user;


        $adminName = optional(Auth::user())->name ?? '-';

        $targetUser->notify(new TrainingUpdatedNotification(
            $training,
            'admin',
            'Daftar Pelatihan',
            '',     // customMessage
            '',     // customType
            $adminName
        ));

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.'
        ]);
    }

    public function updateForm2User(Request $request)
    {
        $request->validate([
            'form_id'      => 'required|exists:reg_training,id',
            'participants' => 'required|array',
            'participants.*.status' => 'required|in:0,1,2',
            'participants.*.reason' => 'nullable|string|max:255',
        ]);

        foreach ($request->participants as $id => $data) {
            $participant = RegParticipant::find($id);
            if ($participant) {
                $participant->status = $data['status'];
                $participant->reason = $data['reason'] ?? null;
                $participant->touch();
                $participant->save();
            }
        }
        $adminName = optional(Auth::user())->name ?? '-';
        $training = RegTraining::find($request->form_id);
        if ($training && $training->user) {
            $training->user->notify(new TrainingUpdatedNotification(
                $training,
                'admin',
                'Daftar Peserta',
                'Data peserta pada pelatihan "' . $training->activity . '" telah diperbarui oleh Admin.',
                'info',
                $adminName
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Data peserta berhasil diperbarui!'
        ]);
    }

    public function uploadFileForAdmin(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:reg_training,id',
            'budget_plan' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:2408',
            'letter_implementation' => 'nullable|file|mimes:pdf,doc,docx|max:2408',
        ]);

        $training = RegTraining::find($request->training_id);
        if (!$training) {
            return response()->json(['success' => false, 'message' => 'Training tidak ditemukan.'], 404);
        }

        $noLetter = str_replace([' ', '/', '\\'], '-', strtolower($training->no_letter ?? 'no-surat'));
        $nameTraining = str_replace([' ', '/', '\\'], '-', strtolower($training->activity ?? 'pelatihan'));
        $fileReq = FileRequirement::firstOrNew(['file_id' => $training->id]);

        // SET FILE_ID SAMA DENGAN ID TRAINING
        $fileReq->file_id = $training->id;

        if ($request->hasFile('budget_plan')) {
            $file = $request->file('budget_plan');
            $ekstensi = $file->getClientOriginalExtension();
            $nameFiles = $noLetter . '_' . $nameTraining . '_budget-plan.' . $ekstensi;
            $file->storeAs('budget-plans', $nameFiles);
            $fileReq->budget_plan = 'budget-plans/' . $nameFiles;
        }
        if ($request->hasFile('letter_implementation')) {
            $file = $request->file('letter_implementation');
            $ekstensi = $file->getClientOriginalExtension();
            $nameFiles = $noLetter . '_' . $nameTraining . '_letter-implementation.' . $ekstensi;
            $file->storeAs('letter-implementations', $nameFiles);
            $fileReq->letter_implementation = 'letter-implementations/' . $nameFiles;
        }

        $fileReq->save();
        $uploaderName = optional(Auth::user())->name ?? 'admin';

        $managers = User::where('role', 'management')->get();

        foreach ($managers as $manager) {
            $manager->notify(new TrainingUpdatedNotification(
                $training,
                'admin',
                'Upload Dokumen',
                "{$uploaderName} telah mengunggah dokumen untuk pelatihan {$training->activity}.",
                'info',
                $uploaderName
            ));
        }
        return response()->json(['success' => true]);
    }



    public function trainingFinish(Request $request, $id)
    {
        $training = RegTraining::where('id', $id)->firstOrFail();
        $currentProgress = $training->isprogress;
        $newProgress = $request->input('isprogress');
        $training->update([
            'isprogress' => max($currentProgress, $newProgress),
        ]);

        $customMessage = "Proses verifikasi berhasil. Pengajuan pelatihan {$training->activity} akan segera ditinjau untuk disetujui.";

        $adminName = optional(Auth::user())->name ?? '-';
        if ($training->user) {
            $training->user->notify(new TrainingUpdatedNotification(
                $training,
                'admin',
                'Daftar Pelatihan',
                $customMessage,
                'verifacc',
                $adminName
            ));
        }

        // $managementUsers = User::where('role', 'management')->get();

        // foreach ($managementUsers as $manager) {
        //     $manager->notify(new TrainingUpdatedNotification(
        //         $training,
        //         'admin',
        //         'Daftar Pelatihan',
        //         'Training telah diperbarui oleh Admin. Silakan tinjau.',
        //         'info'
        //     ));
        // }


        return response()->json(['success' => true, 'message' => 'Progress berhasil diperbarui.']);
    }


    public function destroyParticipant($id)
    {
        $participant = RegParticipant::findOrFail($id);
        $training = $participant->training;

        $participant->delete();

        // Kirim notifikasi ke pemilik pelatihan
        if ($training && $training->user) {
            $training->user->notify(new TrainingUpdatedNotification(
                $training,
                'admin',
                'Daftar Peserta',
                'Satu peserta telah dihapus dari pelatihan "' . $training->activity . '".',
                'update'
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil dihapus.'
        ]);
    }
}
