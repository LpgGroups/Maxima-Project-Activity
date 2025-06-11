@extends('layout.main')
@section('container')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="w-full max-w-sm mx-auto bg-white shadow-lg rounded-2xl p-8">
            <img class="mx-auto w-auto h-12 mb-3" src="/img/maximalog.png" alt="LPG">
            <h2 class="text-center text-2xl font-bold text-[#10496C] mb-1">Lupa Password?</h2>
            <p class="text-xs text-center mb-5">Masukkan email terdaftar, link reset password akan dikirim ke email kamu.</p>

            @if (session('status'))
                <div class="mb-4 text-center text-green-700 bg-green-100 border border-green-400 px-4 py-2 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-center text-red-700 bg-red-100 border border-red-400 px-4 py-2 rounded">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="block text-xs font-medium text-[#10496C]">Email</label>
                    <input type="email" id="email" name="email" required autofocus
                        class="w-full px-3 py-2 border border-[#515151] rounded-md focus:ring focus:border-blue-300 mt-1 text-sm" />
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-b from-yellow-400 to-red-600 text-white py-2 rounded-lg font-semibold text-base mt-2">
                    Kirim Link Reset Password
                </button>
            </form>
            <div class="mt-5 text-center text-xs">
                <a href="{{ route('login.index') }}" class="text-indigo-600 hover:underline">Kembali ke Login</a>
            </div>
        </div>
    </div>
@endsection
