@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-7xl mx-auto mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Monitoring Aktivitas Training</h2>

        @if ($notifications->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">ID Training</th> {{-- ← Tambahan --}}
                            <th class="px-4 py-2">Training</th>
                            <th class="px-4 py-2">User/Admin</th>
                            <th class="px-4 py-2">Dilihat</th>
                            <th class="px-4 py-2">Pesan Notifikasi</th>
                            <th class="px-4 py-2">Waktu Notif</th>
                            <th class="px-4 py-2">Link</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $notifSet = $notifications ?? collect();
                        @endphp

                        @if ($notifSet->count())
                            @foreach ($notifSet->sortByDesc('created_at') as $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2">{{ $training->id }}</td>
                                    <td class="px-4 py-2">{{ $training->activity ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $training->user->name ?? 'System' }}</td>
                                    <td class="px-4 py-2">-</td> {{-- Optional: tampilkan viewed_at jika kamu simpan per user --}}
                                    <td class="px-4 py-2">{{ $item->data['message'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if (!empty($item->data['url']))
                                            <a href="{{ $item->data['url'] }}" target="_blank"
                                                class="text-blue-600 underline">Lihat</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-4 py-2" colspan="8" class="text-center">Tidak ada notifikasi untuk
                                    pelatihan ini.</td>
                            </tr>
                        @endif
                    </tbody>

                </table>
            </div>
        @else
            <p class="text-gray-600">Belum ada data monitoring.</p>
        @endif
    </div>
@endsection
