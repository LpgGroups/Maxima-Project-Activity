@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-5xl mx-auto mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Monitoring Aktivitas Training</h2>

        @if ($notifications->count())
            <div class="space-y-4">
                @php
                    $notifSet = $notifications ?? collect();
                @endphp

                @foreach ($notifSet->sortByDesc('created_at') as $item)
                    @php

                        $url = $item->data['url'] ?? null;

                    @endphp

                    <div class="flex items-start space-x-4 bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Nomor urut -->
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                            {{ $loop->iteration }}
                        </div>

                        <!-- Detail aktivitas -->
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ $item->data['message'] ?? 'Notifikasi aktivitas' }}
                                </h3>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-600">
                                <p><strong>Pelatihan:</strong> {{ $training->activity ?? '-' }}</p>
                                <p><strong>Diperbarui oleh:</strong> {{ $item->data['user_name'] ?? 'System' }}</p>
                            </div>

                            @if (!empty($url))
                                <div class="mt-2">
                                    <a href="{{ $url }}" target="_blank"
                                        class="inline-block text-blue-600 hover:text-blue-800 underline text-sm">
                                        ➜ Lihat Detail
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 mt-4">Belum ada data monitoring.</p>
        @endif
    </div>
@endsection
