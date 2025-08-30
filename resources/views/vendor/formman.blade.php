@extends('layout.main')

@section('container')
    <div class="min-h-[80vh] flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            @if (session('status'))
                <div class="mb-4 rounded-lg border p-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rounded-2xl border p-6 shadow-sm bg-white/60 backdrop-blur">
                <h1 class="text-2xl font-semibold mb-6 text-center">Masuk ke Aplikasi</h1>

                <form method="POST" action="{{ route('form.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus
                            class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-offset-1"
                            placeholder="nama@contoh.com" />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-1">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-offset-1"
                            placeholder="••••••••" />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="remember" class="rounded border">
                            <span>Ingat saya</span>
                        </label>

                        {{-- Tambahkan link lupa password jika diperlukan --}}
                        {{-- <a href="{{ route('password.request') }}" class="text-sm underline">Lupa password?</a> --}}
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl px-4 py-2 font-semibold shadow hover:shadow-md transition">
                        Masuk
                    </button>
                </form>
            </div>

            {{-- Contoh tombol logout saat sudah login (letakkan di halaman lain yang protected) --}}
            {{-- <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
      @csrf
      <button class="text-sm underline">Keluar</button>
    </form> --}}
        </div>
    </div>
@endsection
