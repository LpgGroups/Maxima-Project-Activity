@extends('layout.main')

@section('container')
    <div class="flex h-screen flex-col md:flex-row">
        <!-- LEFT: Poster -->
        <div class="hidden md:block md:flex-[6] bg-cover bg-center relative animate-zoom-loop"
            style="background-image: url('/img/bg-login.webp');">
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="text-center text-white px-6">
                    <h1 class="text-3xl font-bold animate-slide-left-to-right">Selamat Datang</h1>
                    <p class="mt-2 text-sm animate-slide-right-to-left font-bold">Akses fitur terbaik dari E-Registrasi
                        Maxima</p>
                    <p class="mt-4 text-sm animate-fadeInUp">Permudah akses Anda untuk membuat <strong
                            class="text-yellow-300">Jadwal Pelatihan</strong> </br>Melalui Web Aplikasi <strong
                            class="text-yellow-300">E-Registrasi Maxima</strong></p>
                </div>
            </div>
        </div>

        <!-- RIGHT: Login Aside -->
        <aside class="w-full md:flex-[4] bg-white flex ">
            <div class="w-full px-12 mt-20">
                <img class="mx-auto mb-4 w-32 h-auto" src="/img/maximalog.png" alt="Logo Maxima">
                <h2 class="text-center text-3xl font-bold tracking-tight text-[#10496C]">Login</h2>
                <p class="text-xs text-center mt-2">Masukan Email dan Password Anda dengan benar.</p>

                @if ($errors->any())
                    <div class="mt-4 text-sm text-red-600 bg-red-100 border border-red-400 rounded p-2 text-center">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form id="loginForm" class="space-y-6 mt-6" action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="relative">
                        <input id="email" name="email" type="text"
                            class="peer w-full border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                            placeholder=" " />
                        <label for="email"
                            class="absolute text-base bg-white text-[#515151] transition-all duration-300 transform -translate-y-6 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-[#1E6E9E]">
                            Email
                        </label>
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <input id="password" name="password" type="password"
                            class="peer w-full border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                            placeholder=" " />
                        <label for="password"
                            class="absolute text-base bg-white text-[#515151] transition-all duration-300 transform -translate-y-6 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-[#1E6E9E]">
                            Password
                        </label>
                    </div>

                    <p class="text-right text-xs text-gray-500">
                        Lupa Password?
                        <a href="{{ route('password.request') }}"
                            class="font-semibold text-indigo-600 hover:text-indigo-500">Klik Di Sini</a>
                    </p>

                    <div>
                        <button id="loginBtn" type="submit"
                            class="w-full rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline focus:ring-2 focus:ring-indigo-600"
                            style="background: linear-gradient(180deg, #ffc815 0%, #ff0000 100%);">
                            Login
                        </button>
                    </div>
                </form>

                <p class="text-center mt-4 text-xs text-gray-500">
                    Belum Memiliki Akun?
                    <a href="{{ '/register' }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Daftar</a>
                </p>
            </div>
        </aside>
    </div>

    <!-- Loader -->
    <div id="page-loader" class="fixed inset-0 z-50 bg-white bg-opacity-60 backdrop-blur-sm hidden">
        <div class="flex flex-col items-center justify-center h-full">
            <img src="/img/maximalog.png" alt="Logo" class="w-20 h-20 animate-flip-horizontal opacity-90" />
            <p class="mt-6 text-sm text-gray-700">Sedang memuat halaman...</p>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/authentifikasi.js')
@endpush
