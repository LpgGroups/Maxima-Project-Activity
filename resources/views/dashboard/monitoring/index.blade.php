@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="max-w-7xl mx-auto mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Daftar Pelatihan Terdaftar</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-center">#</th>
                        <th class="px-4 py-3 text-center">No Surat</th>
                        <th class="px-4 py-3">Nama Pelatihan</th>
                        <th class="px-4 py-3">Perusahaan</th>
                        <th class="px-4 py-3">Nama PIC</th>
                        <th class="px-4 py-3">Tanggal Daftar</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($trainings as $training)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $training->no_letter }}</td>
                            <td class="px-4 py-2">{{ $training->activity }}</td>
                            <td class="px-4 py-2">{{ $training->name_company ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $training->user->name ?? '-' }}</td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($training->created_at)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('monitoring.show', $training->id) }}"
                                    class="text-blue-600 hover:text-blue-800 underline">
                                    Monitoring
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-6">
                {{ $trainings->links() }}
            </div>
        </div>

    </div>
@endsection
