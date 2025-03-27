@extends('dashboard.layouts.dashboardmain')
@section('container')
<div class="flex justify-center items-center min-h-screen -mt-10">
    <!-- Elemen Kalender di Sebelah Kanan -->
    <div class="w-full lg:w-96 h-[534px] bg-white rounded-2xl shadow-md p-4">
        <p class="text-[18px] font-semibold">Pilih Tanggal Pelatihan</p>

        <!-- Navigasi Bulan -->
        <div class="flex justify-between items-center mt-4">
            <button id="prev-month" class="bg-violet-400 text-white py-2 px-4 rounded-lg">Prev</button>
            <p id="month-name" class="text-[18px]"></p>
            <button id="next-month" class="bg-violet-400 text-white py-2 px-4 rounded-lg">Next</button>
        </div>

        <!-- Kalender -->
        <div class="grid grid-cols-7 gap-2 p-2" id="calendar-days">
            <div class="text-center font-semibold">Sen</div>
            <div class="text-center font-semibold">Sel</div>
            <div class="text-center font-semibold">Rab</div>
            <div class="text-center font-semibold">Kam</div>
            <div class="text-center font-semibold">Jum</div>
            <div class="text-center font-semibold">Sab</div>
            <div class="text-center font-semibold">Min</div>
        </div>

        <div class="grid grid-cols-7 gap-2 p-2" id="days"></div>
        <button id="booking-button" class="bg-gray-300 text-white py-2 px-4 rounded-lg cursor-not-allowed" disabled>Booking Jadwal</button>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/datepicker.js') }}"></script>
@endpush
