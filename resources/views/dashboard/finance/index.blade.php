@extends('dashboard.layouts.dashboardmain')
@section('container')
    <h1 class="text-xl font-bold mb-4">Daftar Pelatihan</h1>
    <x-table-training />
@endsection
@push('scripts')
    @vite('resources/js/livedatafinance.js')
@endpush
