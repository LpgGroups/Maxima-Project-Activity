@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex flex-col lg:flex-row gap-4 ">
        <!-- Container untuk informasi -->
        <div class="flex flex-wrap gap-4 w-full lg:w-[570px] h-auto">
            <!-- Elemen Total Pelatihan -->
            <div class="w-[276px] sm:w-full md:w-[276px] h-auto bg-white rounded-2xl shadow-md p-4"
                style="background-image: url('/img/logo_training.png'); background-repeat: no-repeat; background-position: right bottom; background-size: 140px 140px;">
                <div class="text-violet-600 text-2xl font-bold">Total Pelatihan</div>
                <div class="text-black text-[52px] font-bold">{{ $totalTrainings }}</div>
            </div>

            <!-- Elemen Total Peserta -->
            <div class="lg:w-[276px] sm:w-full w-[276px] h-auto bg-white rounded-2xl shadow-md p-4"
                style="background-image: url('/img/logo_participant.png'); background-repeat: no-repeat; background-position: right bottom; background-size: 140px 140px;">
                <div class="text-violet-400 text-2xl font-bold">Total Peserta</div>
                <div class="text-black text-[52px] font-bold">{{ $totalParticipants }}</div>
            </div>


            <div class="lg:w-[568px] sm:w-full w-[276px] h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0 lg:mb-[500px]">
                {{-- table --}}
                <div class="rounded-2xl p-2 w-full">
                    <table class="table-auto w-full text-center align-middle ">
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
        <div class="lg:w-96 sm:w-full w-[276px] h-[534px] bg-white rounded-2xl shadow-md p-4">
            <p class="text-[18px] font-semibold">Kalender Maxima Academy</p>
            <!-- Navigasi Bulan -->
            <div class="flex justify-between items-center mt-4">
                <button id="prev-month" class="bg-red-500 text-white py-2 px-4 rounded-lg flex items-center justify-center">
                    <!-- Panah kiri -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <p id="month-name" class="text-[18px]"></p>
                <button id="next-month" class="bg-red-500 text-white py-2 px-4 rounded-lg flex items-center justify-center">
                    <!-- Panah kanan -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
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

            <div class="flex flex-wrap space-x-2 items-center mt-4">
                <div class="flex items-center space-x-1">
                    <div class="w-4 h-2 bg-[#9694FF] rounded-sm"></div>
                    <span class="text-[12px]">Hari Ini</span>
                </div>

                <!-- Kotak putih: Hari biasa -->
                <div class="flex items-center space-x-1">
                    <div class="w-4 h-2 bg-white border border-gray-400 rounded-sm"></div>
                    <span class="text-[12px]">Tersedia</span>
                </div>

                <div class="flex items-center space-x-1">
                    <div class="w-4 h-2 bg-gray-300 border border-gray-400 rounded-sm"></div>
                    <span class="text-[12px]">Tidak Tersedia</span>
                </div>

                <div class="flex items-center space-x-1">
                    <div class="w-4 h-2 bg-blue-500 border border-gray-400 rounded-sm"></div>
                    <span class="text-[12px]">Dipilih</span>
                </div>
            </div>
            <button id="booking-button" class="bg-gray-300 text-white py-2 px-4 rounded-lg cursor-not-allowed mt-4"
                disabled>Pilih Jadwal Pelatihan</button>

        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/datepicker.js')
    @vite('resources/js/livedatauser.js')
@endpush
