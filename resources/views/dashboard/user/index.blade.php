@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex flex-col lg:flex-row gap-4 ">
        <!-- Container untuk informasi -->
        <div class="flex flex-wrap gap-4 w-full lg:w-[570px] h-auto">
            <!-- Elemen Total Pelatihan -->
            <div class="lg:w-[276px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4">
                <div class="text-violet-400 text-2xl font-bold">Total Pelatihan</div>
                <div class="text-black text-[52px] font-bold">3</div>
            </div>

            <!-- Elemen Total Peserta -->
            <div class="lg:w-[276px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4">
                <div class="text-violet-400 text-2xl font-bold">Total Peserta</div>
                <div class="text-black text-[52px] font-bold">21</div>
            </div>

            <!-- Elemen Total Kehadiran (Dibawah Total Pelatihan & Peserta) -->
            <div class="lg:w-[568px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0 lg:mb-[500px]">
                {{-- table --}}
                <div class="rounded-2xl p-2 w-full">
                    <table class="table-auto w-full text-center align-middle">
                        <thead>
                            <tr class="bg-slate-600 lg:text-sm text-white text-[8px]">
                                <th class="rounded-l-lg">No</th>
                                <th>Nama Pelatihan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="rounded-r-lg">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="lg:text-[12px] text-[8px]">
                            @forelse ($trainings as $index => $training)
                                <tr onclick="window.location='{{ route('dashboard.form', $training->id) }}'" class="odd:bg-white even:bg-gray-300">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $training->activity }}</td>
                                    <td>Aktif</td>
                                    <td>{{ \Carbon\Carbon::parse($training->date)->format('d M Y') }}</td>
                                    <td>
                                        {{-- Contoh Progress Bar berdasarkan jumlah peserta --}}
                                        @php
                                            $progress = $training->participants->count() * 10; // Misalnya 10% per peserta
                                            $progress = $progress > 100 ? 100 : $progress; // Maks 100%
                                        @endphp
                                        <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                            <div class="bg-blue-600 text-[8px] font-medium text-blue-100 text-center leading-none rounded-full"
                                                style="width: {{ $progress }}%; height:8px"> {{ $progress }}%</div>
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
            </div>

        </div>

        <!-- Elemen Kalender di Sebelah Kanan -->
        <div class="w-full lg:w-96 h-[534px] bg-white rounded-2xl shadow-md p-4">
            <p class="text-[18px] font-semibold">Calendar</p>

            <!-- Navigasi Bulan -->
            <div class="flex justify-between items-center mt-4">
                <button id="prev-month" class="bg-violet-400 text-white py-2 px-4 rounded-lg">Prev</button>
                <p id="month-name" class="text-[18px]"></p>
                <button id="next-month" class="bg-violet-400 text-white py-2 px-4 rounded-lg">Next</button>
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
            <button id="booking-button" class="bg-gray-300 text-white py-2 px-4 rounded-lg cursor-not-allowed"
                disabled>Pilih Jadwal Pelatihan</button>
        </div>
    </div>
@endsection


