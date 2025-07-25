@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $title }}</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('carrousel.create') }}"
                class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Carousel
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 border">#</th>
                        <th class="px-4 py-3 border">Judul</th>
                        <th class="px-4 py-3 border">Ringkasan</th>
                        <th class="px-4 py-3 border">Gambar</th>
                        <th class="px-4 py-3 border">Aktif</th>
                        <th class="px-4 py-3 border">Urutan</th>
                        <th class="px-4 py-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse($carrousels as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3 border">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border">{{ $item->title }}</td>
                            <td class="px-4 py-3 border">{{ $item->summary }}</td>
                            <td class="px-4 py-3 border">
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-24 h-auto rounded shadow">
                            </td>
                            <td class="px-4 py-3 border">
                                <span
                                    class="px-2 py-1 text-xs rounded {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $item->is_active ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border">{{ $item->order }}</td>
                            <td class="px-4 py-3 border text-center space-x-2">
                                <a href="{{ route('carrousel.edit', $item->id) }}"
                                    class="inline-block bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">
                                    Edit
                                </a>

                                <form action="{{ route('carrousel.destroy', $item->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data carousel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
