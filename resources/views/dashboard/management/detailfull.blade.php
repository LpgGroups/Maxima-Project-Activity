@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-4xl mx-auto my-10 bg-white p-8 rounded-lg shadow-lg space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 border-b pb-2 mb-4">Detail Pelatihan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700">
                <div>
                    <span class="font-bold text-gray-600">Nama Kegiatan:</span>
                    <div class="mt-1">{{ config('activity_map.' . $training->activity) ?? $training->activity }}</div>
                </div>
                <div>
                    <span class="font-bold text-gray-600">Lokasi Kegiatan:</span>
                    <div class="mt-1">{{$training->place}} - {{ $training->city ?? 'Tidak Ada' }}</div>
                </div>
                <div>
                    <span class="font-bold text-gray-600">Perusahaan:</span>
                    <div class="mt-1">{{ $training->name_company }}</div>
                </div>
                <div>
                    <span class="font-bold text-gray-600">Nama PIC:</span>
                    <div class="mt-1">
                        {{ $training->name_pic }}<br>
                        <span class="text-xs text-gray-500">{{ $training->email_pic }}, {{ $training->phone_pic }}</span>
                    </div>
                </div>
                <div>
                    <span class="font-bold text-gray-600">Tanggal Pelatihan:</span>
                    <div class="mt-1">
                        {{ \Carbon\Carbon::parse($training->date)->translatedFormat('d F Y') }} -
                        {{ \Carbon\Carbon::parse($training->date_end)->translatedFormat('d F Y') }}
                    </div>
                </div>
                <div>
                    <span class="font-bold text-gray-600">Jumlah Peserta:</span>
                    <div class="mt-1">{{ $training->participants->count() }} peserta</div>
                </div>
                <div>
                    <span class="font-bold text-gray-600">Status:</span>
                    <div class="mt-1">
                        @if ($training->isfinish == 1)
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                Pelatihan Di Setujui
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
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Dokumen {{ $training->name_company }}</h3>

            @if ($training->approvalFiles && count($training->approvalFiles))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($training->approvalFiles as $file)
                        @if ($file->file_approval)
                            <div class="flex items-center space-x-4 p-4 border rounded-lg bg-gray-50">
                                <img src="{{ asset('img/icon_pdf_mou.png') }}" alt="Dokumen" class="w-12 h-12">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800">File Persetujuan</div>
                                    <a href="{{ asset('storage/' . $file->file_approval) }}" target="_blank"
                                        class="inline-block mt-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($file->proof_payment)
                            <div class="flex items-center space-x-4 p-4 border rounded-lg bg-gray-50">
                                <img src="{{ asset('img/icon_pdf_mou.png') }}" alt="Dokumen" class="w-12 h-12">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800">Bukti Bayar</div>
                                    <a href="{{ asset('storage/' . $file->proof_payment) }}" target="_blank"
                                        class="inline-block mt-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($file->letter_implementation)
                            <div class="flex items-center space-x-4 p-4 border rounded-lg bg-gray-50">
                                <img src="{{ asset('img/icon_pdf_mou.png') }}" alt="Dokumen" class="w-12 h-12">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800">Surat Pelaksanaan</div>
                                    <a href="{{ asset('storage/' . $file->letter_implementation) }}" target="_blank"
                                        class="inline-block mt-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($file->budget_plan)
                            <div class="flex items-center space-x-4 p-4 border rounded-lg bg-gray-50">
                                <img src="{{ asset('img/icon_pdf_mou.png') }}" alt="Dokumen" class="w-12 h-12">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800">Budget Plan</div>
                                    <a href="{{ route('download.confidential', ['type' => 'budget-plan', 'file' => basename($file->budget_plan)]) }}"
                                        target="_blank"
                                        class="inline-block mt-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif


                        @if ($file->file_nobatch)
                            <div class="flex items-center space-x-4 p-4 border rounded-lg bg-gray-50">
                                <img src="{{ asset('img/icon_pdf_mou.png') }}" alt="Dokumen" class="w-12 h-12">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800">File No Batch</div>
                                    <a href="{{ route('download.confidential', ['type' => 'file-nobatch', 'file' => basename($file->file_nobatch)]) }}"
                                        target="_blank"
                                        class="inline-block mt-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-sm italic text-gray-500">Tidak ada berkas approval yang tersedia.</p>
            @endif
        </div>
    </div>
@endsection
