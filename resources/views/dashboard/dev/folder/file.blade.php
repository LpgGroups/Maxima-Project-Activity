@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="max-w-full mx-auto my-6 p-4 bg-white rounded-lg shadow">
        <h1 class="text-xl font-bold mb-4">{{ $title }}</h1>

        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-2 bg-red-200 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        {{-- FORM FILTER (GET) --}}
        <form method="GET" class="flex flex-wrap gap-3 items-center mb-3">
            <select name="status" class="border rounded px-2 py-1">
                <option value="">Semua Status</option>
                <option value="5-1" {{ request('status') == '5-1' ? 'selected' : '' }}>Selesai</option>
                <option value="5-2" {{ request('status') == '5-2' ? 'selected' : '' }}>Ditolak</option>
                <option value="4-0" {{ request('status') == '4-0' ? 'selected' : '' }}>Proses</option>
                <option value="5-0" {{ request('status') == '5-0' ? 'selected' : '' }}>Menunggu</option>
            </select>
            <input type="date" name="date_start" value="{{ request('date_start') }}" class="border rounded px-2 py-1"
                placeholder="Mulai">
            <input type="date" name="date_end" value="{{ request('date_end') }}" class="border rounded px-2 py-1"
                placeholder="Selesai">
            <select name="sort" class="border rounded px-2 py-1">
                <option value="">Urutkan</option>
                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filter</button>
            @if (request()->hasAny(['status', 'date_start', 'date_end', 'sort']))
                <a href="{{ route('folder.show', $folderName) }}" class="text-red-600 ml-2 underline">Reset</a>
            @endif
        </form>

        {{-- FORM BULK DELETE & DOWNLOAD --}}
        <form method="POST" action="{{ route('folder.bulkDelete', $folderName) }}" id="bulkDeleteForm">
            @csrf
            <div class="mb-3 flex flex-wrap gap-3 items-center">
                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded shadow"
                    onclick="selectAllFiles()">Pilih Semua</button>
                <button type="submit" class="bg-red-500 hover:bg-red-900 text-white px-4 py-2 rounded shadow"
                    onclick="return confirm('Yakin ingin hapus file terpilih?')">Hapus File Terpilih</button>
                <button type="button" class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded shadow"
                    onclick="submitBulkDownload()">Download File Terpilih</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs border border-gray-200 rounded">
                    <thead class="bg-slate-700 text-white">
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" style="width:20px;height:20px;accent-color:#3b82f6;">
                            </th>
                            <th class="py-2 px-2 text-left">Nama File</th>
                            <th class="py-2 px-2 text-center">Status</th>
                            <th class="py-2 px-2 text-center">Tanggal Pelatihan</th>
                            <th class="py-2 px-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($files as $file)
                            <tr class="odd:bg-white even:bg-gray-100">
                                <td class="py-2 px-2 text-center">
                                    <input type="checkbox" name="files[]" value="{{ $file['basename'] }}"
                                        class="bg-blue-200">
                                </td>
                                <td class="py-2 px-2 break-all">{{ $file['basename'] }}</td>
                                <td class="p-1 text-center">
                                    @php $training = $file['training'] ?? null; @endphp
                                    @if ($training)
                                        @if ($training->isprogress == 5 && $training->isfinish == 1)
                                            <span class="bg-green-600 font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">Selesai</span>
                                        @elseif ($training->isprogress == 5 && $training->isfinish == 2)
                                            <span class="bg-red-600 font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">Ditolak</span>
                                        @elseif ($training->isprogress == 5 && $training->isfinish == 0)
                                            <span class="bg-yellow-400 font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">Menunggu</span>
                                        @elseif ($training->isprogress < 5)
                                            <span class="bg-blue-400 font-semibold text-[10px] px-2 py-[2px] rounded inline-block w-[70px] text-center">Proses</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-1 text-center">
                                    @if ($training)
                                        {{ \Carbon\Carbon::parse($training->date)->format('d M Y') }}
                                        @if ($training->date_end && $training->date_end != $training->date)
                                            - {{ \Carbon\Carbon::parse($training->date_end)->format('d M Y') }}
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2 px-2 text-center">
                                    <a href="{{ $file['url'] }}" target="_blank"
                                        class="text-blue-600 hover:underline">Lihat</a>
                                    <a href="{{ $file['download_route'] }}"
                                        class="text-green-600 hover:underline ml-3">Download</a>
                                    <button type="button" class="text-red-600 hover:underline ml-3"
                                        onclick="deleteFile('{{ $file['basename'] }}')">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-6 italic">Tidak ada file ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        {{-- FORM DELETE INDIVIDU --}}
        @foreach ($files as $file)
            <form id="delete-form-{{ $file['basename'] }}" action="{{ $file['delete_route'] }}" method="POST"
                style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        {{-- Bulk Download Form --}}
        <form method="POST" action="{{ route('folder.bulkDownload', $folderName) }}" id="bulkDownloadForm"
            style="display:none;">
            @csrf
            <input type="hidden" name="files" id="filesToDownload">
        </form>
    </div>

    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll("input[type='checkbox'][name='files[]']");
            for (let cb of checkboxes) cb.checked = this.checked;
        });

        // Button "Pilih Semua"
        function selectAllFiles() {
            let checkboxes = document.querySelectorAll("input[type='checkbox'][name='files[]']");
            for (let cb of checkboxes) cb.checked = true;
            document.getElementById('selectAll').checked = true;
        }

        // Bulk Download function
        function submitBulkDownload() {
            let checked = document.querySelectorAll("input[type='checkbox'][name='files[]']:checked");
            let names = Array.from(checked).map(cb => cb.value);
            if (names.length === 0) {
                alert('Pilih minimal satu file!');
                return;
            }
            document.getElementById('filesToDownload').value = names.join(',');
            document.getElementById('bulkDownloadForm').submit();
        }

        function deleteFile(basename) {
            if (confirm('Yakin hapus file ini?')) {
                document.getElementById('delete-form-' + basename).submit();
            }
        }
    </script>
@endsection
