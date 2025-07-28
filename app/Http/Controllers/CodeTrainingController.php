<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CarrouselAds;
use App\Models\RegTraining;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CodeTrainingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $trainings = RegTraining::when($search, function ($query, $search) {
            return $query->where('code_training', $search);
        })->get();

        $carrousel = CarrouselAds::where('is_active', true)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderBy('order')
            ->get()
            ->map(function ($item) {
                $path = storage_path('app/public/' . $item->image);
                if (file_exists($path)) {
                    [$width, $height] = getimagesize($path);
                    $item->is_small = $width < 1139 || $height < 450;
                } else {
                    $item->is_small = true; // fallback jika file tidak ditemukan
                }
                return $item;
            });

        return view('code.index', [
            'title' => 'Search Training',
            'trainings' => $trainings,
            'carrousel' => $carrousel,
        ]);
    }

    public function show($id)
    {
        $training = RegTraining::with('participants', 'approvalFiles')->findOrFail($id);

        return view('code.show', [
            'training' => $training,
        ]);
    }
}
