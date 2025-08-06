@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="max-w-6xl mx-auto bg-white shadow rounded-lg p-8 mt-4">
        <div class="flex items-center justify-between mb-6">
            <!-- Judul di kiri -->
            <h2 class="text-3xl font-bold text-gray-800 border-b pb-3">
                📅 Informasi Jadwal Pelatihan
            </h2>

            <!-- Button di kanan -->
            <button id="showCalendarBtn"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition-all duration-200"
                type="button">
                <!-- Icon Heroicons: Rocket Launch -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                </svg>

                Check Kalender
            </button>
        </div>
        @forelse ($trainingsByDate as $date => $trainings)
            <div
                class="bg-white border border-gray-200 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-200 mb-4">
                <div
                    class="flex flex-wrap justify-between items-center px-4 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-blue-100 rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-200">
                            <!-- Heroicon: Calendar Days -->
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-lg text-blue-700">
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                            </div>
                            <div class="text-xs text-blue-400 font-semibold tracking-wider uppercase mt-1">
                                Jadwal Pelatihan
                            </div>
                        </div>
                    </div>
                    <span
                        class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-4 py-1 rounded-full font-medium text-sm shadow">
                        <!-- Heroicon: Users -->
                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm6 8v-2a4 4 0 0 0-3-3.87" />
                        </svg>
                        Total: {{ $trainings->sum('participants_count') }} Peserta
                    </span>
                </div>

                <ul class="divide-y divide-gray-100">
                    @forelse ($trainings as $training)
                        <li
                            class="flex flex-wrap md:flex-nowrap justify-between gap-3 px-6 py-4 items-center hover:bg-blue-50 transition">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-gray-900 text-base">
                                        {{ $training->activity }}
                                    </span>
                                    @if ($training->name_company)
                                        <span
                                            class="inline-block bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-1 rounded-lg ml-2 shadow-sm">
                                            <!-- Heroicon: Office Building -->
                                            <svg class="w-4 h-4 inline mr-1 text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 21v-8a2 2 0 0 1 2-2h2V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6h2a2 2 0 0 1 2 2v8" />
                                            </svg>
                                            {{ $training->name_company }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-sm mt-1">
                                    @if ($training->place)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 bg-pink-200 text-blue-800 rounded-md font-medium">
                                            <!-- Heroicon: Map Pin -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                            </svg>


                                            {{ $training->place }}
                                        </span>
                                    @endif
                                    @if ($training->provience)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 bg-blue-200 text-blue-800 rounded-md font-medium">
                                            <!-- Heroicon: Map Pin -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>

                                            {{ $training->provience }}
                                        </span>
                                    @endif
                                    @if ($training->city)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 bg-green-200 text-green-800 rounded-md font-medium ml-1">
                                            <!-- Heroicon: Building Office 2 (City) -->
                                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 21V3a1 1 0 0 1 1-1h2m10 0h2a1 1 0 0 1 1 1v18m-7-4h.01M7 17h.01M7 13h.01M17 17h.01M17 13h.01M12 17h.01M12 13h.01M12 9h.01" />
                                            </svg>
                                            {{ $training->city }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-2 md:mt-0">
                                <span
                                    class="bg-blue-600 text-white rounded-full px-4 py-1 text-sm font-bold shadow flex items-center gap-1">
                                    <!-- Heroicon: User Group -->
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm6 8v-2a4 4 0 0 0-3-3.87" />
                                    </svg>
                                    {{ $training->participants_count }} Peserta
                                </span>
                            </div>
                        </li>
                    @empty
                        <li class="px-6 py-8 text-center text-gray-400 italic">
                            Tidak ada pelatihan pada tanggal ini.
                        </li>
                    @endforelse
                </ul>
            </div>

        @empty
            <div class="text-center py-10 text-gray-500 text-sm italic">
                Tidak ada pelatihan yang terjadwal saat ini.
            </div>
        @endforelse
    </div>
    <script>
        document.getElementById('showCalendarBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Kalender Pelatihan',
                html: `
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
        </div>
            `,
                showCloseButton: true,
                showConfirmButton: false,
                width: 450,
                customClass: {
                    popup: 'p-0'
                },
                didOpen: () => {
                    // Setelah popup muncul dan elemen HTML sudah ada
                    renderCalendar();

                    // Event tombol next/prev
                    document.getElementById('prev-month').onclick = function() {
                        currentDate.setMonth(currentDate.getMonth() - 1);
                        renderCalendar();
                    };
                    document.getElementById('next-month').onclick = function() {
                        currentDate.setMonth(currentDate.getMonth() + 1);
                        renderCalendar();
                    };
                }
            });
        });


        let currentDate = new Date();
        let selectedDay = null;

        function getMonthNames() {
            return [
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember",
            ];
        }

        function getTodayAndTenDaysLater() {
            const today = new Date();
            const tenDaysLater = new Date(today);
            tenDaysLater.setDate(today.getDate() + 10);
            return {
                today,
                tenDaysLater
            };
        }

        function renderCalendar() {
            const monthNames = getMonthNames();
            const daysInMonth = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth() + 1,
                0
            ).getDate();

            const firstDay = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                1
            ).getDay();

            const $daysContainer = $("#days");
            $daysContainer.empty();

            // Set nama bulan
            $("#month-name").text(
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`
            );

            const today = new Date();
            const tenDaysLater = new Date(today);
            tenDaysLater.setDate(today.getDate() + 9);

            // Isi tanggal penuh (kuota sudah 2) dari backend
            const fullQuotaDates = window.fullQuotaDates || []; // Format: ['2025-07-10', ...]

            // Spasi awal sebelum tanggal 1
            for (let i = 0; i < firstDay; i++) {
                $("<div>").addClass("text-center text-xs").appendTo($daysContainer);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = $("<div>").addClass(
                    "text-center border border-black py-1 px-2 rounded-lg cursor-pointer text-xs h-8 flex items-center justify-center"
                );

                const currentDay = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    day
                );
                const dateString =
                    currentDay.getFullYear() +
                    "-" +
                    String(currentDay.getMonth() + 1).padStart(2, "0") +
                    "-" +
                    String(currentDay.getDate()).padStart(2, "0");
                const dayOfWeek = currentDay.getDay();
                const isWeekend = dayOfWeek === 0;
                const isPastDate = currentDay < today;
                const isWithinNextTenDays =
                    currentDay <= tenDaysLater && currentDay > today;
                const isToday =
                    day === today.getDate() &&
                    currentDate.getMonth() === today.getMonth() &&
                    currentDate.getFullYear() === today.getFullYear();

                const isFullQuota = fullQuotaDates.includes(dateString);

                // Logika tampilan kalender
                if (isToday) {
                    dayCell.addClass("bg-violet-400 text-white");
                    dayCell.css("pointer-events", "none");
                } else if (isPastDate || isWithinNextTenDays || isWeekend) {
                    dayCell.addClass(
                        "bg-gray-300 text-gray-500 cursor-not-allowed"
                    );
                    dayCell.css("pointer-events", "none");
                } else {
                    dayCell.on("click", function() {
                        selectedDay = day;
                        $("#days div.bg-blue-500")
                            .removeClass("bg-blue-500 text-white")
                            .addClass("bg-white text-black");

                        dayCell
                            .removeClass("bg-white")
                            .addClass("bg-blue-500 text-white");
                    });
                }

                dayCell.text(day);
                $daysContainer.append(dayCell);
            }


        }
    </script>

@endsection
