<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegTraining;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = RegTraining::with(['participants', 'files', 'trainingNotifications', 'user'])
            ->where('isprogress', 5);

        // Filter tanggal mulai
        if ($request->filled('startDate')) {
            $query->whereDate('date', '>=', $request->startDate);
        }

        // Filter tanggal selesai
        if ($request->filled('endDate')) {
            $query->whereDate('date_end', '<=', $request->endDate);
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

        return view('dashboard.management.index', [
            'title' => 'Management Summary',
            'data' => $data,
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

        // Simpan reason_fail jika ditolak
        if ($isfinish === 2) {
            $training->reason_fail = $request->input('reason_fail');
        } else {
            // kosongkan alasan jika disetujui atau direset
            $training->reason_fail = null;
        }

        $training->save();

        return response()->json(['success' => true]);
    }
}
