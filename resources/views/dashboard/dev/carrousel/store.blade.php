@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-xl font-semibold mb-4">{{ $title }}</h1>

        <form action="{{ route('carrousel.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium">Judul</label>
                <input type="text" name="title" id="title"
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                    value="{{ old('title') }}" required>
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="summary" class="block text-sm font-medium">Deskripsi (opsional)</label>
                <textarea name="summary" id="summary"
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200" rows="3">{{ old('summary') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium">Gambar</label>
                <input type="file" name="image" id="image"
                    class="mt-1 block w-full border border-gray-300 rounded p-1" required>
                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="order" class="block text-sm font-medium">Urutan</label>
                <input type="number" name="order" id="order"
                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                    value="{{ old('order', 0) }}">
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                    class="mr-2 rounded border-gray-300">
                <label for="is_active" class="text-sm">Aktif</label>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('carrousel.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Kembali</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection
