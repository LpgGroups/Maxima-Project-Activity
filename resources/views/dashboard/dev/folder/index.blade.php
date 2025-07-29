@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="max-w-2xl mx-auto my-8 p-6 bg-white rounded-lg shadow">
        <h1 class="text-xl font-bold mb-4">{{ $title }}</h1>
        @if (session('error'))
            <div class="mb-4 px-4 py-2 bg-red-200 text-red-800 rounded">{{ session('error') }}</div>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border">
                <thead class="bg-slate-700 text-white">
                    <tr>
                        <th class="py-2 px-4 text-left">#</th>
                        <th class="py-2 px-4 text-left">Nama Folder</th>
                        <th class="py-2 px-4 text-center">Jumlah File</th>
                        <th class="py-2 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($folders as $index => $folder)
                        <tr class="odd:bg-white even:bg-gray-100">
                            <td class="py-2 px-4">{{ $index + 1 }}</td>
                            <td class="py-2 px-4">
                                <a href="{{ route('folder.show', $folder['name']) }}"
                                    class="text-blue-700 hover:underline font-semibold">{{ $folder['name'] }}</a>
                            </td>
                            <td class="py-2 px-4 text-center">{{ $folder['count'] }}</td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-6 italic">Tidak ada folder ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
