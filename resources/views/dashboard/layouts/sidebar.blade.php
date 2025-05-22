@php
    $role = auth()->user()->role;
@endphp

<style>
    .menu-item-hover {
        position: relative;
        overflow: hidden;
    }

    .menu-item-hover::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: currentColor;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease-out;
    }

    .menu-item-hover:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
</style>

<div class="flex h-screen">
    <div id="sidebar"
        class="transition-all duration-300 ease-in-out bg-[#2A2A2A] text-white h-full flex flex-col w-64 fixed top-0 left-0 z-20">
        <div class="flex items-center justify-between p-4">
            <a href="#" class="pointer-events-auto">
                <img src="/img/logo_maxima.png" class="w-20 h-auto rounded-lg" alt="Logo">
            </a>
            <button onclick="toggleSidebar()" class="text-red-600 bg-[#EBEAFF] rounded-full ml-2 animate-bounce">
                <svg id="sidebar-toggle-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Menu -->
        <div class="flex-grow p-2">
            <ul>
                @if ($role == 'user')
                    {{-- Menu untuk User --}}
                    <li class="my-1">
                        <a href="/dashboard/user"
                            class="flex items-center space-x-2 menu-item-hover 
                                  @if (request()->is('dashboard/user')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                            </svg>
                            <span class="text-sm">Dashboard</span>
                        </a>
                    </li>

                    <li class="my-1">
                        <a href="/dashboard/user/training"
                            class="flex items-center space-x-2 menu-item-hover 
                                  @if (request()->is('dashboard/user/training')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
                            </svg>
                            <span class="text-sm">Daftar Pelatihan</span>
                        </a>
                    </li>
                    <li class="my-1">
                        <a href="#"
                            class="flex items-center space-x-2 menu-item-hover hover:bg-red-500 rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v14m7-7H5">
                                </path>
                            </svg>
                            <span class="text-sm">Reports</span>
                        </a>
                    </li>
                @elseif ($role == 'admin')
                    {{-- Menu untuk Admin --}}
                    <li class="my-1">
                        <a href="/dashboard/admin"
                            class="flex items-center space-x-2 menu-item-hover 
                                  @if (request()->is('dashboard/admin')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h18v4H3V3zm0 6h18v4H3V9zm0 6h18v4H3v-4z" />
                            </svg>
                            <span class="text-sm">Admin Dashboard</span>
                        </a>
                    </li>
                    <li class="my-1">
                        <a href="/dashboard/admin/users"
                            class="flex items-center space-x-2 menu-item-hover 
                                  @if (request()->is('dashboard/admin/users')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a4 4 0 00-5-4m-6 6v-2a4 4 0 00-5-4h0a4 4 0 00-5 4v2h5m6-6a4 4 0 110-8 4 4 0 010 8z" />
                            </svg>
                            <span class="text-sm">Manajemen User</span>
                        </a>
                    </li>
                    <li class="my-1">
                        <a href="/dashboard/admin/reports"
                            class="flex items-center space-x-2 menu-item-hover 
                                  @if (request()->is('dashboard/admin/reports')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2M9 7h.01M15 7h.01M12 9.5a2.5 2.5 0 00-2.5 2.5h5a2.5 2.5 0 00-2.5-2.5z" />
                            </svg>
                            <span class="text-sm">Laporan</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Version Info -->
        <div class="p-4 mt-auto">
            <span class="text-sm">Testing:V1.6.3</span>
        </div>
    </div>
</div>

<script>
    let isSidebarOpen = true;

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('sidebar-toggle-icon');
        const mainContent = document.getElementById('main-content');
        if (isSidebarOpen) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            sidebar.querySelectorAll('span').forEach(span => span.classList.add('hidden'));
            icon.classList.add('rotate-90');
            mainContent.classList.remove('ml-64');
            mainContent.classList.add('ml-20');
        } else {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            sidebar.querySelectorAll('span').forEach(span => span.classList.remove('hidden'));
            icon.classList.remove('rotate-90');
            mainContent.classList.remove('ml-20');
            mainContent.classList.add('ml-64');
        }
        isSidebarOpen = !isSidebarOpen;
    }
</script>
