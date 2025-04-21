@extends('layout.main')
@section('container')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto w-auto h-auto top-[44px] left-[95px]" src="./img/maximalog.png" alt="LPG">
            <h2 class="top-[12px] text-center text-3xl font-bold tracking-tight text-[#10496C]">Login</h2>
            <p class="text-xs text-center mx-14 mt-2">Masukan Email dan Password
                Anda dengan benar.</p>
        </div>

        @if ($errors->any())
            <div
                class="inline-block max-w-sm text-sm text-red-600 bg-red-100 border border-red-400 rounded p-2 text-center mx-auto">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif


        <div class="mt-4 sm:mx-auto sm:w-full sm:max-w-sm">
            <form id="loginForm" class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <!-- Email Input Field -->
                <div class="relative mb-6">
                    <input id="email" name="email" type="text"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="" />
                    <label for="email"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-6 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Username</label>
                </div>

                <!-- Password Field -->
                <div class="relative mb-6">
                    <input id="password" name="password" type="password"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder=" " />
                    <label for="password"
                        class="absolute text-base bg-[#fffefe] text-[#515151] transition-all duration-300 transform -translate-y-6 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-6 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">Password</label>
                </div>
                <p class="mt-10 text-center text-xs text-gray-500">
                    Lupa Password?
                    <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">Klik Disini</a>
                </p>

                <!-- Submit Button -->
                <div>
                    <button id="loginBtn" type="submit"
                        class="flex w-full justify-center rounded-md px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600"
                        style="background: linear-gradient(180deg, #ffc815 0%, #ff0000 100%);">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="page-loader"
    class="fixed inset-0 z-50 bg-white bg-opacity-60 backdrop-blur-sm hidden">
    <div class="flex flex-col items-center justify-center h-full">
        <img src="/img/maximalog.png" alt="Logo" class="w-20 h-20 animate-flip-horizontal opacity-90" />
        <p class="mt-6 text-sm text-gray-700">Sedang memuat halaman...</p>
    </div>
</div>

@endsection
@push('scripts')
  @vite('resources/js/authentifikasi.js')

@endpush
