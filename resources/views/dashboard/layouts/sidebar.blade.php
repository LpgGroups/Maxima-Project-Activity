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
                        <a href="/tutorial"
                            class="flex items-center space-x-2 menu-item-hover 
                                  @if (request()->is('tutorial')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>

                            <span class="text-sm">Panduan Aplikasi</span>
                        </a>
                    </li>
                @elseif ($role == 'admin')
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

                    {{-- Dropdown Manajemen Pelatihan --}}
                    <li class="my-1">
                        <div class="group">
                            <div
                                class="flex items-center justify-between menu-item-hover 
                      @if (request()->is('dashboard/admin/training*')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                      rounded-lg p-2 cursor-pointer">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <span class="text-sm">Manajemen Pelatihan</span>
                                </div>
                                <svg class="size-4 transition-transform group-hover:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            {{-- Sub-menu (Selalu tampil saat active, tampil saat hover) --}}
                            <ul
                                class="mt-1 ml-6 pl-3 border-l border-gray-200 space-y-1 hidden group-hover:block 
                            @if (request()->is('dashboard/admin/training*'))  @endif">
                                <li>
                                    <a href="/dashboard/admin/training/alltraining" @class([
                                        'block px-2 py-1 rounded hover:bg-red-100 text-sm',
                                        'text-red-500 font-bold' => request()->is(
                                            'dashboard/admin/training/alltraining'),
                                        'text-gray-500' => !request()->is('dashboard/admin/training/alltraining'),
                                    ])>
                                        List Pelatihan
                                    </a>
                                </li>
                                <li>
                                    <a href="/dashboard/schedule" @class([
                                        'block px-2 py-1 rounded hover:bg-red-100 text-sm',
                                        'text-red-500 font-bold' => request()->is('dashboard/schedule'),
                                        'text-gray-500' => !request()->is('dashboard/schedule'),
                                    ])>
                                        Jadwal Pelatihan
                                    </a>
                                </li>
                                {{-- Tambah submenu lain di sini jika perlu --}}
                            </ul>
                        </div>
                    </li>

                    {{-- Menu lainnya --}}
                    <li class="my-1">
                        <a href="/dashboard/admin/users"
                            class="flex items-center space-x-2 menu-item-hover 
                  @if (request()->is('dashboard/admin/users')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                            <span class="text-sm">Manajemen User</span>
                        </a>
                    </li>

                    <li class="my-1">
                        <a href="/dashboard/monitoring"
                            class="flex items-center space-x-2 menu-item-hover 
                  @if (request()->is('dashboard/monitoring')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                            </svg>
                            <span class="text-sm">Monitoring</span>
                        </a>
                    </li>
                @elseif ($role == 'management')
                    {{-- Menu untuk Management --}}
                    <li class="my-1">
                        <a href="/dashboard/management"
                            class="flex items-center space-x-2 menu-item-hover 
                               @if (request()->is('dashboard/management*')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                @elseif ($role == 'dev')
                    {{-- Menu untuk Management --}}
                    <a href="/dashboard/developer"
                        class="flex items-center space-x-2 menu-item-hover 
    @if (request()->is('dashboard/developer') && !request()->is('dashboard/developer/*')) bg-red-500 text-white @else hover:bg-red-500 @endif 
    rounded-lg p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h18v4H3V3zm0 6h18v4H3V9zm0 6h18v4H3v-4z" />
                        </svg>
                        <span class="text-sm">Dev Dashboard</span>
                    </a>
                    </li>

                    <li class="my-1">
                        <div class="group">
                            <div
                                class="flex items-center justify-between menu-item-hover 
    @if (request()->is('dashboard/developer/*')) bg-red-500 text-white @else hover:bg-red-500 @endif 
    rounded-lg p-2 cursor-pointer">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <span class="text-sm">Manajemen DEV</span>
                                </div>
                                <svg class="size-4 transition-transform group-hover:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            {{-- Sub-menu (Selalu tampil saat active, tampil saat hover) --}}
                            <ul
                                class="mt-1 ml-6 pl-3 border-l border-gray-200 space-y-1 hidden group-hover:block 
                            @if (request()->is('dashboard/developer*'))  @endif">
                                <li>
                                    <a href="/dashboard/developer/account" @class([
                                        'block px-2 py-1 rounded hover:bg-red-100 text-sm',
                                        'text-red-500 font-bold' => request()->is('dashboard/developer/account'),
                                        'text-gray-500' => !request()->is('dashboard/developer/account'),
                                    ])>
                                        Account
                                    </a>
                                </li>

                                 <li>
                                    <a href="/dashboard/developer/trainingall" @class([
                                        'block px-2 py-1 rounded hover:bg-red-100 text-sm',
                                        'text-red-500 font-bold' => request()->is(
                                            'dashboard/developer/trainingall*'),
                                        'text-gray-500' => !request()->is('dashboard/developer/trainingall*'),
                                    ])>
                                        Semua Pelatihan
                                    </a>
                                </li>
                                <li>
                                    <a href="/dashboard/schedule" @class([
                                        'block px-2 py-1 rounded hover:bg-red-100 text-sm',
                                        'text-red-500 font-bold' => request()->is('dashboard/schedule'),
                                        'text-gray-500' => !request()->is('dashboard/schedule'),
                                    ])>
                                        Jadwal Pelatihan
                                    </a>
                                </li>

                                <li>
                                    <a href="/dashboard/developer/folder" @class([
                                        'block px-2 py-1 rounded hover:bg-red-100 text-sm',
                                        'text-red-500 font-bold' => request()->is('dashboard/developer/folder*'),
                                        'text-gray-500' => !request()->is('dashboard/developer/folder*'),
                                    ])>
                                        Folder
                                    </a>
                                </li>

                               
                                {{-- Tambah submenu lain di sini jika perlu --}}
                            </ul>
                        </div>
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
                @elseif ($role == 'viewer')
                    {{-- Menu untuk Management --}}
                    <li class="my-1">
                        <a href="/dashboard/viewers"
                            class="flex items-center space-x-2 menu-item-hover 
                               @if (request()->is('dashboard/viewers*')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h18v4H3V3zm0 6h18v4H3V9zm0 6h18v4H3v-4z" />
                            </svg>
                            <span class="text-sm">Viewer Dashboard</span>
                        </a>
                    </li>

                    <li class="my-1">
                        <a href="/dashboard/schedule"
                            class="flex items-center space-x-2 menu-item-hover 
                               @if (request()->is('/dashboard/schedule')) bg-red-500 text-white @else hover:bg-red-500 @endif 
                                  rounded-lg p-2">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="text-sm">Jadwal Pelatihan</span>
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

        <!-- Version Info -->
        <div class="p-4 mt-auto">
            <span class="text-sm">Version: Beta V1.7.1 </span>
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
