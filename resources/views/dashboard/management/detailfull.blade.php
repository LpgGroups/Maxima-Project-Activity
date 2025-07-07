@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-4xl mx-auto my-10 bg-white p-8 rounded-lg shadow-lg space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 border-b pb-2 mb-4">Detail Pelatihan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700">
                <div>
                    <span class="font-medium text-gray-600">Nama Kegiatan:</span>
                    <div class="mt-1">{{ $training->activity }}</div>
                </div>
                <div>
                    <span class="font-medium text-gray-600">Perusahaan:</span>
                    <div class="mt-1">{{ $training->name_company }}</div>
                </div>
                <div>
                    <span class="font-medium text-gray-600">PIC:</span>
                    <div class="mt-1">
                        {{ $training->name_pic }}<br>
                        <span class="text-xs text-gray-500">{{ $training->email_pic }}, {{ $training->phone_pic }}</span>
                    </div>
                </div>
                <div>
                    <span class="font-medium text-gray-600">Tanggal:</span>
                    <div class="mt-1">
                        {{ \Carbon\Carbon::parse($training->date)->translatedFormat('d F Y') }} -
                        {{ \Carbon\Carbon::parse($training->date_end)->translatedFormat('d F Y') }}
                    </div>
                </div>
                <div>
                    <span class="font-medium text-gray-600">Jumlah Peserta:</span>
                    <div class="mt-1">{{ $training->participants->count() }} orang</div>
                </div>
                <div>
                    <span class="font-medium text-gray-600">Status:</span>
                    <div class="mt-1">
                        @if ($training->isfinish == 1)
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                Selesai
                            </span>
                        @elseif ($training->isfinish == 2)
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                Ditolak
                            </span>
                        @else
                            <span
                                class="inline-block px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                Proses
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Kelengkapan Berkas --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Berkas Approval</h3>

            @if ($training->approvalFiles && count($training->approvalFiles))
                <ul class="space-y-2 text-sm text-gray-700">
                    @foreach ($training->approvalFiles as $file)
                        <li class="flex flex-col md:flex-row md:items-center md:space-x-3 bg-gray-50 p-3 rounded-md border">
                            <div class="font-medium text-gray-600">Dokumen:</div>
                            <div class="flex-1 space-x-2 mt-1 md:mt-0">
                                @if ($file->file_approval)
                                    <a href="{{ asset('storage/' . $file->file_approval) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Approval File</a>
                                @endif
                                @if ($file->proof_payment)
                                    <a href="{{ asset('storage/' . $file->proof_payment) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Bukti Bayar</a>
                                @endif
                                @if ($file->budget_plan)
                                    <a href="{{ route('download.confidential', ['type' => 'budget-plan', 'file' => basename($file->budget_plan)]) }}"
                                        target="_blank" class="text-blue-600 hover:underline">Budget Plan</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm italic text-gray-500">Tidak ada berkas approval yang tersedia.</p>
            @endif
        </div>
    </div>
@endsection
