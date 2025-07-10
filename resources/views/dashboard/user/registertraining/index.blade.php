@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="lg:w-full sm:w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0 lg:mb-[500px]">
        <a class="bg-blue-500 rounded-lg p-2 text-white" href="{{ route('dashboard.selectDate') }}">+ Tambah Training</a>

        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-end mt-4">
            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                class="border rounded px-2 py-1 text-sm" />

            <select name="sort" class="border w-40 rounded px-2 py-1 text-sm">
                <option value="">Urutkan</option>
                <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A - Z (Nama Pelatihan)</option>
            </select>

            <input type="date" name="start_date" class="border rounded px-2 py-1 text-sm"
                value="{{ request('start_date') }}" />
            <input type="date" name="end_date" class="border rounded px-2 py-1 text-sm"
                value="{{ request('end_date') }}" />

            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Filter</button>

            <a href="{{ '/dashboard/user/training' }}"
                class="text-sm bg-red-500 text-gray-600 underline rounded px-3 py-1
                hover:text-red-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </a>
        </form>

        {{-- Per Page Form --}}
        <form method="GET" class="mb-2">
            <label for="per_page" class="text-sm">Tampilkan:</label>
            <select name="per_page" id="per_page" class="border rounded px-2 py-1 text-sm w-[60px]"
                onchange="this.form.submit()">
                @foreach ([10, 20, 30, 50] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select> data per halaman
            @foreach (request()->except('per_page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
        <!-- Table -->
        <div class="lg:h-auto h-[225px] rounded-2xl p-2 w-full">
            <table class="table-auto w-full text-center align-middle">
                <thead>
                    <tr class="bg-slate-600 lg:text-sm text-white text-[8px]">
                        <th class="rounded-l-lg">No</th>
                        <th>No Surat</th>
                        <th>Pelatihan</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Peserta</th>
                        <th>Tanggal</th>
                        <th class="rounded-r-lg">Progress </th>
                    </tr>
                </thead>
                <tbody class="lg:text-[12px] text-[8px]">
                    @foreach ($trainings as $index => $training)
                        <tr onclick="window.location='{{ route('dashboard.form', ['id' => $training->id]) }}'"
                            class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose">
                            <td>{{ $trainings->firstItem() + $index }}</td>
                            <td>{{ $training->no_letter }}</td>
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
                                    <img src="/img/gif/update.gif" alt="Updated" class="w-5 h-3 -mt-3 inline-block">
                                @endif
                            </td>
                            <td>
                                @php
                                    $allowedActivities = ['TKPK1', 'TKPK2', 'TKBT1', 'TKBT2'];
                                @endphp
                                {{ $training->place }} @if (in_array($training->activity, $allowedActivities) && $training->city)
                                    - {{ $training->city }}
                                @endif
                            </td>
                            <td class="p-1">
                                @if ($training->isprogress == 5 && $training->isfinish == 1)
                                    <span
                                        class="bg-green-600 text-white font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">
                                        Selesai
                                    </span>
                                @elseif ($training->isprogress == 5 && $training->isfinish == 2)
                                    <span
                                        class="bg-yellow-400 text-black font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">
                                        Menunggu
                                    </span>
                                @elseif ($training->isprogress <= 4 && $training->isfinish == 0)
                                    <span
                                        class="bg-blue-400 text-white font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">
                                        Proses
                                    </span>
                                @else
                                    <span
                                        class="bg-yellow-400 text-black font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">
                                        Menunggu
                                    </span>
                                @endif
                            </td>

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
                                    $progressValue = $training->isprogress;
                                    $isRejected = $training->isfinish == 2;

                                    $progressMap = [
                                        1 => ['percent' => 10, 'color' => 'bg-red-600'],
                                        2 => ['percent' => 30, 'color' => 'bg-orange-500'],
                                        3 => ['percent' => 50, 'color' => 'bg-yellow-400'],
                                        4 => ['percent' => 75, 'color' => 'bg-[#e6e600]'],
                                        5 => ['percent' => 100, 'color' => 'bg-green-600'],
                                    ];

                                    $progress = $progressMap[$progressValue] ?? [
                                        'percent' => 0,
                                        'color' => 'bg-gray-400',
                                    ];

                                    // Jika isfinish = 2 (Ditolak), override warna dan persen
                                    // if ($isRejected) {
                                    //     $progress['color'] = 'bg-red-600';
                                    //     $progress['percent'] = 100;
                                    // }

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
