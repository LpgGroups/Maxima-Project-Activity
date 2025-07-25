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
            <div class="relative flex items-start space-x-2">
                <h3 class="text-[18px] font-semibold mt-2">Kalender Maxima Academy</h3>

                <!-- Icon + Tooltip -->
                <div class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 mt-3 cursor-pointer" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.313-1.17 1.29-2 2.522-2 1.54 0 2.75 1.21 2.75 2.75 0 1.052-.597 1.976-1.469 2.406a2.25 2.25 0 00-1.281 2.156m0 3h.008M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                    </svg>

                    <!-- Tooltip container -->
                    <div class="absolute top-0 right-full ml-2 w-[400px] h-[250px] hidden group-hover:flex z-10">
                        <img src="/img/gif/step_booking.gif" alt="GIF Tooltip" class="w-full h-auto rounded shadow-lg" />
                    </div>
                </div>
            </div>
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

            <div class="grid grid-cols-5 gap-4 mt-4">
                <div class="flex flex-col items-center space-y-1">
                    <div class="w-4 h-2 bg-[#9694FF] rounded-sm"></div>
                    <span class="text-[12px] text-center">Hari Ini</span>
                </div>
                <div class="flex flex-col items-center space-y-1">
                    <div class="w-4 h-2 bg-white border border-gray-400 rounded-sm"></div>
                    <span class="text-[12px] text-center">Tersedia</span>
                </div>
                <div class="flex flex-col items-center space-y-1">
                    <div class="w-4 h-2 bg-gray-300 border border-gray-400 rounded-sm"></div>
                    <span class="text-[12px] text-center">Tidak Tersedia</span>
                </div>
                <div class="flex flex-col items-center space-y-1">
                    <div class="w-4 h-2 bg-blue-500 border border-gray-400 rounded-sm"></div>
                    <span class="text-[12px] text-center">Dipilih</span>
                </div>
                <div class="flex flex-col items-center space-y-1">
                    <div class="w-4 h-2 bg-red-500 border border-gray-400 rounded-sm"></div>
                    <span class="text-[12px] text-center">Full</span>
                </div>
            </div>

            <div class="flex justify-center">
                <button id="booking-button" class="bg-gray-300 text-white py-2 px-4 rounded-lg cursor-not-allowed mt-4"
                    disabled>
                    Pilih Jadwal Pelatihan
                </button>
            </div>
        </div>
    </div>
    <script>
        window.fullQuotaDates = @json($fullQuotaDates);
    </script>
@endsection

@push('scripts')
    @vite('resources/js/datepicker.js')
    @vite('resources/js/livedatauser.js')
@endpush
