<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegTraining;


class DashboardAdminController extends Controller
{
    public function index()
    {
        $trainingAll = RegTraining::latest()->get();

        $totalTraining = RegTraining::count();
        return view('dashboard.admin.index', [
            'title' => 'Dashboard Admin',
            'trainingAll' => $trainingAll,
            'totalTraining' => $totalTraining,
        ]);
    }
}
