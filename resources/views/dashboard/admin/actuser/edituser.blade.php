@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="w-full max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Edit Data User</h2>

        <form action="{{ route('users.update', $users->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block font-medium text-sm mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $users->name) }}" required
                    class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div class="mb-4">
                <label for="email" class="block font-medium text-sm mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $users->email) }}" required
                    class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div class="mb-4">
                <label for="company" class="block font-medium text-sm mb-1">Perusahaan</label>
                <input type="text" name="company" id="company" value="{{ old('company', $users->company) }}"
                    class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div class="mb-4">
                <label for="phone" class="block font-medium text-sm mb-1">Nomor WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $users->phone) }}"
                    class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div class="mb-4">
                <label for="role" class="block font-medium text-sm mb-1">Role</label>
                <select name="role" id="role"
                    class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="user" {{ $users->role == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $users->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="management" {{ $users->role == 'management' ? 'selected' : '' }}>Management</option>
                    <option value="dev" {{ $users->role == 'dev' ? 'selected' : '' }}>Developer</option>
                    <option value="viewer" {{ $users->role == 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="is_active" class="block font-medium text-sm mb-1">Status Akun</label>
                <select name="is_active" id="is_active"
                    class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="1" {{ $users->is_active ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$users->is_active ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2 hover:bg-gray-600">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection
