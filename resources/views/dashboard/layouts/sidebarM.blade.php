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
        class="transition-all duration-300 ease-in-out bg-[#2A2A2A] text-white h-full flex flex-col w-64 fixed top-0 left-0 z-20
            -translate-x-full md:translate-x-0">
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
                @if ($role == 'management')
                    {{-- Menu untuk Management --}}
                    <li class="my-1">
                        <a href="/dashboard/management"
                            class="flex items-center space-x-2 menu-item-hover 
                                  @if (request()->is('dashboard/management')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h18v4H3V3zm0 6h18v4H3V9zm0 6h18v4H3v-4z" />
                            </svg>
                            <span class="text-sm">Management Dashboard</span>
                        </a>
                    </li>

                    <li class="my-1">
                        <a href="/dashboard/monitoring"
                            class="flex items-center space-x-2 menu-item-hover 
                                  @if (request()->is('dashboard/monitoring')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                            </svg>
                            <span class="text-sm">Monitoring</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<script>
    let isSidebarOpen = true;

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.getElementById('sidebar-toggle-icon');
        const mainContent = document.getElementById('main-content');
        const isMobile = window.innerWidth < 768; // breakpoint md di tailwind

        if (isMobile) {
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
            }
        } else {
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
        }

        isSidebarOpen = !isSidebarOpen;
    }
</script>
