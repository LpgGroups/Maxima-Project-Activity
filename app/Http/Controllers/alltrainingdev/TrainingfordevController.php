<?php

namespace App\Http\Controllers\alltrainingdev;

use App\Http\Controllers\Controller;
use App\Models\RegTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TrainingfordevController extends Controller
{
    public function index(Request $request)
    {
        $query = RegTraining::with(['user', 'participants']);

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
        if ($request->filled('isprogress')) {
            if ($request->isprogress == 'proses') {
                $query->where(function ($q) {
                    $q->where('isprogress', '<', 5)
                        ->orWhere(function ($q2) {
                            $q2->where('isprogress', 5)
                                ->where('isfinish', 0);
                        });
                });
            } elseif ($request->isprogress == 'selesai') {
                $query->where('isprogress', 5)
                    ->where('isfinish', 1);
            } elseif ($request->isprogress == 'ditolak') {
                $query->where('isprogress', 5)
                    ->where('isfinish', 2);
            } elseif ($request->isprogress == 'menunggu') {
                $query->where(function ($q) {
                    $q->where('isprogress', '>', 4)->where('isfinish', 0);
                });
            }
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

        return view('dashboard.dev.alltraining.index', [
            'title' => 'Dashboard Admin',
            'trainingAll' => $trainingAll,
            'totalParticipants' => $totalParticipants,
        ]);
    }


    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $training = RegTraining::with(['participants', 'approvalFiles'])->findOrFail($id);

            // Validasi nomor surat
            $posted = trim((string)$request->input('confirm_letter'));
            $expected = trim((string)($training->no_letter ?? ''));
            if ($posted !== $expected) {
                return back()->with('error', 'Nomor surat tidak cocok. Gagal menghapus pelatihan.');
            }

            $fileFields = [
                'photo',
                'ijazah',
                'letter_employee',
                'letter_statement',
                'form_registration',
                'letter_health',
                'cv',
            ];

            foreach ($training->participants as $participant) {
                foreach ($fileFields as $field) {
                    $path = $participant->$field;
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            // (Opsional) Hapus file-file requirement pelatihan jika ada (dari form3)
            if ($training->fileRequirement) {
                $req = $training->fileRequirement;
                foreach (['file_approval', 'proof_payment'] as $field) {
                    $path = $req->$field;
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
                $req->delete();
            }

            // ========== HAPUS DATA DB ==========
            // hapus peserta
            $training->participants()->delete();

            // hapus folder pelatihan (kalau kamu memang bikin folder ini)
            Storage::disk('public')->deleteDirectory("uploads/training/{$id}");

            // hapus record training
            $training->delete();

            DB::commit();
            return back()->with('success', 'Pelatihan dan semua file terkait berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pelatihan: ' . $e->getMessage());
        }
    }
}
