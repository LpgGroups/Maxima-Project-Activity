@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Detail Pelatihan</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Nama PIC:</strong> {{ $training->name_pic }}</div>
            <div><strong>Perusahaan:</strong> {{ $training->name_company }}</div>
            <div><strong>Email:</strong> {{ $training->email_pic }}</div>
            <div><strong>Telepon:</strong> {{ $training->phone_pic }}</div>
            <div><strong>Pelatihan:</strong> {{ $training->activity }}</div>
            <div><strong>Tempat:</strong> {{ $training->place }}</div>
            <div><strong>Link:</strong> {{ $training->link }}</div>
            <div><strong>Progress:</strong> {{ $training->isprogress * 20 }}%</div>
        </div>
    @endsection
