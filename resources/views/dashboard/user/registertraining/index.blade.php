@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="lg:w-full sm:w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0 lg:mb-[500px]">
        <a href="{{ route('dashboard.selectDate') }}">Tambah</a>


        <!-- Table -->
        <div class="lg:h-auto h-[225px] rounded-2xl p-2 w-full">
            <table class="table-auto w-full text-center align-middle">
                <thead>
                    <tr class="bg-slate-600 lg:text-sm text-white text-[8px]">
                        <th class="rounded-l-lg">No</th>
                        <th>Nama Pelatihan</th>
                        <th>Status</th>
                        <th>Peserta</th>
                        <th>Tanggal</th>
                        <th class="rounded-r-lg">Progress</th>
                    </tr>
                </thead>
                <tbody class="lg:text-[12px] text-[8px]">
                    @foreach ($trainings as $index => $training)
                        <tr onclick="window.location='{{ route('dashboard.form', ['id' => $training->id]) }}'"
                            class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose">
                            <td>{{ $trainings->firstItem() + $index }}</td>
                            <td>{{ $training->activity }}</td>
                            <td>{{ $training->status }}</td>
                            <td>{{ $training->participants->count() }} peserta</td>
                            <td>
                                @php
                                    $start = \Carbon\Carbon::parse($training->date)->locale('id');
                                    $end = \Carbon\Carbon::parse($training->date_end)->locale('id');

                                    if ($start->year != $end->year) {
                                        // Beda tahun: tampilkan full untuk keduanya
                                        $date =
                                            $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
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
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $trainings->links() }}
            </div>
        </div>
    </div>
@endsection
