@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-2xl mx-auto my-10 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-3">Detail Pelatihan</h2>
        <dl>
            <dt class="font-semibold">Nama Kegiatan:</dt>
            <dd class="mb-2">{{ $training->activity }}</dd>

            <dt class="font-semibold">Perusahaan:</dt>
            <dd class="mb-2">{{ $training->name_company }}</dd>

            <dt class="font-semibold">PIC:</dt>
            <dd class="mb-2">{{ $training->name_pic }} ({{ $training->email_pic }}, {{ $training->phone_pic }})</dd>

            <dt class="font-semibold">Tanggal:</dt>
            <dd class="mb-2">
                {{ \Carbon\Carbon::parse($training->date)->translatedFormat('d F Y') }} -
                {{ \Carbon\Carbon::parse($training->date_end)->translatedFormat('d F Y') }}
            </dd>

            <dt class="font-semibold">Jumlah Peserta:</dt>
            <dd class="mb-2">{{ $training->participants->count() }}</dd>

            <dt class="font-semibold">Status:</dt>
            <dd class="mb-2">
                @if ($training->isfinish == 1)
                    <span class="px-2 py-1 rounded bg-green-100 text-green-800">Selesai</span>
                @elseif ($training->isfinish == 2)
                    <span class="px-2 py-1 rounded bg-red-100 text-red-800">Ditolak</span>
                @else
                    <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800">Proses</span>
                @endif
            </dd>
        </dl>

        {{-- List File atau Approval (jika ada) --}}
        <div class="mt-4">
            <h3 class="font-semibold">Berkas Approval</h3>
            @if ($training->approvalFiles && count($training->approvalFiles))
                <ul class="list-disc pl-5">
                    @foreach ($training->approvalFiles as $file)
                        <li>
                            @if ($file->file_approval)
                                <a href="{{ asset('storage/' . $file->file_approval) }}" target="_blank"
                                    class="text-blue-600 underline">Approval File</a>
                            @endif
                            @if ($file->proof_payment)
                                | <a href="{{ asset('storage/' . $file->proof_payment) }}" target="_blank"
                                    class="text-blue-600 underline">Bukti Bayar</a>
                            @endif
                            @if ($file->budget_plan)
                                | <a href="{{ route('download.confidential', ['type' => 'budget-plan', 'file' => basename($file->budget_plan)]) }}"
                                    target="_blank" class="text-blue-600 underline">Budget Plan</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">Tidak ada berkas approval.</p>
            @endif
        </div>
    </div>
@endsection
