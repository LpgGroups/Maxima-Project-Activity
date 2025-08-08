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

                <input type="date" id="start_date" name="start_date" class="border rounded px-2 py-1 text-sm" />
                <input type="date" id="end_date" name="end_date" class="border rounded px-2 py-1 text-sm" />
                <select name="isprogress" class="border w-36 rounded px-2 py-1 text-sm">
                    <option value="">Semua Progress</option>
                    <option value="proses" {{ request('isprogress') == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ request('isprogress') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('isprogress') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="menunggu" {{ request('isprogress') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                </select>


                <button type="submit"
                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Filter</button>

                <a href="{{ '/dashboard/admin/training/alltraining' }}"
                    class="text-sm text-gray-600 underline hover:text-red-500">Reset</a>
            </form>
            <form method="GET" class="mb-2">
                <label for="per_page" class="text-sm">Tampilkan:</label>
                <select name="per_page" id="per_page" class="border rounded px-2 py-1 text-sm w-[60px]"
                    onchange="this.form.submit()">
                    @foreach ([20, 30, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 20) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select> data per halaman
            </form>
            <table class="table-auto w-full text-center align-middle">
                <thead>
                    <tr class="bg-slate-600 lg:text-sm text-white text-[10px]">
                        <th class="rounded-l-lg">No</th>
                        <th>No Surat</th>
                        <th>PIC</th>
                        <th>Perusahaan</th>
                        <th>Pelatihan</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Progress</th>
                        <th class="rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="lg:text-[14px] text-[10px]">
                    @forelse ($trainingAll as $index => $training)
                        <tr onclick="window.location='{{ route('dashboard.admin.training.show', ['id' => $training->id]) }}'"
                            class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose">
                            <td>{{ $trainingAll->firstItem() + $index }}</td>
                            <td class="max-w-[100px] truncate whitespace-nowrap" title="{{ $training->user->name }}">
                                {{ $training->no_letter ?? '-' }}
                            </td>

                            <td class="max-w-[100px] truncate whitespace-nowrap" title="{{ $training->name_pic }}">
                                {{ $training->name_pic ?? '-' }}
                            </td>
                            <td class="max-w-[100px] truncate whitespace-nowrap" title="{{ $training->name_company }}">
                                {{ $training->name_company }}
                            </td>

                            <td>{{ $training->activity }}
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
                            <td>{{ $training->participants->count() }}</td>
                            <td class="p-1">
                                @if ($training->isprogress == 5 && $training->isfinish == 1)
                                    <span
                                        class="bg-green-600 text-white font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">
                                        Selesai
                                    </span>
                                @elseif ($training->isprogress == 5 && $training->isfinish == 2)
                                    <span
                                        class="bg-red-600 text-white font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">
                                        Ditolak
                                    </span>
                                @elseif ($training->isprogress <= 4 && $training->isfinish == 0)
                                    <span
                                        class="bg-blue-400 text-white font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">
                                        Proses
                                    </span>
                                @else
                                    <span
                                        class="bg-yellow-400 text-black font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">
                                        Menunggu
                                    </span>
                                @endif
                            </td>

                            <td class="max-w-[140px] truncate whitespace-nowrap">
                                @php
                                    $start = \Carbon\Carbon::parse($training->date)->locale('id');
                                    $end = \Carbon\Carbon::parse($training->date_end)->locale('id');

                                    if ($start->year != $end->year) {
                                        $date =
                                            $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
                                    } else {
                                        if ($start->month == $end->month) {
                                            $date =
                                                $start->translatedFormat('d F') .
                                                ' - ' .
                                                $end->translatedFormat('d F Y');
                                        } else {
                                            $date =
                                                $start->translatedFormat('d F') .
                                                ' - ' .
                                                $end->translatedFormat('d F Y');
                                        }
                                    }
                                @endphp

                                {{ $date }}
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
                                        4 => ['percent' => 75, 'color' => 'bg-[#e6e600]'],
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
                            <td class="py-2 px-2 text-center">
                                {{-- Delete --}}
                                <form action="{{ route('training.destroy', $training->id) }}" method="POST"
                                    onsubmit="event.stopPropagation(); return confirm('Yakin ingin menghapus pelatihan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-gray-500 py-2">Belum ada data pelatihan </td>
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
