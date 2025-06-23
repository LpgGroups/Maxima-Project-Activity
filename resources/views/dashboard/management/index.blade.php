@extends('dashboard.layouts.dashboardmain')
@section('container')
    @php
        $activityMap = [
            'TKPK1' => 'Pelatihan Tenaga Kerja Pada Ketinggian Tingkat 1',
            'TKPK2' => 'Pelatihan Tenaga Kerja Pada Ketinggian Tingkat 2',
            'TKBT1' => 'Pelatihan Tenaga Kerja Bangunan Tinggi 1',
            'TKBT2' => 'Pelatihan Tenaga Kerja Bangunan Tinggi 2',
            'BE' => 'Pelatihan Basic Electrical',
            'P3K' => 'Pelatihan Pertolongan Pertama Pada Kecelakaan (P3K)',
            'AK3U' => 'Pelatihan Ahli K3 Umum (AK3U) ',
        ];
    @endphp
    <div class="mb-4 relative w-full md:w-2/3">
        <div class="flex items-center space-x-2">
            <!-- Input pencarian -->
            <div class="relative w-full">
                <input type="text" id="searchInput" placeholder="Cari..."
                    class="w-full p-2 pr-10 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.75 3.75a7.5 7.5 0 0012.9 12.9z" />
                    </svg>
                </div>
            </div>
            <!-- Tombol filter -->
            <button id="filterToggleBtn" type="button"
                class="p-2 border border-gray-300 rounded bg-white hover:bg-gray-100 focus:outline-none">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-5.414 5.414A1 1 0 0015 12.414V20a1 1 0 01-1.447.894l-4-2A1 1 0 019 18.118V12.414a1 1 0 00-.293-.707L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
            </button>
        </div>

        <!-- Panel filter (hidden by default) -->
        <form method="GET" action="{{ '/dashboard/management' }}">
            <div id="filterPanel" class="hidden mt-3 bg-gray-50 border border-gray-300 p-4 rounded-md space-y-4">
                <div class="flex flex-col md:flex-row md:items-end md:space-x-4">
                    <div class="flex-1">
                        <label for="sortCompany" class="block text-sm font-medium text-gray-700">Urutkan Perusahaan</label>
                        <select name="sortCompany" id="sortCompany"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded">
                            <option value="">-- Pilih Urutan --</option>
                            <option value="asc" {{ request('sortCompany') == 'asc' ? 'selected' : '' }}>A - Z</option>
                            <option value="desc" {{ request('sortCompany') == 'desc' ? 'selected' : '' }}>Z - A</option>
                        </select>
                    </div>

                    <div class="flex-1">
                        <label for="startDate" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="startDate" id="startDate"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded"
                            value="{{ request('startDate') }}" />
                    </div>

                    <div class="flex-1">
                        <label for="endDate" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                        <input type="date" name="endDate" id="endDate"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded" value="{{ request('endDate') }}" />
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Filter
                    </button>

                    <a href="{{ '/dashboard/management' }}"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>


    <div class="space-y-4">

        @foreach ($data as $training)
            <div class="training-card w-full h-[120px] bg-green-500 rounded-lg shadow-md relative overflow-hidden">
                <div class="absolute inset-0 bg-white rounded-lg p-3 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div>
                            @if ($training->isfinish == true)
                                <div class="flex">
                                    <p class="text-zinc-800 text-sm font-semibold">
                                        {{ $activityMap[$training->activity] ?? 'null' }}</p>
                                    <img src="{{ asset('img/svg/success.svg') }}" alt="Success" class="ml-1 w-4 h-4">
                                </div>
                            @else
                                <p class="text-zinc-800 text-sm font-semibold">
                                    {{ $activityMap[$training->activity] ?? 'null' }}</p>
                            @endif
                            <p class="text-zinc-800 text-xs">{{ $training->name_pic ?? 'null' }} -
                                {{ $training->name_company ?? '-' }}</p>
                            <p class="text-zinc-800 text-[12px] mt-1">{{ $training->participants->count() }} Peserta</p>
                        </div>
                        <div class="text-right">
                            @php
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

                            <div class="text-[10px] text-black font-medium">
                                {{ $progress['percent'] === 100 ? 'Completed' : 'In Progress' }}
                            </div>

                            <div class="relative w-full md:w-24 h-1.5 bg-violet-100 rounded-full mt-1 mb-1">
                                <div class="absolute top-0 left-0 h-1.5 {{ $progress['color'] }} rounded-full"
                                    style="width: {{ $progress['percent'] }}%;"></div>
                            </div>

                            <div class="text-[10px] text-neutral-600">{{ $progress['percent'] }}%</div>

                        </div>
                    </div>
                    <div class="flex justify-between items-center text-[12px]">
                        <span class="text-violet-400">
                            {{ \Carbon\Carbon::parse($training->date)->translatedFormat('d F Y') }}
                            -
                            {{ \Carbon\Carbon::parse($training->date_end)->translatedFormat('d F Y') }}</span>
                        <a href="#" class="text-indigo-600 text-xs view-detail-btn" data-id="{{ $training->id }}">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@push('scripts')
    @vite('resources/js/management.js')
@endpush
