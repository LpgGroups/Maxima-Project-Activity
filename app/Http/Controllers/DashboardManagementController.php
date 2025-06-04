<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegTraining;
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
}
