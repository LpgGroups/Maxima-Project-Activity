@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-6xl mx-auto bg-white shadow rounded-lg p-8 mt-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">
            📅 Informasi Jadwal Pelatihan
        </h2>

        @forelse ($trainingsByDate as $date => $trainings)
            <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-5 mb-6 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xl font-semibold text-red-600">
                        <i class="fas fa-calendar-day mr-2"></i>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    </h3>
                    <span class="text-sm text-gray-500">
                        Total: {{ $trainings->sum('participants_count') }} peserta
                    </span>
                </div>

                <ul class="divide-y divide-gray-200">
                    @foreach ($trainings as $training)
                        <li class="py-3 flex items-start justify-between">
                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $training->activity }}
                                    <span class="text-gray-500 text-sm">
                                        — {{ $training->name_company ?? '-' }}
                                    </span>
                                </p>
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $training->participants_count }} peserta
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="text-center py-10 text-gray-500 text-sm italic">
                Tidak ada pelatihan yang terjadwal saat ini.
            </div>
        @endforelse
    </div>
@endsection
