<nav class="h-14 bg-[#EBEAFF] border-b-2 border-[#CAC9FF]">
    <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">
        <button onclick="toggleMobileSidebar()" class="md:hidden text-gray-700 p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="flex items-center space-x-4 ml-auto">
            <!-- Notifikasi -->
            @php
                $unreadCount = Auth::user()->unreadNotifications->count();
                $isManajemen = Auth::user()->role === 'management';
            @endphp
            <div class="relative">
                <button id="notifBtn" data-role="{{ $isManajemen ? 'management' : 'user' }}" type="button"
                    class="mb-2 text-gray-700 dark:text-white hover:text-gray-900">
                    <!-- Icon Bell -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>

                    <!-- Badge jumlah notifikasi -->


                    @if ($unreadCount > 0)
                        <span
                            class="badge absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <!-- Dropdown Notifikasi -->
                <div id="notifDropdown"
                    class="hidden absolute right-0 w-80 bg-white rounded-lg shadow-lg z-50 dark:bg-gray-800">
                    <div
                        class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-white border-b dark:border-gray-700">
                        Notifikasi
                    </div>
                    @isset($dropdownNotifications)
                        <div class="max-h-60 overflow-y-auto">
                            <ul>
                                @foreach ($dropdownNotifications->take(10) as $notif)
                                    <li class="border-b border-gray-200 dark:border-gray-700">
                                        @php
                                            $trainingId = $notif->data['training_id'] ?? ($notif->data['id'] ?? null);
                                        @endphp
                                        <a href="
    {{ $isManajemen ? route('management.training.detail', ['id' => $trainingId]) : $notif->data['url'] ?? '#' }}"
                                            class="{{ $notif->read_at ? 'text-gray-400 font-normal text-[14px]' : 'text-black font-bold dark:text-white text-[14px]' }} block px-4 py-2">

                                            {{-- Flex container for icon and content --}}
                                            <div class="flex items-start gap-3">
                                                @php
                                                    $icon = match ($notif->data['type'] ?? 'default') {
                                                        'new' => '🔔',
                                                        'update' => '✏️',
                                                        'success' => '🎉',
                                                        'verifacc' => '⏳',
                                                        default => '🔔',
                                                    };
                                                @endphp

                                                {{-- Icon --}}
                                                <div class="text-xl flex-shrink-0">
                                                    {{ $icon }}
                                                </div>

                                                {{-- Message and metadata --}}
                                                <div class="flex flex-col">
                                                    <span>{{ $notif->data['message'] ?? 'Notifikasi baru' }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $notif->data['from'] ?? '' }}
                                                        {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>

                        </div>
                    @endisset

                    <div class="px-4 py-2 text-sm text-center text-indigo-600 hover:underline dark:text-indigo-400">
                        <a href="{{ route('notification') }}">Lihat semua notifikasi</a>
                    </div>
                </div>
            </div>


            <!-- Profil dan dropdown -->
            <div class="relative group">
                <button type="button"
                    class="mb-4 flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                    id="user-menu-button">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="/img/default_profile.png" alt="user photo">
                </button>

                <!-- Dropdown Profil -->
                <div
                    class="mb-4 absolute right-0 z-50 mt-2 w-64 bg-white rounded-lg shadow-lg opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 dark:bg-gray-800">
                    <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                        <div class="font-medium truncate">{{ Auth::user()->name }}</div>
                        <div class="truncate text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                        <hr class="mt-2 border-t-2 border-red-500">
                    </div>
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                        <li>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <!-- Icon Pengaturan -->
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg> Settings
                            </a>
                        </li>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <!-- Ikon Logout -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V4" />
                                </svg>
                                Logout
                            </button>

                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    let notifOpen = false; // Status dropdown terbuka

    function initNotifEvents() {
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');

        if (!notifBtn || !notifDropdown) return;

        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');

            // Update status berdasarkan apakah dropdown terlihat
            notifOpen = !notifDropdown.classList.contains('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
                notifDropdown.classList.add('hidden');
                notifOpen = false; // Dropdown ditutup
            }
        });
    }

    // Jalankan saat awal
    initNotifEvents();

    // Auto-refresh setiap 10 detik, tapi hanya jika dropdown TERTUTUP
    setInterval(() => {
        if (notifOpen) return; // Jika sedang terbuka, jangan refresh

        fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newNotifHTML = doc.querySelector('.relative').outerHTML;
                document.querySelector('.relative').outerHTML = newNotifHTML;

                // Setelah diganti, pasang ulang event
                initNotifEvents();
            })
            .catch(err => console.error('Gagal refresh notifikasi:', err));
    }, 20000);
</script>
