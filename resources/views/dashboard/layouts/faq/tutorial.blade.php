@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="min-h-screen bg-gray-50 py-10 px-4 flex justify-center">
        <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg p-6 space-y-6">

            <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Video Tutorial Penggunaan</h2>

            {{-- Dropdown 1: Video Tutorial Umum --}}
            <details class="group border border-gray-200 rounded-lg">
                <summary
                    class="flex items-center justify-between cursor-pointer px-4 py-3 text-gray-800 font-medium bg-gray-100 hover:bg-gray-200 rounded-t-lg">
                    <span>1. Video Tutorial Umum</span>
                    <svg class="w-5 h-5 transform transition-transform duration-200 group-open:rotate-180" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-4 bg-white border-t">
                    <div class="relative w-full" style="padding-bottom: 56.25%;">
                        <iframe class="absolute top-0 left-0 w-full h-full rounded-md"
                            src="https://www.youtube.com/embed/alcKZLJzx64?si=pugKmb6YTTcCG6Is" title="Tutorial Umum"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Panduan umum penggunaan sistem aplikasi E-Register Maxima.</p>
                </div>
            </details>

            {{-- Dropdown 2: Video Tambah dan Edit --}}
            <details class="group border border-gray-200 rounded-lg">
                <summary
                    class="flex items-center justify-between cursor-pointer px-4 py-3 text-gray-800 font-medium bg-gray-100 hover:bg-gray-200 rounded-t-lg">
                    <span>2. Video Tambah & Edit Data Peserta</span>
                    <svg class="w-5 h-5 transform transition-transform duration-200 group-open:rotate-180" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-4 bg-white border-t">
                    <div class="relative w-full" style="padding-bottom: 56.25%;">
                        <iframe class="absolute top-0 left-0 w-full h-full rounded-md"
                            src="https://www.youtube.com/embed/r5Z5YL-Se9s?si=GQayuCrJ-nMtXqVJ" title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Langkah-langkah menambah dan mengedit data peserta pelatihan.</p>
                </div>
            </details>

        </div>
    </div>
@endsection
