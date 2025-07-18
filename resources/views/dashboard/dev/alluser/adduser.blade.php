@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="flex justify-center items-center min-h-screen bg-gray-100 px-4 py-8">
        <div class="w-full max-w-xl bg-white shadow-md rounded-lg p-6 space-y-6">
            <h2 class="text-2xl font-bold text-center text-gray-800">Daftar Akun</h2>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('dashboard.dev.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-indigo-200 focus:border-indigo-400"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Company -->
                <div>
                    <label for="company" class="block font-medium text-sm text-gray-700">Perusahaan (Opsional)</label>
                    <input type="text" name="company" id="company" value="{{ old('company') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-indigo-200 focus:border-indigo-400"
                        placeholder="Masukkan nama perusahaan">
                    @error('company')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-indigo-200 focus:border-indigo-400"
                        placeholder="email@example.com">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block font-medium text-sm text-gray-700">Nomor WhatsApp</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-indigo-200 focus:border-indigo-400"
                        placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block font-medium text-sm text-gray-700">Role</label>
                    <select name="role" id="role"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-indigo-200 focus:border-indigo-400">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="management" {{ old('role') == 'management' ? 'selected' : '' }}>Management</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="dev" {{ old('role') == 'dev' ? 'selected' : '' }}>Dev</option>
                        <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                    </select>
                    @error('role')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-indigo-200 focus:border-indigo-400"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-indigo-200 focus:border-indigo-400"
                        placeholder="Ulangi password">
                </div>

                <!-- Tombol Daftar -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-md transition duration-300">
                        Daftar Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
