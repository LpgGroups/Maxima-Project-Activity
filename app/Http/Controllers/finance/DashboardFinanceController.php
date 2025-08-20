<?php

namespace App\Http\Controllers\finance;

use App\Http\Controllers\Controller;
use App\Models\RegTraining;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardFinanceController extends Controller
{
    public function index()
    {
        // Halaman kosong + komponen live akan fetch sendiri
        return view('dashboard.finance.index', [
            "title" => "Dtest",
        ]);
    }

    // DashboardFinanceController.php
    public function show($id)
    {
        $training = RegTraining::with('approvalFiles')->findOrFail($id);

        // Contoh format tanggal aman
        $tgl = null;
        try {
            if ($training->date instanceof \DateTimeInterface) {
                $tgl = $training->date->format('d M Y');
            } elseif (is_string($training->date) && trim($training->date) !== '') {
                $tgl = Carbon::parse($training->date)->format('d M Y');
            }
        } catch (\Throwable $e) {
            $tgl = null;
        }

        $user = Auth::user();
        $trainingId = $training->id;

        if ($user->role === 'finance') {
            $financeUsers = User::where('role', 'finance')->get();
            foreach ($financeUsers as $finance) {
                $finance->unreadNotifications->where('data.training_id', $trainingId)->each(function ($notif) {
                    $notif->markAsRead();
                });
            }
        } else {
            $notification = $user->unreadNotifications
                ->where('data.training_id', $trainingId)
                ->first();

            if ($notification) {
                $notification->markAsRead();
            }
        }

        return view('dashboard.finance.show', [
            'title'    => 'Detail Pelatihan',
            'training' => $training,
            'date_fmt' => $tgl,
        ]);
    }


    public function data(Request $request)
    {
        $pageSize = (int) $request->get('per_page', 10);

        $query = RegTraining::query()
            ->with('approvalFiles')
            ->orderByDesc('created_at');


        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('no_letter', 'like', "%{$search}%")
                    ->orWhere('activity', 'like', "%{$search}%")
                    ->orWhere('name_company', 'like', "%{$search}%");
            });
        }

        $paginator = $query->paginate($pageSize)->appends($request->query());

        $items = collect($paginator->items())->map(function ($t, $i) use ($paginator) {
            // format date yang aman (string / null / carbon)
            $tgl = null;
            try {
                if ($t->date instanceof \DateTimeInterface) {
                    $tgl = $t->date->format('d M Y');
                } elseif (is_string($t->date) && trim($t->date) !== '') {
                    $tgl = Carbon::parse($t->date)->format('d M Y');
                }
            } catch (\Throwable $e) {
                $tgl = null;
            }

            $proofFile = $t->approvalFiles->firstWhere('proof_payment', '!=', null);

            $statusUpload = $proofFile
                ? 'Sudah diupload'
                : 'Belum diupload';
            return [
                'id'           => $t->id,
                'no'         => ($paginator->firstItem() ?? 1) + $i,
                'no_letter'   => $t->no_letter,
                // sementara ambil dari kolom fallback di tabelnya
                'pic'        => $t->name_pic ?? '-',
                'company' => $t->name_company ?? '-',
                'activity'  => $t->activity,
                'provience'  => $t->provience,
                'city'  => $t->city,
                'date'    => $tgl,
                'progress'   => (int) ($t->progress_percent ?? 0),
                'status_upload' => $statusUpload,
            ];
        });

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'last_page'    => $paginator->lastPage(),
                'total'        => $paginator->total(),
                'first_item'   => $paginator->firstItem(),
                'last_item'    => $paginator->lastItem(),
            ],
        ]);
    }
}
