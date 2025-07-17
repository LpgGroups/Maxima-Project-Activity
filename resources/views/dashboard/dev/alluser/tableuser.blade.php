@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="w-full h-auto bg-white rounded-2xl shadow-md p-4 sm:mb-0">
        <h2 class="text-xl font-semibold mb-4">Daftar User</h2>
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">No</th>
                    <th class="border px-4 py-2 text-left">Nama</th>
                    <th class="border px-4 py-2 text-left">Email</th>
                    <th class="border px-4 py-2 text-left">Perusahaan</th>
                    <th class="border px-4 py-2 text-left">Telepon</th>
                    <th class="border px-4 py-2 text-left">Role</th>
                    <th class="border px-4 py-2 text-left">Status</th>
                    <th class="border px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2">{{ $user->company ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $user->phone ?? '-' }}</td>
                        <td class="border px-4 py-2 capitalize">{{ $user->role }}</td>
                        <td class="border px-4 py-2">
                            @if ($user->is_active)
                                <span class="text-green-600 font-semibold">Aktif</span>
                            @else
                                <span class="text-red-600 font-semibold">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('dashboard.dev.edit', $user->id) }}"
                                class="text-blue-600 hover:underline mr-2">Edit</a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Yakin ingin hapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="border px-4 py-2 text-center text-gray-500">Belum ada data user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
