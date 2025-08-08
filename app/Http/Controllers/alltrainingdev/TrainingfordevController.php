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
            $training = RegTraining::findOrFail($id);

            // ✅ Validasi nomor surat
            if ($request->input('confirm_letter') !== $training->no_letter) {
                return redirect()->back()->with('error', 'Nomor surat tidak cocok. Gagal menghapus pelatihan.');
            }

            // Hapus semua peserta
            $training->participants()->delete();

            // Hapus folder file terkait
            Storage::disk('public')->deleteDirectory("uploads/training/{$id}");

            // Hapus data pelatihan
            $training->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Pelatihan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus pelatihan: ' . $e->getMessage());
        }
    }
}
