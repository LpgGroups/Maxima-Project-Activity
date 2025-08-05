<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaintanceFolderController extends Controller
{
    public function index()
    {
        // List semua subfolder
        $subfolders = Storage::disk('public')->directories('uploads/participants');
        $folders = [];
        foreach ($subfolders as $dir) {
            $folders[] = [
                'name' => basename($dir),
                'count' => count(Storage::disk('public')->files($dir)),
            ];
        }
        return view('dashboard.dev.folder.index', [
            "title" => "Folder ALL",
            "folders" => $folders,
        ]);
    }

    public function show($folderName)
    {
        $folderPath = 'uploads/participants/' . $folderName;

        // Cek folder exist
        if (!Storage::disk('public')->exists($folderPath)) {
            return redirect()->route('folder.index')->with('error', 'Folder tidak ditemukan.');
        }

        // Map folder name ke kolom field RegParticipant
        $fieldMap = [
            'photo' => 'photo',
            'ijazah' => 'ijazah',
            'letter_employee' => 'letter_employee',
            'letter_statement' => 'letter_statement',
            'form_registration' => 'form_registration',
            'letter_health' => 'letter_health',
            'cv' => 'cv',
        ];
        $field = $fieldMap[$folderName] ?? null;

        // Jika folder tidak valid (tidak ada mapping), return kosong
        if (!$field) {
            return view('dashboard.dev.folder.file', [
                "title" => "Isi Folder: $folderName",
                "folderName" => $folderName,
                "files" => collect([]),
            ]);
        }

        $files = collect(Storage::disk('public')->files($folderPath))
            ->map(function ($file) use ($field) {

                $basename = basename($file);
                $participant = $field ? \App\Models\RegParticipant::where($field, 'like', "%$basename")->first() : null;
                $training = $participant ? $participant->training : null;
                return [
                    'basename' => $basename,
                    'url' => asset('storage/uploads/participants/' . $field . '/' . $basename),
                    'delete_route' => route('folder.file.delete', ['folderName' => $field, 'fileName' => $basename]),
                    'download_route' => route('folder.file.download', ['folderName' => $field, 'fileName' => $basename]),
                    'training' => $training,
                ];
            });

        // --- FILTER SERVER SIDE ---
        $status = request('status');
        $date_start = request('date_start');
        $date_end = request('date_end');
        $sort = request('sort');

        $files = $files->filter(function ($file) use ($status, $date_start, $date_end) {
            $t = $file['training'];
            if (!$t) return false;

            // Filter status
            if ($status) {
                if ($status === '5-1' && !($t->isprogress == 5 && $t->isfinish == 1)) return false; // Selesai
                if ($status === '5-2' && !($t->isprogress == 5 && $t->isfinish == 2)) return false; // Ditolak
                if ($status === '5-0' && !($t->isprogress == 5 && $t->isfinish == 0)) return false; // Menunggu Konfirmasi Manajemen
                if ($status === '4-0' && !($t->isprogress < 5)) return false; // Proses
            }

            // Filter tanggal start & end
            if ($date_start && (\Carbon\Carbon::parse($t->date)->lt($date_start))) return false;
            if ($date_end && (\Carbon\Carbon::parse($t->date)->gt($date_end))) return false;
            return true;
        });

        // --- SORTING ---
        if ($sort == 'terbaru') {
            $files = $files->sortByDesc(function ($file) {
                $t = $file['training'];
                return $t ? ($t->date_end ?? $t->date) : null;
            });
        } elseif ($sort == 'terlama') {
            $files = $files->sortBy(function ($file) {
                $t = $file['training'];
                return $t ? ($t->date ?? null) : null;
            });
        }

        $files = $files->values(); // reset index

        return view('dashboard.dev.folder.file', [
            "title" => "Isi Folder: $folderName",
            "folderName" => $folderName,
            "files" => $files,
        ]);
    }

    public function bulkDownload(Request $request, $folderName)
    {
        $fileNames = $request->input('files', []);
        if (is_string($fileNames)) {
            $fileNames = explode(',', $fileNames);
        }
        if (empty($fileNames)) {
            return back()->with('error', 'Tidak ada file yang dipilih untuk di-download.');
        }

        // Nama zip = nama folder + tanggal download
        $zipFileName = $folderName . '_' . now()->format('Ymd_His') . '.zip';
        $zipPath = storage_path("app/temp/$zipFileName");

        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($fileNames as $fileName) {
                $filePath = storage_path("app/public/uploads/participants/{$folderName}/{$fileName}");
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $fileName);
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
    public function downloadFile($folderName, $fileName)
    {
        $filePath = storage_path("app/public/uploads/participants/{$folderName}/{$fileName}");
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Download dengan nama aslinya
        return response()->download($filePath, $fileName);
    }
    public function deleteFile($folderName, $fileName)
    {
        $filePath = "uploads/participants/{$folderName}/{$fileName}";

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return back()->with('success', 'File berhasil dihapus!');
        } else {
            return back()->with('error', 'File tidak ditemukan!');
        }
    }

    public function bulkDelete(Request $request, $folderName)
    {
        $fileNames = $request->input('files', []);
        $deleted = 0;
        $folderPath = 'uploads/participants/' . $folderName;
        Log::info('METHOD: ' . $request->method());
        foreach ($fileNames as $fileName) {
            $filePath = $folderPath . '/' . $fileName;
            Log::info("Try delete: $filePath");
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                Log::info("Deleted: $filePath");
                $deleted++;
            } else {
                Log::warning("File not found: $filePath");
            }
        }
        return back()->with('success', "$deleted file berhasil dihapus.");
    }
}
