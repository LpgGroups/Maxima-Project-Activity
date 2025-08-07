@extends('layout.main')
@section('container')
    <div class="max-w-4xl mx-auto my-10 bg-white p-8 rounded-lg shadow-lg space-y-8">
        <div>
            <div class="flex items-center border-b pb-2 mb-4">
                <a href="{{ route('code.index') }}"
                    class="bg-gray-100 hover:bg-gray-300 text-gray-800 text-sm font-semibold py-1.5 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>

                </a>
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
                    <div class="mt-1"><strong>{{ $training->place }}</strong> - {{ $training->provience ?? 'Tidak Ada' }}
                        - {{ $training->city ?? '' }}</div>
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
                        @else
                            <span
                                class="inline-block px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                                Proses
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <span class="font-bold text-gray-600">Link Laporan Kegiatan:</span>
                    <div class="mt-2">
                        @if ($training->link && filter_var($training->link, FILTER_VALIDATE_URL))
                            <a href="{{ $training->link }}" target="_blank"
                                class="inline-flex items-center gap-2 bg-purple-200 text-purple-800 text-sm font-semibold px-4 py-2 rounded-md hover:bg-purple-300 transition">

                                <!-- SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14 3h7v7m0-7L10 14M5 5v14h14v-7" />
                                </svg>

                                <span>Buka Laporan - {{ $training->activity }}</span>
                            </a>
                        @else
                            <span class="text-gray-500 italic text-[12px]">Belum ada link laporan kegiatan (Link ini di
                                update
                                ketika pelatihan sudah selesai oleh admin sebagai dokumentasi pelatihan).</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <h3 class="text-lg font-semibold text-gray-800 border-b">Peserta {{ $training->name_company }}</h3>
        </div>

        <div class="rounded-2xl w-full overflow-hidden">
            <div class="max-h-96 overflow-y-auto rounded-2xl border border-gray-200">
                <table class="table-auto w-full text-center align-middle">
                    <thead class="sticky top-0 bg-slate-600 text-white text-[8px] lg:text-sm z-10">
                        <tr>
                            <th class="w-[10px]">No</th>
                            <th class="w-[100px]">Peserta</th>
                            <th class="w-[80px]">Status</th>
                            <th class="w-[200px]">Informasi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[8px] lg:text-[12px]">
                        @forelse ($training->participants->sortBy('name') as $index => $participant)
                            <tr class="odd:bg-white even:bg-gray-100">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $participant->name }}</td>

                                @php
                                    $statusInfo = [
                                        0 => ['file' => 'waiting.svg', 'label' => 'Waiting'],
                                        1 => ['file' => 'success.svg', 'label' => 'Success'],
                                        2 => ['file' => 'rejected.svg', 'label' => 'Rejected'],
                                    ];
                                    $info = $statusInfo[$participant->status] ?? [
                                        'file' => 'unknown.svg',
                                        'label' => 'Unknown',
                                    ];
                                @endphp

                                <td class="text-center relative group py-1">
                                    <img src="{{ asset('img/svg/' . $info['file']) }}" alt="{{ $info['label'] }}"
                                        class="w-4 h-4 mx-auto cursor-pointer">
                                    <div
                                        class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-10">
                                        {{ $info['label'] }}
                                    </div>
                                </td>

                                <td>
                                    @php
                                        $statusReasonMap = [
                                            0 => 'Dalam Pemeriksaan',
                                            1 => 'Berhasil Terverifikasi',
                                        ];
                                        $rawReason = $participant->reason;
                                        if (!empty($rawReason)) {
                                            $displayReason = e($rawReason);
                                        } else {
                                            $statusBasedReason =
                                                $statusReasonMap[$participant->status] ?? 'tidak ada catatan';
                                            $displayReason =
                                                '<span class="text-gray-400 italic">' .
                                                e($statusBasedReason) .
                                                '</span>';
                                        }
                                    @endphp

                                    {!! $displayReason !!}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-2">Tidak ada peserta</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
