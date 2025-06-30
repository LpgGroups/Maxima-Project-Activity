@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="max-w-7xl mx-auto mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Daftar Pelatihan Terdaftar</h2>

        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Nama Pelatihan</th>
                    <th class="px-4 py-2">Perusahaan</th>
                    <th class="px-4 py-2">Nama PIC</th>
                    <th class="px-4 py-2">Tanggal Daftar</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trainings as $training)
                    <tr>
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $training->activity }}</td>
                        <td class="px-4 py-2">{{ $training->company_name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $training->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($training->created_at)->format('d M Y') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('monitoring.show', $training->id) }}"
                                class="text-blue-600 underline">Monitoring</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
