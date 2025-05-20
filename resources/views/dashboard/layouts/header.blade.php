<nav class="h-14 bg-[#EBEAFF] border-b-2 border-[#CAC9FF]">
    <div class="max-w-screen-xl flex items-center justify-between mx-auto p-4">

        <div class="flex items-center space-x-4 ml-auto">

            <!-- Notifikasi -->
            <div class="relative">
                <button id="notifBtn" type="button" class="mt-2 text-gray-700 dark:text-white hover:text-gray-900">
                    <!-- Icon Bell -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>

                    <!-- Badge jumlah notifikasi -->
                    @php
                        $unreadCount = Auth::user()->unreadNotifications->count();
                    @endphp

                    @if ($unreadCount > 0)
                        <span
                            class="badge absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-600 rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <!-- Dropdown Notifikasi -->
                <div id="notifDropdown"
                    class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 dark:bg-gray-800">
                    <div
                        class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-white border-b dark:border-gray-700">
                        Notifikasi
                    </div>
                    @isset($dropdownNotifications)
                        <div class="max-h-60 overflow-y-auto">
                            <ul>
                                @foreach ($dropdownNotifications->take(5) as $notif)
                                    <li class="border-b border-gray-200 dark:border-gray-700">
                                        <a href="{{ $notif->data['url'] ?? '#' }}"
                                            class="{{ $notif->read_at ? 'text-gray-400 font-normal text-[14px]' : 'text-black font-bold dark:text-white text-[14px]' }} block px-4 py-2">

                                            {{-- Flex container for icon and content --}}
                                            <div class="flex items-start gap-3">
                                                @php
                                                    $icon = match ($notif->data['type'] ?? 'default') {
                                                        'new' => '🔔',
                                                        'update' => '✏️',
                                                        'success' => '🎉',
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
                        <a href="#">Lihat semua notifikasi</a>
                    </div>
                </div>
            </div>


            <!-- Profil dan dropdown -->
            <div class="relative group">
                <button type="button"
                    class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                    id="user-menu-button">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="/docs/images/people/profile-picture-5.jpg" alt="user photo">
                </button>

                <!-- Dropdown Profil -->
                <div
                    class="absolute right-0 z-50 mt-2 w-64 bg-white rounded-lg shadow-lg opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 dark:bg-gray-800">
                    <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                        <div class="font-medium truncate">{{ Auth::user()->name }}</div>
                        <div class="truncate text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                        <hr class="mt-2 border-t-2 border-red-500">
                    </div>
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                        <li>
                            <a href="#"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Settings</a>
                        </li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
                                Sign out
                            </button>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');

    notifBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notifDropdown.classList.toggle('hidden');
    });

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', function(e) {
        if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
    });
</script>
