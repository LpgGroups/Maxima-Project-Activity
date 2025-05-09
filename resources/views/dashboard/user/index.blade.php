@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex flex-col lg:flex-row gap-4 ">
        <!-- Container untuk informasi -->
        <div class="flex flex-wrap gap-4 w-full lg:w-[570px] h-auto">
            <!-- Elemen Total Pelatihan -->
            <div class="lg:w-[276px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4">
                <div class="text-violet-400 text-2xl font-bold">Total Pelatihan</div>
                <div class="text-black text-[52px] font-bold">{{ $totalTrainings }}</div>
            </div>

            <!-- Elemen Total Peserta -->
            <div class="lg:w-[276px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4">
                <div class="text-violet-400 text-2xl font-bold">Total Peserta</div>
                <div class="text-black text-[52px] font-bold">{{ $totalParticipants }}</div>
            </div>

            <!-- Elemen Total Kehadiran (Dibawah Total Pelatihan & Peserta) -->
            <div class="lg:w-[568px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0 lg:mb-[500px]">
                {{-- table --}}
                <div class="rounded-2xl p-2 w-full">
                    <table class="table-auto w-full text-center align-middle">
                        <thead>
                            <tr class="bg-slate-600 lg:text-sm text-white text-[10px]">
                                <th class="rounded-l-lg">No</th>
                                <th>Nama Pelatihan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="rounded-r-lg">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="lg:text-[14px] text-[10px]">
                            @forelse ($trainings as $index => $training)
                                <tr onclick="window.location='{{ route('dashboard.form', ['id' => $training->id]) }}'"
                                    class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $training->activity }}

                                        @php
                                            $notification = $training->trainingNotifications
                                                ->where('user_id', auth()->id())
                                                ->first();
                                            $isNew = !$notification || !$notification->viewed_at;

                                            // Jika bukan new, cek apakah ada update setelah dilihat
                                            $isUpdated = false;
                                            if (
                                                $notification &&
                                                $notification->viewed_at &&
                                                $training->updated_at > $notification->viewed_at
                                            ) {
                                                $isUpdated = true;
                                            }
                                        @endphp

                                        @if ($isNew)
                                            <img src="/img/gif/new.gif" alt="New" class="w-5 h-3 -mt-3 inline-block">
                                        @elseif ($isUpdated)
                                            <img src="/img/gif/update.gif" alt="Updated"
                                                class="w-5 h-3 -mt-3 inline-block">
                                        @endif
                                    </td>

                                    <td>Aktif</td>
                                    <td>
                                        @php
                                            $start = \Carbon\Carbon::parse($training->date)->locale('id');
                                            $end = \Carbon\Carbon::parse($training->date_end)->locale('id');

                                            if ($start->year != $end->year) {
                                                // Beda tahun: tampilkan full untuk keduanya
                                                $date =
                                                    $start->translatedFormat('d F Y') .
                                                    ' - ' .
                                                    $end->translatedFormat('d F Y');
                                            } else {
                                                // Tahun sama
                                                if ($start->month == $end->month) {
                                                    // Bulan sama → 12 - 15 Mei 2025
                                                    $date =
                                                        $start->translatedFormat('d F') .
                                                        ' - ' .
                                                        $end->translatedFormat('d F Y');
                                                } else {
                                                    // Bulan beda → 30 Mei - 1 Juni 2025
                                                    $date =
                                                        $start->translatedFormat('d F') .
                                                        ' - ' .
                                                        $end->translatedFormat('d F Y');
                                                }
                                            }
                                        @endphp

                                        {{ $date }}
                                    </td>
                                    <td>
                                        @php
                                            // Ambil nilai isprogress
                                            $progressValue = $training->isprogress;

                                            // Tentukan persentase dan warna berdasarkan nilai isprogress
                                            $progressMap = [
                                                1 => ['percent' => 10, 'color' => 'bg-red-600'],
                                                2 => ['percent' => 30, 'color' => 'bg-orange-500'],
                                                3 => ['percent' => 50, 'color' => 'bg-yellow-400'],
                                                4 => ['percent' => 75, 'color' => 'bg-[#bffb4e]'],
                                                5 => ['percent' => 100, 'color' => 'bg-green-600'],
                                            ];

                                            $progress = $progressMap[$progressValue] ?? [
                                                'percent' => 0,
                                                'color' => 'bg-gray-400',
                                            ];
                                        @endphp

                                        <div class="w-[80px] h-2 bg-gray-200 rounded-full dark:bg-gray-700 mx-auto">
                                            <div class="{{ $progress['color'] }} text-[8px] font-medium text-white text-center leading-none rounded-full"
                                                style="width: {{ $progress['percent'] }}%; height:8px">
                                                {{ $progress['percent'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-gray-500 py-2">Belum ada data pelatihan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($totalTrainings > 10)
                    <div class="text-center mt-1">
                        <a href="/dashboard/user/training"
                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                            Tampilkan Lebih Banyak
                        </a>
                    </div>
                @endif
            </div>

        </div>

        <!-- Elemen Kalender di Sebelah Kanan -->
        <div class="w-full lg:w-96 h-[534px] bg-white rounded-2xl shadow-md p-4">
            <p class="text-[18px] font-semibold">Kalender</p>

            <!-- Navigasi Bulan -->
            <div class="flex justify-between items-center mt-4">
                <button id="prev-month" class="bg-violet-400 text-white py-2 px-4 rounded-lg">Prev</button>
                <p id="month-name" class="text-[18px]"></p>
                <button id="next-month" class="bg-violet-400 text-white py-2 px-4 rounded-lg">Next</button>
            </div>

            <!-- Kalender -->
            <div class="grid grid-cols-7 gap-2 p-2" id="calendar-days">
                <div class="text-center font-semibold">Min</div>
                <div class="text-center font-semibold">Sen</div>
                <div class="text-center font-semibold">Sel</div>
                <div class="text-center font-semibold">Rab</div>
                <div class="text-center font-semibold">Kam</div>
                <div class="text-center font-semibold">Jum</div>
                <div class="text-center font-semibold">Sab</div>
            </div>

            <div class="grid grid-cols-7 gap-2 p-2" id="days"></div>
            <button id="booking-button" class="bg-gray-300 text-white py-2 px-4 rounded-lg cursor-not-allowed"
                disabled>Pilih Jadwal Pelatihan</button>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/datepicker.js')
@endpush
