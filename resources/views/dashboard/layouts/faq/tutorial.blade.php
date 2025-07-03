@extends('dashboard.layouts.dashboardmain')

@section('container')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800">Tutorial Video</h2>
            <p class="text-sm text-gray-500">Pelajari materi melalui video berikut.</p>
        </div>

        {{-- Responsive video container --}}
        <div class="relative w-full" style="padding-bottom: 56.25%;">
            <iframe
                class="absolute top-0 left-0 w-full h-full rounded-b-xl"
                src="https://www.youtube.com/embed/alcKZLJzx64?si=pugKmb6YTTcCG6Is"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen>
            </iframe>
        </div>

        <div class="px-6 py-4">
            <p class="text-gray-700 text-sm">
                Video ini menjelaskan langkah-langkah penting secara ringkas dan padat. Tonton sampai selesai untuk mendapatkan pemahaman menyeluruh.
            </p>
        </div>
    </div>
</div>
@endsection
