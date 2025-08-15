@extends('dashboard.layouts.dashboardmain')

@section('container')
    <h1 class="text-xl font-bold mb-4">Detail Pelatihan</h1>

    {{-- Pakai komponen --}}
    <x-detail-training :training="$training" :date-fmt="$date_fmt" />

    <div class="mt-4">
        <a href="{{ route('dashboard.finance.index') }}" class="text-sm text-blue-600 underline">← Kembali ke daftar</a>
    </div>
@endsection
