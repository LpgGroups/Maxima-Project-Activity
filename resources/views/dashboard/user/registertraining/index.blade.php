@extends('dashboard.layouts.dashboardmain')
@section('container')
<div class="lg:w-full sm:w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0 lg:mb-[500px]">
    <a href="{{ route('dashboard.selectDate') }}">Tambah</a>
    

    <!-- Table -->
    <div class="lg:h-auto h-[225px] rounded-2xl p-2 w-full">
        <table class="table-auto w-full text-center align-middle">
            <thead>
                <tr class="bg-slate-600 lg:text-sm text-white text-[8px]">
                    <th class="rounded-l-lg">No</th>
                    <th>Nama Pelatihan</th>
                    <th>Status</th>
                    <th>Peserta</th>
                    <th>Tanggal</th>
                    <th class="rounded-r-lg">Progress</th>
                </tr>
            </thead>
            <tbody class="lg:text-[12px] text-[8px]">
                <tr class="odd:bg-white even:bg-gray-300 rounded">
                    <td>1</td>
                    <td>TKPK1</td>
                    <td>aktif</td>
                    <td>12 peserta</td>
                    <td>18 mar-20 mar 2025</td>
                    <td>
                        <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                            <div class="bg-blue-600 text-[8px] font-medium text-blue-100 text-center leading-none rounded-full"
                                style="width: 45%; height:8px"> 45%</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/registertraining.js') }}"></script>
@endpush