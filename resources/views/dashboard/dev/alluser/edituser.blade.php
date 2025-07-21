@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-2xl mx-auto mt-10 bg-white border border-gray-200 rounded-xl shadow-sm p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit User Data</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('dashboard.dev.update', ['id' => $users->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $users->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $users->email) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                <input type="text" name="company" id="company" value="{{ old('company', $users->company) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $users->phone) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="user" {{ $users->role == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $users->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="management" {{ $users->role == 'management' ? 'selected' : '' }}>Management</option>
                    <option value="dev" {{ $users->role == 'dev' ? 'selected' : '' }}>Developer</option>
                    <option value="viewer" {{ $users->role == 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>
            </div>

            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status Akun</label>
                <select name="is_active" id="is_active"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1" {{ $users->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$users->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                    Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('dashboard.dev.alluser') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 transition">
                    ❌ Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                    💾 Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
