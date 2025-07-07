<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegTraining;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = RegTraining::with(['participants', 'files', 'trainingNotifications', 'user'])
            ->where('isprogress', 5);

        if ($request->filled('startDate')) {
            $query->whereDate('date_end', '>=', $request->startDate); // Data yang belum selesai pada startDate
        }

        if ($request->filled('endDate')) {
            $query->whereDate('date', '<=', $request->endDate); // Data yang sudah mulai sebelum endDate
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_letter', 'like', '%' . $search . '%')
                    ->orWhere('activity', 'like', '%' . $search . '%')
                    ->orWhere('name_company', 'like', '%' . $search . '%');
            });
        }

        // Sorting berdasarkan nama perusahaan (name_company)
        if ($request->filled('sortCompany')) {
            $sortDirection = $request->sortCompany === 'desc' ? 'desc' : 'asc';
            $query->orderBy('name_company', $sortDirection);
        } else {
            // Default: sorting berdasarkan created_at terbaru
            $query->orderBy('created_at', 'desc');
        }

        $data = $query->get();

        $notifications = Auth::user()->unreadNotifications;
        return view('dashboard.management.index', [
            'title' => 'Management Summary',
            'data' => $data,
            'notifications' => $notifications,
        ]);
    }


    public function getData()
    {
        $data = RegTraining::with(['participants', 'files', 'trainingNotifications', 'user'])
            ->where('isprogress', 5)
            ->orderBy('created_at', 'desc') // Menampilkan data terbaru duluan
            ->get();

        return response()->json($data);
    }

    public function showDetail($id)
    {
        $training = RegTraining::with('participants', 'approvalFiles')->findOrFail($id);

        return response()->json([
            'activity'     => $training->activity,
            'no_letter'     => $training->no_letter,
            'pic'          => $training->name_pic,
            'email'          => $training->email_pic,
            'phone'          => $training->phone_pic,
            'company'      => $training->name_company,
            'isfinish' => $training->isfinish,
            'participants' => $training->participants->count(),
            'date'         => Carbon::parse($training->date)->translatedFormat('d F Y'),
            'date_end'     => Carbon::parse($training->date_end)->translatedFormat('d F Y'),
            'files' => $training->approvalFiles->map(function ($file) {
                return [
                    'id'                    => $file->id,
                    'file_approval'         => $file->file_approval ? asset('storage/' . $file->file_approval) : null,
                    'proof_payment'         => $file->proof_payment ? asset('storage/' . $file->proof_payment) : null,
                    'budget_plan' => $file->budget_plan
                        ? route('download.confidential', ['type' => 'budget-plan', 'file' => basename($file->budget_plan)])
                        : null,
                    'letter_implementation' => $file->letter_implementation
                        ? route('download.confidential', ['type' => 'letter-implementation', 'file' => basename($file->letter_implementation)])
                        : null,
                    'created_at'            => $file->created_at ? $file->created_at->format('d-m-Y H:i') : null,
                    // bisa tambahkan kolom lain sesuai kebutuhan
                ];
            }),

        ]);
    }
    public function approve(Request $request, $id)
    {
        $training = RegTraining::findOrFail($id);
        $isfinish = (int) $request->input('isfinish', 0);

        if ($isfinish === 2 && empty($request->input('reason_fail'))) {
            return response()->json([
                'success' => false,
                'message' => 'Alasan penolakan wajib diisi.'
            ], 422);
        }

        $training->isfinish = $isfinish;

        if ($isfinish === 2) {
            $training->reason_fail = $request->input('reason_fail');
        } else {
            $training->reason_fail = null;
        }

        $training->save();

        // === Kirim Notifikasi WhatsApp ===
        $phone = $training->phone_pic;

        if (empty($phone) || !is_string($phone)) {
            Log::warning("WhatsApp not sent: No phone number in reg_training ID {$training->id}");
        } else {
            try {
                $client = new \GuzzleHttp\Client();
                $response = $client->post('https://app.maxchat.id/api/messages/push', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('MAXCHAT_TOKEN'),
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                    ],
                    'json' => [
                        'to' => $phone,
                        'msgType' => 'text',
                        'templateId' => env('MAXCHAT_TEMPLATE'),
                    ],
                ]);
                $status = $response->getStatusCode();
                $body = json_decode($response->getBody()->getContents(), true);
                if ($status == 200 && !isset($body['error'])) {
                    Log::info("SUKSES kirim WA ke $phone (reg_training id: {$training->id}): " . json_encode($body));
                } else {
                    Log::error("GAGAL kirim WA ke $phone (reg_training id: {$training->id}): " . json_encode($body));
                }
            } catch (\Exception $e) {
                Log::error("Gagal kirim WhatsApp Maxchat: " . $e->getMessage());
            }
        }


        // Kirim notifikasi ke semua admin
        $adminUsers = User::where('role', 'admin')->get();

        foreach ($adminUsers as $admin) {
            $admin->notify(new \App\Notifications\TrainingUpdatedNotification(
                $training,
                'admin',
                '',
                "Status pelatihan {$training->activity} telah di-" . ($isfinish == 1 ? 'approve' : 'tolak') . ".",
                $isfinish == 1 ? 'success' : 'denied',
                $request->user()->name ?? 'Admin'
            ));
        }
        return response()->json(['success' => true]);
    }

    public function getManagementNotifications()
    {
        $notifications = Auth::user()->notifications // atau ->unreadNotifications()
            ->where('type', 'App\Notifications\TrainingUpdatedNotification')
            ->latest()
            ->get();

        return view('dashboard.management.notifications', compact('notifications'));
    }

    public function detailView($id)
    {
        $training = RegTraining::with(['participants', 'approvalFiles', 'files', 'user'])->findOrFail($id);

        $user = Auth::user(); // <-- Tambah ini

        if ($user->role === 'management') {
            $manajemenUsers = User::where('role', 'management')->get();

            foreach ($manajemenUsers as $manajemen) {
                $manajemen->unreadNotifications
                    ->where('data.training_id', $training->id)
                    ->each(function ($notif) {
                        $notif->markAsRead();
                    });
            }
        }

        return view('dashboard.management.detailfull', [
            'training' => $training,
            'title' => 'Detail Pelatihan',
        ]);
    }
}
