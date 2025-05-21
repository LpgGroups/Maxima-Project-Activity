@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex flex-col lg:flex-row gap-4 ">
        <!-- Container untuk informasi -->
        <div class="flex flex-wrap gap-4 w-full lg:w-full h-auto">
            <!-- Elemen Total Pelatihan -->
            <div class="lg:w-[276px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4">
                <div class="text-violet-400 text-2xl font-bold">Total Pelatihan</div>
                <div class="text-black text-[52px] font-bold">{{ $totalTraining }}</div>
            </div>

            <!-- Elemen Total Peserta -->
            <div class="lg:w-[276px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4">
                <div class="text-violet-400 text-2xl font-bold">Total Peserta</div>
                <div class="text-black text-[52px] font-bold"></div>
            </div>

            <div class="w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0">
                {{-- table --}}
                <div class="rounded-2xl p-2 w-full">
                    <table class="table-auto w-full text-center align-middle">
                        <thead>
                            <tr class="bg-slate-600 lg:text-sm text-white text-[10px]">
                                <th class="rounded-l-lg">No</th>
                                <th>Pengguna</th>
                                <th>Nama PIC</th>
                                <th>Nama Perusahaan</th>
                                <th>Nama Pelatihan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="rounded-r-lg">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="lg:text-[14px] text-[10px]">
                            @forelse ($trainingAll as $index => $training)
                                <tr onclick="window.location='{{ route('dashboard.admin.training.show', ['id' => $training->id]) }}'"
                                    class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $training->user->name ?? '-' }}</td>
                                    <td>{{ $training->name_pic }}</td>
                                    <td>
                                        {{ $training->name_company }}

                                        @php
                                            // Ambil semua notifikasi training yang sudah dilihat oleh ADMIN siapa pun
                                            $adminSeen = $training->trainingNotifications
                                                ->filter(function ($notif) {
                                                    return $notif->viewed_at &&
                                                        $notif->user &&
                                                        $notif->user->role === 'admin';
                                                })
                                                ->isNotEmpty();

                                            $isNew = !$adminSeen;

                                            $isUpdated = false;
                                            if (
                                                $adminSeen &&
                                                $training->updated_at >
                                                    $training->trainingNotifications->first()?->viewed_at
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

                                    <td>{{ $training->activity }}</td>
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
                {{-- @if ($totalTrainings > 10)
                <div class="text-center mt-1">
                    <a href="/dashboard/user/training"
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                        Tampilkan Lebih Banyak
                    </a>
                </div>
            @endif --}}
            </div>
        </div>
    </div>
@endsection
