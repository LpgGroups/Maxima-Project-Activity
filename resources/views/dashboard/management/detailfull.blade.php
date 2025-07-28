@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-4xl mx-auto my-10 bg-white p-8 rounded-lg shadow-lg space-y-8">
        <div>
            <div class="flex items-center border-b pb-2 mb-4">
                <button onclick="window.history.back()"
                    class="bg-gray-100 hover:bg-gray-300 text-gray-800 text-sm font-semibold py-1.5 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>

                </button>
                <h2 class="ml-2 text-2xl font-bold text-gray-800">Detail Pelatihan</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700">
                <div>
                    <span class="font-bold text-gray-600">No Surat Kegiatan:</span>
                    <div class="mt-1">{{ $training->no_letter }}</div>
                </div>
                <div>
                    <span class="font-bold text-gray-600">Nama Kegiatan:</span>
                    <div class="mt-1">{{ config('activity_map.' . $training->activity) ?? $training->activity }}</div>
                </div>
                <div>
                    <span class="font-bold text-gray-600">Lokasi Kegiatan:</span>
                    <div class="mt-1">{{ $training->place }} - {{ $training->city ?? 'Tidak Ada' }}</div>
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
                            <p>Dengan Alasan:</p>
                            <p class="text-red-500 italic">{{ $training->reason_fail }}</p>
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
                                    <a href="{{ route('download.confidential', ['type' => 'letter-implementation', 'file' => basename($file->letter_implementation)]) }}"
                                        target="_blank"
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

            @if ($training->isprogress === 5)
                @php
                    $activityFull = config('map.activity_map.' . $training->activity) ?? $training->activity;

                    $reviewData = [
                        'id' => $training->id,
                        'activity' => $training->activity,
                        'activity_full' => $activityFull,
                        'name_company' => $training->name_company,
                        'name_pic' => $training->name_pic,
                        'participants' => $training->participants->count(),
                    ];
                @endphp

                <div class="relative inline-block mt-4">
                    <button type="button"
                        class="px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600 transition duration-200"
                        onclick='accDetail({!! json_encode($reviewData) !!})' title="Review dan Tinjau Pelatihan">
                        Review & Tinjau
                    </button>
                </div>
            @endif

        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@push('scripts')
    <script>
        function accDetail(data) {
            Swal.fire({
                title: `<strong>${data.activity_full || data.activity}</strong>`,
                html: `
            <p><strong>Nama Perusahaan:</strong> ${data.name_company}</p>
            <p><strong>PIC:</strong> ${data.name_pic}</p>
            <p><strong>Jumlah Peserta:</strong> ${data.participants}</p>
        `,
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Setujui",
                confirmButtonColor: "#3085d6",
                showDenyButton: true,
                denyButtonText: "Tolak",
                denyButtonColor: "#d33",
                cancelButtonText: "Tutup",
                allowOutsideClick: () => !Swal.isLoading(),
                allowEscapeKey: () => !Swal.isLoading(),

                preConfirm: () => {
                    Swal.showLoading();
                    return $.ajax({
                        url: `/dashboard/management/approve/${data.id}`,
                        type: "PUT",
                        contentType: "application/json",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        data: JSON.stringify({
                            isfinish: 1
                        }),
                    }).catch(() => {
                        Swal.showValidationMessage("Gagal menyetujui data.");
                    });
                },

                preDeny: async () => {
                    const {
                        isConfirmed,
                        value
                    } = await Swal.fire({
                        title: "Alasan Penolakan",
                        input: "textarea",
                        inputLabel: "Wajib isi alasan mengapa ditolak",
                        inputPlaceholder: "Tuliskan alasan di sini...",
                        inputAttributes: {
                            "aria-label": "Tuliskan alasan di sini",
                        },
                        inputValidator: (val) => !val && "Alasan penolakan wajib diisi!",
                        showCancelButton: true,
                        cancelButtonText: "Batal",
                        confirmButtonText: "Kirim Penolakan",
                        confirmButtonColor: "#d33",
                    });

                    if (!isConfirmed) return false;

                    Swal.showLoading();
                    return $.ajax({
                        url: `/dashboard/management/approve/${data.id}`,
                        type: "PUT",
                        contentType: "application/json",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        data: JSON.stringify({
                            isfinish: 2,
                            reason_fail: value,
                        }),
                    }).catch(() => {
                        Swal.showValidationMessage("Gagal menolak data.");
                    });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil Disetujui",
                        text: "Data telah disetujui.",
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => location.reload());
                } else if (result.isDenied) {
                    Swal.fire({
                        icon: "warning",
                        title: "Ditolak",
                        text: "Data telah ditolak.",
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => location.reload());
                }
            });
        }
    </script>
