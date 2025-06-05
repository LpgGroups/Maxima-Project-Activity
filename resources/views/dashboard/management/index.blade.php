@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="space-y-4">
        @foreach ($data as $training)
            <div class="w-full h-24 bg-green-500 rounded-lg shadow-md relative overflow-hidden">
                <div class="absolute inset-0 bg-white rounded-lg p-3 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-zinc-800 text-sm font-semibold">{{ $training->activity }}</p>
                            <p class="text-zinc-800 text-xs">{{ $training->name_pic ?? 'M' }} -
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
