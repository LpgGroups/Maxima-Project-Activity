@extends('dashboard.layouts.dashboardmain')
@section('container')

    <div class="max-w-4xl mx-auto mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Notifikasi</h2>

        @if ($notifications->count())
            <ul class="divide-y divide-gray-200">
                @foreach ($notifications as $notif)
                    @php
                        $data = $notif->data;
                        $type = $data['type'] ?? 'default';
                        $icon = match ($type) {
                            'new' => '🆕',
                            'update' => '✏️',
                            'success' => '✅',
                            default => '🔔',
                        };
                        $isRead = $notif->read_at !== null;
                    @endphp

                    <li class="py-4">
                        <a href="{{ $data['url'] ?? '#' }}"
                            class="flex items-start gap-3 {{ $isRead ? 'text-gray-500' : 'font-bold text-black' }}">
                            <span class="text-2xl">{{ $icon }}</span>
                            <div class="flex-1">
                                <div class="text-sm">
                                    {{ $data['message'] ?? 'Notifikasi baru' }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $data['from'] ?? '' }} &bull; {{ $notif->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">Tidak ada notifikasi.</p>
        @endif
    </div>
@endsection

