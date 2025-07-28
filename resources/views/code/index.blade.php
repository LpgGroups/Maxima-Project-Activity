@extends('layout.main')

@section('container')
    <div class="px-4 md:px-8 mb-4 w-full m-4">
        <div class="w-full md:w-2/3 mx-auto">
            <form method="GET" action="{{ route('code.index') }}" class="w-full">
                <input type="text" name="search" placeholder="Masukkan kode training lengkap..."
                    value="{{ request('search') }}"
                    class="w-full pl-4 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />

                <!-- Tombol submit di bawah input -->
                <button type="submit"
                    class="mt-3 w-[200px] flex items-center justify-center gap-2 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition font-semibold mx-auto">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.75 3.75a7.5 7.5 0 0012.9 12.9z" />
                    </svg>
                    Cari
                </button>
            </form>
        </div>
    </div>



    @php
        $progressMap = [
            1 => ['percent' => 10, 'color' => 'bg-red-600'],
            2 => ['percent' => 30, 'color' => 'bg-orange-500'],
            3 => ['percent' => 50, 'color' => 'bg-yellow-400'],
            4 => ['percent' => 75, 'color' => 'bg-[#bffb4e]'],
            5 => ['percent' => 100, 'color' => 'bg-green-600'],
        ];
    @endphp

    @if (request('search'))
        @if ($trainings->count())
            <div class="space-y-4 m-8">
                @foreach ($trainings as $training)
                    <div class="training-card w-full h-[150px] bg-white rounded-lg shadow-md relative overflow-hidden">
                        <div>
                            <div class="absolute inset-0 bg-white rounded-lg p-3 flex flex-col justify-between">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="no-letter text-[12px] text-gray-500">Kode: {{ $training->code_training }}
                                        </p>
                                        <hr>
                                        </hr>
                                        <div class="flex items-center">
                                            <p class="text-zinc-800 text-sm font-semibold">
                                                {{ config('activity_map.' . $training->activity) ?? $training->activity }}
                                            </p>
                                        </div>
                                        <p class="text-zinc-800 text-xs truncate overflow-hidden whitespace-nowrap w-[160px] sm:w-[200px] md:w-[240px] lg:w-[500px]"
                                            title="{{ $training->name_pic ?? 'Tidak Ada' }} - {{ $training->name_company ?? '-' }}">
                                            {{ $training->name_pic ?? 'Tidak Ada' }} - {{ $training->name_company ?? '-' }}
                                        </p>
                                        <p class="text-zinc-800 text-[12px] mt-1">{{ $training->participants->count() }}
                                            Peserta</p>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $progressValue = $training->isprogress;
                                            $map = $progressMap[$progressValue] ?? [
                                                'percent' => 0,
                                                'color' => 'bg-gray-400',
                                            ];
                                            $progress = [
                                                'percent' => $map['percent'],
                                                'color' => $map['color'],
                                                'label' => $map['percent'] === 100 ? 'Selesai' : 'Sedang Proses',
                                            ];
                                        @endphp

                                        <div class="text-[10px] text-black font-medium">
                                            {{ $progress['label'] }}
                                        </div>

                                        <div class="relative w-full md:w-24 h-1.5 bg-violet-100 rounded-full mt-1 mb-1">
                                            <div class="absolute top-0 left-0 h-1.5 {{ $progress['color'] }} rounded-full"
                                                style="width: {{ $progress['percent'] }}%;"></div>
                                        </div>
                                        <div class="text-[10px] text-neutral-600">{{ $progress['percent'] }}%</div>
                                    </div>
                                </div>

                                <div class="text-[10px]">
                                    Update terbaru:
                                    {{ \Carbon\Carbon::parse($training->updated_at)->translatedFormat('d F Y H:i') }} WIB
                                </div>

                                <div class="flex justify-between items-center text-[12px] relative">
                                    <span class="text-blue-400">
                                        {{ \Carbon\Carbon::parse($training->date)->translatedFormat('d F Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($training->date_end)->translatedFormat('d F Y') }}
                                    </span>
                                    <a href="{{ route('code.show', $training->id) }}"
                                        class="sm:mb-4 inline-block px-4 py-2 rounded-md bg-blue-300 text-blue-900 font-medium shadow hover:bg-blue-400 hover:shadow-lg hover:scale-105 transform transition duration-200 ease-in-out">
                                        Detail
                                    </a>
                                </div>
                            </div>
                            </a>
                        </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mt-4 m-8 mx-auto">Tidak ada hasil untuk "<strong>{{ request('search') }}</strong>".</p>
        @endif
    @else
        @if ($carrousel->count())
            <div class="w-full px-4 md:px-8 mt-10 relative max-w-[1139px] mx-auto">
                <p class="text-24 font-bold bg-red-500 w-[200px] rounded-lg">Seputar Informasi Maxima </p>
                <div class="overflow-hidden rounded-lg shadow-lg aspect-[1139/450] relative">
                    <div id="carousel" class="flex transition-transform duration-700 ease-in-out h-full"
                        style="width: {{ 100 * $carrousel->count() }}%;">
                        @foreach ($carrousel as $ad)
                            @if ($ad->url)
                                <a href="{{ $ad->url }}" target="_blank" rel="noopener"
                                    class="block h-full w-full relative group"
                                    style="width:{{ 100 / $carrousel->count() }}%">
                                    <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}"
                                        class="w-full h-full {{ $ad->is_small ? 'object-contain p-4' : 'object-cover' }} rounded-lg transition-opacity group-hover:opacity-80" />

                                    <div
                                        class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent text-white p-4 rounded-b-lg">
                                        <h3 class="text-base sm:text-lg md:text-xl font-semibold">{{ $ad->title }}</h3>
                                        @if ($ad->summary)
                                            <p class="text-xs sm:text-sm md:text-base mt-1">{{ $ad->summary }}</p>
                                        @endif
                                    </div>
                                </a>
                            @else
                                <div class="h-full w-full relative" style="width:{{ 100 / $carrousel->count() }}%">
                                    <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}"
                                        class="w-full h-full {{ $ad->is_small ? 'object-contain p-4' : 'object-cover' }} rounded-lg" />

                                    <div
                                        class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent text-white p-4 rounded-b-lg">
                                        <h3 class="text-base sm:text-lg md:text-xl font-semibold">{{ $ad->title }}</h3>
                                        @if ($ad->summary)
                                            <p class="text-xs sm:text-sm md:text-base mt-1">{{ $ad->summary }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Navigasi --}}
                    <button id="prevBtn"
                        class="absolute top-1/2 left-2 -translate-y-1/2 bg-black/50 hover:bg-black text-white rounded-full w-8 h-8 flex items-center justify-center z-10">
                        &#10094;
                    </button>
                    <button id="nextBtn"
                        class="absolute top-1/2 right-2 -translate-y-1/2 bg-black/50 hover:bg-black text-white rounded-full w-8 h-8 flex items-center justify-center z-10">
                        &#10095;
                    </button>
                </div>
            </div>

            {{-- JS --}}
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    let currentSlide = 0;
                    const carousel = document.getElementById('carousel');
                    const totalSlides = {{ $carrousel->count() }};
                    const prevBtn = document.getElementById('prevBtn');
                    const nextBtn = document.getElementById('nextBtn');

                    function updateSlide() {
                        carousel.style.transform = `translateX(-${currentSlide * (100 / totalSlides)}%)`;
                    }

                    nextBtn.addEventListener('click', () => {
                        currentSlide = (currentSlide + 1) % totalSlides;
                        updateSlide();
                    });

                    prevBtn.addEventListener('click', () => {
                        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                        updateSlide();
                    });

                    setInterval(() => {
                        currentSlide = (currentSlide + 1) % totalSlides;
                        updateSlide();
                    }, 7000);

                    window.addEventListener('resize', updateSlide); // agar responsif
                });
            </script>
        @endif


    @endif
@endsection
