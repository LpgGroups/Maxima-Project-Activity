@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0">
        {{-- table --}}
        <div class="rounded-2xl p-2 w-full">
            <form method="GET" class="mb-4 flex flex-wrap gap-2 items-end">
                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                    class="border rounded px-2 py-1 text-sm" />

                <select name="sort" class="border w-40 rounded px-2 py-1 text-sm">
                    <option value="">Urutkan</option>
                    <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A - Z (Perusahaan)</option>
                </select>

                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="border rounded px-2 py-1 text-sm" />
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="border rounded px-2 py-1 text-sm" />

                <button type="submit"
                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Filter</button>

                <a href="{{ '/dashboard/admin/training/alltraining' }}"
                    class="text-sm text-gray-600 underline hover:text-red-500">Reset</a>
            </form>
            <table class="table-auto w-full text-center align-middle">
                <thead>
                    <tr class="bg-slate-600 lg:text-sm text-white text-[10px]">
                        <th class="rounded-l-lg">No</th>
                        <th>Pengguna</th>
                        <th>PIC</th>
                        <th>Perusahaan</th>
                        <th>Pelatihan</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="rounded-r-lg">Progress</th>
                    </tr>
                </thead>
                <tbody id="live-training-user" class="lg:text-[14px] text-[10px]">
                    {{-- tidak akan di perlukan lagi untuk foreelse karena sudah di handle js --}}
                    @forelse ($trainingAll as $index => $training)
                        <tr onclick="window.location='{{ route('dashboard.admin.training.show', ['id' => $training->id]) }}'"
                            class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $training->user->name ?? '-' }}</td>
                            <td>{{ $training->name_pic }}</td>
                            <td>
                                {{ $training->name_company }}

                                @php
                                    // Ambil semua notifikasi training yang sudah dilihat oleh ADMIN
                                    $adminNotifications = $training->trainingNotifications->filter(function ($notif) {
                                        return $notif->viewed_at && $notif->user && $notif->user->role === 'admin';
                                    });

                                    // Apakah sudah dibaca oleh minimal satu admin?
                                    $adminSeen = $adminNotifications->isNotEmpty();

                                    // Ambil waktu terakhir admin melihat training
                                    $lastAdminViewedAt = $adminNotifications->max('viewed_at');

                                    // Logika badge
                                    $isNew = !$adminSeen;
                                    $isUpdated = $adminSeen && $training->updated_at > $lastAdminViewedAt;
                                @endphp

                                @if ($isNew)
                                    <img src="/img/gif/new.gif" alt="New" class="w-5 h-3 -mt-3 inline-block">
                                @elseif ($isUpdated)
                                    <img src="/img/gif/update.gif" alt="Updated" class="w-5 h-3 -mt-3 inline-block">
                                @endif
                            </td>

                            <td>{{ $training->activity }}</td>
                            <td>{{ $training->participants->count() }}</td>
                            <td>Aktif</td>
                            <td>
                                @php
                                    $start = \Carbon\Carbon::parse($training->date)->locale('id');
                                    $end = \Carbon\Carbon::parse($training->date_end)->locale('id');

                                    if ($start->year != $end->year) {
                                        // Beda tahun: tampilkan full untuk keduanya
                                        $date =
                                            $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
                                    } else {
                                        // Tahun sama
                                        if ($start->month == $end->month) {
                                            // Bulan sama → 12 - 15 Mei 2025
                                            $date =
                                                $start->translatedFormat('d F') .
                                                ' - ' .
                                                $end->translatedFormat('d F Y');
                                        } else {
                                            // Bulan beda → 30 Mei - 1 Juni 2025
                                            $date =
                                                $start->translatedFormat('d F') .
                                                ' - ' .
                                                $end->translatedFormat('d F Y');
                                        }
                                    }
                                @endphp

                                {{ $date }}
                            </td>

                            </td>
                            <td>
                                @php
                                    // Ambil nilai isprogress
                                    $progressValue = $training->isprogress;

                                    // Tentukan persentase dan warna berdasarkan nilai isprogress
                                    $progressMap = [
                                        1 => ['percent' => 10, 'color' => 'bg-red-600'],
                                        2 => ['percent' => 30, 'color' => 'bg-orange-500'],
                                        3 => ['percent' => 50, 'color' => 'bg-yellow-400'],
                                        4 => ['percent' => 75, 'color' => 'bg-[#bffb4e]'],
                                        5 => ['percent' => 100, 'color' => 'bg-green-600'],
                                    ];

                                    $progress = $progressMap[$progressValue] ?? [
                                        'percent' => 0,
                                        'color' => 'bg-gray-400',
                                    ];
                                @endphp

                                <div class="w-[80px] h-2 bg-gray-200 rounded-full dark:bg-gray-700 mx-auto">
                                    <div class="{{ $progress['color'] }} text-[8px] font-medium text-white text-center leading-none rounded-full"
                                        style="width: {{ $progress['percent'] }}%; height:8px">
                                        {{ $progress['percent'] }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-gray-500 py-2">Belum ada data pelatihan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $trainingAll->links() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/livedata.js')
@endpush
