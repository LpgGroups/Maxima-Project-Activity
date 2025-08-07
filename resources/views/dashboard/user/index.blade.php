@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex flex-col lg:flex-row gap-4 ">
        <!-- Container untuk informasi -->
        <div class="flex flex-wrap gap-4 w-full lg:w-[570px] h-auto">
            <!-- Elemen Total Pelatihan -->
            <!-- Elemen Total Pelatihan -->
            <div class="w-[276px] sm:w-full md:w-[276px] h-auto bg-rose-100 rounded-2xl shadow-md p-4"
                style="background-image: url('/img/logo_training.png'); background-repeat: no-repeat; background-position: right bottom; background-size: 140px 140px;">
                <div class="text-blue-700 text-2xl font-bold">Total Pelatihan</div>
                <div class="text-black text-[52px] font-bold">{{ $totalTrainings }}</div>
            </div>

            <!-- Spacer -->

            <!-- Elemen Total Peserta -->
            <div class="w-[276px] sm:w-full md:w-[276px] h-auto bg-rose-100 rounded-2xl shadow-md p-4"
                style="background-image: url('/img/logo_participant.png'); background-repeat: no-repeat; background-position: right bottom; background-size: 140px 140px;">
                <div class="text-blue-700 text-2xl font-bold">Total Peserta</div>
                <div class="text-black text-[52px] font-bold">{{ $totalParticipants }}</div>
            </div>



            <div
                class="lg:w-[568px] sm:min-w-[568px] w-[276px h-auto bg-gradient-to-br from-white via-rose-100 to-rose-200 rounded-2xl shadow-md p-4 sm:mb-0 lg:mb-[500px]">
                {{-- table --}}
                <div class="rounded-2xl p-2 w-full overflow-x-auto sm:overflow-x-visible ">
                    <table class="table-auto w-full text-center align-middle overflow-x-auto sm:overflow-x-visible">
                        <thead>
                            <tr class="bg-slate-600 lg:text-sm text-white text-[10px]">
                                <th class="rounded-l-lg">No</th>
                                <th>Pelatihan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="rounded-r-lg">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="lg:text-[14px] text-[10px] ">
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

        <!-- Kalender Maxima Academy -->
        <div
            class="lg:w-96 sm:w-full w-[276px] h-[534px] bg-gradient-to-br from-white via-rose-100 to-rose-200
 rounded-2xl shadow-lg p-5">

            <!-- Header Kalender + Tooltip -->
            <div class="relative flex items-start justify-between">
                <h3 class="text-lg font-semibold text-gray-800">📅 Kalender Maxima Academy</h3>

                <!-- Tooltip -->
                <div class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 cursor-pointer" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.313-1.17 1.29-2 2.522-2 1.54 0 2.75 1.21 2.75 2.75 0 1.052-.597 1.976-1.469 2.406a2.25 2.25 0 00-1.281 2.156m0 3h.008M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                    </svg>

                    <div class="absolute top-0 right-full ml-3 w-[300px] h-[200px] hidden group-hover:flex z-10">
                        <img src="/img/gif/step_booking.gif" alt="GIF Tooltip" class="w-full h-auto rounded-lg shadow-xl" />
                    </div>
                </div>
            </div>

            <!-- Navigasi Bulan -->
            <div class="flex justify-between items-center mt-5">
                <button id="prev-month"
                    class="bg-blue-100 hover:bg-blue-200 text-blue-600 py-2 px-3 rounded-full shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <p id="month-name" class="text-base font-medium text-gray-700"></p>
                <button id="next-month"
                    class="bg-blue-100 hover:bg-blue-200 text-blue-600 py-2 px-3 rounded-full shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Hari dalam Minggu -->
            <div class="grid grid-cols-7 gap-2 mt-4 text-xs font-medium text-center text-gray-500" id="calendar-days">
                <div>Min</div>
                <div>Sen</div>
                <div>Sel</div>
                <div>Rab</div>
                <div>Kam</div>
                <div>Jum</div>
                <div>Sab</div>
            </div>

            <!-- Tanggal-tanggal -->
            <div class="grid grid-cols-7 gap-2 mt-1 text-sm" id="days">
                <!-- Diisi oleh script -->
            </div>

            <!-- Legend -->
            <div class="grid grid-cols-5 gap-3 mt-5 text-[11px] text-center text-gray-600">
                <div class="flex flex-col items-center">
                    <div class="w-4 h-2 bg-[#9694FF] rounded-sm mb-1"></div>
                    <span>Hari Ini</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-4 h-2 bg-transparent border border-gray-400 rounded-sm mb-1"></div>
                    <span>Tersedia</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-4 h-2 bg-gray-300 border border-gray-400 rounded-sm mb-1"></div>
                    <span>Tidak Tersedia</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-4 h-2 bg-blue-500 border border-gray-400 rounded-sm mb-1"></div>
                    <span>Dipilih</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-4 h-2 bg-red-500 border border-gray-400 rounded-sm mb-1"></div>
                    <span>Full</span>
                </div>
            </div>

            <!-- Tombol Booking -->
            <div class="flex justify-center mt-5">
                <button id="booking-button"
                    class="bg-gray-300 text-white py-2 px-4 rounded-md cursor-not-allowed text-sm shadow-inner" disabled>
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
