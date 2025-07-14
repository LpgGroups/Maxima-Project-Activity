@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-5xl mx-auto mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Monitoring Aktivitas Training</h2>

        @if ($notifications->count())
            <div class="space-y-4">
                @php
                    $notifSet = ($notifications ?? collect())->sortByDesc('created_at');
                    $total = $notifSet->count(); // total notifikasi
                @endphp

                @foreach ($notifSet as $item)
                    @php
                        $url = $item->data['url'] ?? null;
                        $role = $item->data['user_role'] ?? 'user'; // fallback ke 'user' jika tidak ada
                        $borderColor = match ($role) {
                            'admin' => 'border-rose-500',
                            'management' => 'border-yellow-500',
                            'user' => 'border-emerald-400',
                            default => 'border-gray-200',
                        };
                    @endphp

                    <div
                        class="flex items-start space-x-4 bg-gray-50 {{ $borderColor }} border-l-4 rounded-lg p-4 shadow-sm">
                        <!-- Nomor urut mundur -->
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                            {{ $total - $loop->index }}
                        </div>

                        <!-- Detail aktivitas -->
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ $item->data['message'] ?? 'Notifikasi aktivitas' }}
                                </h3>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-600">
                                <p><strong>Pelatihan:</strong> {{ $training->activity ?? '-' }}</p>
                                <p><strong>Diperbarui oleh:</strong> {{ $item->data['user_name'] ?? 'System' }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- <pre class="text-xs text-red-500">
    {{ json_encode($item->data, JSON_PRETTY_PRINT) }}
</pre> --}}
                @endforeach


            </div>
        @else
            <p class="text-gray-600 mt-4">Belum ada data monitoring.</p>
        @endif
    </div>
@endsection
