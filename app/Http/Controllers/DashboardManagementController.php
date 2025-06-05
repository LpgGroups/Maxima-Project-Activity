<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegTraining;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardManagementController extends Controller
{
    public function index()
    {
        $data = RegTraining::with(['participants', 'files', 'trainingNotifications', 'user'])->where('isprogress', 5)->get();

        return view('dashboard.management.index', [
            'title' => 'Management Summary',
            'data' => $data
        ]);
    }

    public function getData()
    {
        $data = RegTraining::with(['participants', 'files', 'trainingNotifications', 'user'])
            ->where('isprogress', 5)
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
            'files'        => $training->approvalFiles->map(function ($file) {
                return [
                    'name' => 'File Persetujuan',
                    'url'  => $file->file_approval ? asset('storage/' . $file->file_approval) : null,
                ];
            }),
        ]);
    }
    public function approve(Request $request, $id)
    {
        $training = RegTraining::findOrFail($id);

        // Baca nilai dari request body
        $isfinish = $request->input('isfinish', false);

        // Simpan nilai ke database
        $training->isfinish = $isfinish;
        $training->save();

        return response()->json(['success' => true]);
    }
}
