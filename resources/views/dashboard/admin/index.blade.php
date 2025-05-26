@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex flex-col lg:flex-row gap-4 ">
        <!-- Container untuk informasi -->
        <div class="flex flex-wrap gap-4 w-full lg:w-full h-auto">
            <!-- Elemen Total Pelatihan -->
            <div class="lg:w-[276px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4">
                <div class="text-violet-400 text-2xl font-bold">Total Pelatihan</div>
                <div class="text-black text-[52px] font-bold">{{ $totalTraining }}</div>
            </div>

            <!-- Elemen Total Peserta -->
            <div class="lg:w-[276px] sm:w-full h-auto bg-white rounded-2xl shadow-md p-4">
                <div class="text-violet-400 text-2xl font-bold">Total Peserta</div>
                <div class="text-black text-[52px] font-bold"></div>
            </div>

            <div class="w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0">
                {{-- table --}}
                <div class="rounded-2xl p-2 w-full">
                    <table class="table-auto w-full text-center align-middle">
                        <thead>
                            <tr class="bg-slate-600 lg:text-sm text-white text-[10px]">
                                <th class="rounded-l-lg">No</th>
                                <th>Pengguna</th>
                                <th>Nama PIC</th>
                                <th>Nama Perusahaan</th>
                                <th>Nama Pelatihan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="rounded-r-lg">Progress</th>
                            </tr>
                        </thead>
                        <tbody id="live-training-body" class="lg:text-[14px] text-[10px]">
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
                                            $adminNotifications = $training->trainingNotifications->filter(function (
                                                $notif,
                                            ) {
                                                return $notif->viewed_at &&
                                                    $notif->user &&
                                                    $notif->user->role === 'admin';
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
                                            <img src="/img/gif/update.gif" alt="Updated"
                                                class="w-5 h-3 -mt-3 inline-block">
                                        @endif
                                    </td>

                                    <td>{{ $training->activity }}</td>
                                    <td>Aktif</td>
                                    <td>
                                        @php
                                            $start = \Carbon\Carbon::parse($training->date)->locale('id');
                                            $end = \Carbon\Carbon::parse($training->date_end)->locale('id');

                                            if ($start->year != $end->year) {
                                                // Beda tahun: tampilkan full untuk keduanya
                                                $date =
                                                    $start->translatedFormat('d F Y') .
                                                    ' - ' .
                                                    $end->translatedFormat('d F Y');
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
                </div>
                {{-- @if ($totalTrainings > 10)
                <div class="text-center mt-1">
                    <a href="/dashboard/user/training"
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                        Tampilkan Lebih Banyak
                    </a>
                </div>
            @endif --}}
            </div>
        </div>
    </div>
@endsection
<script>
    function fetchTrainingData() {
        fetch("{{ route('admin.training.live') }}")
            .then(response => response.json())
            .then(response => {
                const trainings = response.data.reverse();
                let html = '';

                trainings.forEach((training, index) => {
                    const userName = training.user?.name || '-';
                    const namePic = training.name_pic || '-';
                    const nameCompany = training.name_company || '-';
                    const activity = training.activity || '';

                    const dateStart = training.date ? new Date(training.date) : null;
                    const dateEnd = training.date_end ? new Date(training.date_end) : null;

                    // Format tanggal custom
                    let formattedDate = '';
                    if (dateStart && dateEnd) {
                        const dayStart = dateStart.getDate();
                        const dayEnd = dateEnd.getDate();
                        const monthStart = dateStart.toLocaleDateString('id-ID', {
                            month: 'long'
                        });
                        const monthEnd = dateEnd.toLocaleDateString('id-ID', {
                            month: 'long'
                        });
                        const yearStart = dateStart.getFullYear();
                        const yearEnd = dateEnd.getFullYear();

                        if (yearStart !== yearEnd) {
                            formattedDate =
                                `${dayStart} ${monthStart} ${yearStart} - ${dayEnd} ${monthEnd} ${yearEnd}`;
                        } else if (dateStart.getMonth() === dateEnd.getMonth()) {
                            formattedDate = `${dayStart}-${dayEnd} ${monthStart} ${yearStart}`;
                        } else {
                            formattedDate =
                                `${dayStart} ${monthStart} - ${dayEnd} ${monthEnd} ${yearStart}`;
                        }
                    }

                    const progressMap = {
                        1: {
                            percent: 10,
                            color: 'bg-red-600'
                        },
                        2: {
                            percent: 30,
                            color: 'bg-orange-500'
                        },
                        3: {
                            percent: 50,
                            color: 'bg-yellow-400'
                        },
                        4: {
                            percent: 75,
                            color: 'bg-[#bffb4e]'
                        },
                        5: {
                            percent: 100,
                            color: 'bg-green-600'
                        },
                    };

                    const progress = progressMap[training.isprogress] || {
                        percent: 0,
                        color: 'bg-gray-400'
                    };

                    let badgeHTML = '';
                    if (training.isNew) {
                        badgeHTML =
                            `<img src="/img/gif/new.gif" alt="New" class="w-5 h-3 -mt-3 inline-block">`;
                    } else if (training.isUpdated) {
                        badgeHTML =
                            `<img src="/img/gif/update.gif" alt="Updated" class="w-5 h-3 -mt-3 inline-block">`;
                    }

                    html += `
                    <tr onclick="window.location='/dashboard/admin/training/${training.id}'"
                        class="odd:bg-white even:bg-gray-300 cursor-pointer hover:bg-red-500 hover:text-white leading-loose">
                        <td>${index + 1}</td>
                        <td>${userName}</td>
                        <td>${namePic}</td>
                        <td>
                            ${nameCompany} 
                            ${badgeHTML}
                        </td>
                        <td>${activity}</td>
                        <td>Aktif</td>
                        <td>${formattedDate}</td>
                        <td>
                            <div class="w-[80px] h-2 bg-gray-200 rounded-full dark:bg-gray-700 mx-auto">
                                <div class="${progress.color} text-[8px] font-medium text-white text-center leading-none rounded-full"
                                    style="width: ${progress.percent}%; height:8px">
                                    ${progress.percent}%
                                </div>
                            </div>
                        </td>
                    </tr>`;
                });

                document.getElementById('live-training-body').innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching training data:', error);
            });
    }

    fetchTrainingData();

    // Refresh data setiap 2 detik
    setInterval(fetchTrainingData, 2000);
</script>
