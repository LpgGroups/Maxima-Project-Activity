<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileDownloadController extends Controller
{
    public function download($type, $file)
    {
        // Validasi user, hanya admin/management
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'management'])) {
            abort(403, 'Anda tidak berhak mengakses file ini.');
        }

        // Tentukan folder berdasarkan type
        $folders = [
            'budget-plan' => 'budget-plans',
            'letter-implementation' => 'letter-implementations',
        ];
        if (!array_key_exists($type, $folders)) {
            abort(404, 'Jenis file tidak dikenali');
        }

        $path = storage_path('app/private/' . $folders[$type] . '/' . $file);
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        // Download file
        return response()->download($path);
    }
}
