@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="p-4 border border-t-0 border-gray-300 bg-white rounded-lg mt-6">
        <h1 class="text-[24px] font-semibold">Daftar Pelatihan</h1>
        <h2 class="text-[15px]">Lengkapi data form ini untuk mengikuti pelatihan yang akan diselenggarakan oleh PT
            Maxima Aksara Jaya Utama, pastikan Anda mengisi form aplikasi pendaftaran dengan benar dan sesuai data
            yang valid.</h2>
        <form id="editFormbyAdmin" action="/dashboard/admin/training/{{ $training->id }}" method="POST"
            data-training-id="{{ $training->id }}">
            @csrf
            <div class="flex gap-x-4">
                <!-- Nama PIC Perusahaan -->
                <div class="relative mt-4 w-64">
                    <input id="name_pic" name="name_pic" type="text"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="" required pattern="[A-Za-z\s]+" title="Nama PIC hanya boleh berisi huruf dan spasi."
                        value="{{ old('name_pic', $training->name_pic ?? '') }}" />
                    <label for="name_pic"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        Nama PIC
                    </label>
                </div>

                <!-- Nama Perusahaan -->
                <div class="relative mt-4 w-64">
                    <input id="name_company" name="name_company" type="text"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="" required title="Nama Perusahaan hanya boleh berisi huruf, angka, dan spasi."
                        value="{{ old('name_company', $training->name_company ?? '') }}" />
                    <label for="name_company"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        Nama Perusahaan
                    </label>
                </div>
            </div>

            <div class="flex gap-x-4 mt-2">
                <!-- Email PIC -->
                <div class="relative mt-4 w-64">
                    <input id="email_pic" name="email_pic" type="email"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="" required value="{{ old('email_pic', $training->email_pic ?? '') }}" />
                    <label for="email_pic"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        Email PIC
                    </label>
                </div>

                <!-- No WhatsApp -->
                <div class="relative mt-4 w-64">
                    <input id="phone_pic" name="phone_pic" type="text"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="" required title="No WhatsApp harus berupa nomor telepon yang valid."
                        value="{{ old('phone_pic', $training->phone_pic ?? '') }}" />
                    <label for="phone_pic"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        No WhatsApp
                    </label>
                    <span class="ml-1 text-sm text-gray-500 cursor-pointer relative group">
                        Ex:0818080808
                    </span>
                    @error('phone_pic')
                        <div class="text-red-500 text-sm mt-1 z-10">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex gap-x-4 mt-2">
                <div class="relative mt-4 w-64">
                    <select id="activity" name="activity"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E]"
                        required>
                        <option value="" disabled selected hidden></option>
                        <option value="TKPK1"
                            {{ old('activity', $training->activity ?? '') == 'TKPK1' ? 'selected' : '' }}>TKPK1</option>
                        <option value="TKPK2"
                            {{ old('activity', $training->activity ?? '') == 'TKPK2' ? 'selected' : '' }}>TKPK2</option>
                        <option value="TKBT1"
                            {{ old('activity', $training->activity ?? '') == 'TKBT1' ? 'selected' : '' }}>TKBT1</option>
                        <option value="TKBT2"
                            {{ old('activity', $training->activity ?? '') == 'TKBT2' ? 'selected' : '' }}>TKBT2</option>
                        <option value="BE" {{ old('activity', $training->activity ?? '') == 'BE' ? 'selected' : '' }}>
                            BE</option>
                        <option value="P3K" {{ old('activity', $training->activity ?? '') == 'P3K' ? 'selected' : '' }}>
                            P3K</option>
                        <option value="AK3U"
                            {{ old('activity', $training->activity ?? '') == 'AK3U' ? 'selected' : '' }}>
                            AK3U</option>
                    </select>

                    <label for="activity"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform scale-75 -translate-y-4 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        Kegiatan
                    </label>
                </div>

                <div class="relative mt-4 w-64">
                    @php
                        use Carbon\Carbon;
                        $formattedDate = old(
                            'date',
                            $training->date ? Carbon::parse($training->date)->format('Y-m-d') : '',
                        );
                    @endphp
                    <input id="date" name="date" type="text"
                        value="{{ \Carbon\Carbon::parse($training->date)->format('d-m-Y') }}"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="" required /> <label for="date"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        Tanggal Kegiatan
                    </label>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-sm font-medium text-red-600 text-[10px]">Selesai Pada:</p>
                        <input type="text" id="end_date" name="end_date" readonly
                            value="{{ old('end_date', \Carbon\Carbon::parse($training->date_end)->format('Y-m-d')) }}"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm w-28 bg-gray-100 text-gray-700" />
                    </div>
                </div>

            </div>
            <label class="block mt-2 mb-2 text-sm font-medium text-gray-900">Tempat Pelatihan:</label>
            <div class="flex min-h-5">
                <div class="flex items-center justify-center gap-x-4 border border-gray-300 rounded-lg h-12 px-4">
                    <div class="flex items-center">
                        <input id="radio-online" type="radio" value="Online" name="colored-radio"
                            class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ $training->place == 'Online' ? 'checked' : '' }}>
                        <label for="radio-online" class="ms-2 text-sm font-medium text-gray-900 ">Online</label>
                    </div>
                    <div class="flex items-center">
                        <input id="radio-offline" type="radio" value="Offline" name="colored-radio"
                            class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ $training->place == 'Offline' ? 'checked' : '' }}>
                        <label for="radio-offline" class="ms-2 text-sm font-medium text-gray-900 ">Offline</label>
                    </div>
                    <div class="flex items-center">
                        <input id="radio-blended" type="radio" value="Blended" name="colored-radio"
                            class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ $training->place == 'Blended' ? 'checked' : '' }}>
                        <label for="radio-blended" class="ms-2 text-sm font-medium text-gray-900 ">Blended</label>
                    </div>
                </div>
            </div>


            <div class="mt-4 flex items-center gap-2">
                <input type="checkbox" id="confirmEdit"
                    class="h-5 w-5 appearance-none border-2 border-gray-400 rounded-sm checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200" />
                <label for="confirmEdit" class="text-[12px] text-gray-700">
                    Saya telah memverifikasi dan memastikan bahwa seluruh data yang diinput telah sesuai dengan informasi
                    peserta yang valid. Jika terdapat perubahan, telah dilakukan berdasarkan konfirmasi dan bukti yang sah
                </label>
            </div>

            <button id="submitBtn" disabled
                class="mt-4 px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed transition disabled:opacity-50">
                Update Data
            </button>
        </form>
    </div>

    <div class="p-4 border border-t-0 border-gray-300 bg-white rounded-lg mt-4">
        <h1 class="text-[24px] font-semibold">Peserta Terdaftar</h1>
        <h2 class="text-[15px]">Sebelum menyimpan atau mengirim data pelatihan, pastikan hal-hal berikut telah diperiksa
            dan sesuai dengan data peserta:</h2>
        <div class="rounded-2xl p-2 w-full mt-4">
            <!-- Form untuk menambah peserta -->
            <button id="submitParticipation" class="mb-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Tambah Peserta
            </button>

            <!-- Form untuk update semua peserta -->
            <form id="updateParticipantsForm" data-form-id="{{ $training->id }}">
                @csrf
                <div class="overflow-y-auto max-h-[300px] shadow-lg rounded-md border bg-white">
                    <table class="table-auto w-full text-center align-middle">
                        <thead>
                            <tr class="bg-slate-600 text-white text-[10px] lg:text-sm">
                                <th class="w-[24px]">No</th>
                                <th class="w-[200px]">Peserta</th>
                                <th class="w-[120px]">Status</th>
                                <th>Catatan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($training->participants as $index => $participant)
                                <tr class="odd:bg-[#d9f6fd] even:bg-white text-[12px]"
                                    data-participant-id="{{ $participant->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <input type="text" name="participants[{{ $participant->id }}][name]"
                                            value="{{ $participant->name }}"
                                            class="border px-2 py-1 w-full bg-transparent">
                                    </td>
                                    <td>
                                        <select name="participants[{{ $participant->id }}][status]"
                                            class="px-4 py-1 w-full bg-transparent">
                                            <option value="1" {{ $participant->status == 1 ? 'selected' : '' }}>
                                                🔄Waiting</option>
                                            <option value="0" {{ $participant->status == 0 ? 'selected' : '' }}>
                                                ❌Rejected</option>
                                            <option value="2" {{ $participant->status == 2 ? 'selected' : '' }}>
                                                ✅Success</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="participants[{{ $participant->id }}][reason]"
                                            value="{{ $participant->reason ?? '' }}"
                                            class="border px-2 py-1 w-full bg-transparent">
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="text-red-600 hover:text-red-800 deleteButtonParticipant"
                                            data-id="{{ $participant->id }}" title="Hapus Peserta">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500 italic">
                                        Data peserta belum tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </form>
        </div>

        <div class="mt-4">
            <h2>PIC yang telah mendaftarkan pesertanya pada tabel diatas telah melampirkan link berkas file yang telah
                diupload untuk ditinjau oleh admin.</h2>

            <h1 class="mt-2">
                <a href="{{ $training->link }}"
                    class="text-blue-600 underline inline-flex items-center gap-2 max-w-xs truncate" target="_blank"
                    title="{{ $training->link }}">

                    <!-- Ikon SVG dari file lokal -->
                    <img src="/img/svg/attachment.svg" alt="attachment" class="h-4 w-4 shrink-0">

                    <!-- Teks link -->
                    <span class="truncate">{{ $training->link }}</span>
                </a>
            </h1>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <input type="checkbox" id="confirmEdit2"
                class="h-5 w-5 appearance-none border-2 border-gray-400 rounded-sm checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200" />
            <label for="confirmEdit2" class="text-[12px] text-gray-700">
                Saya telah memverifikasi dan memastikan bahwa seluruh data yang diinput telah sesuai dengan informasi
                peserta yang valid. Jika terdapat perubahan, telah dilakukan berdasarkan konfirmasi dan bukti yang sah
            </label>
        </div>
        <button type="button" id="submitParticipantBtn"
            class="mt-4 px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed transition disabled:opacity-50">
            Simpan Semua Peserta
        </button>
    </div>

    <div class="p-4 border border-t-0 border-gray-300 bg-white rounded-lg mt-4">
        <h1 class="text-[24px] font-semibold">Upload persetujuan</h1>
        <p class="text-[15px]">Pastikan bahwa dokumen MoU (Memorandum of Understanding) dan Quotation (Penawaran Harga)
            dari client
            diperiksa dengan cermat:</p>

        @forelse ($training->files as $file)
            @if ($file->file_approval)
                <div class="flex mt-4">

                    <img src="{{ asset('img/icon_pdf_mou.png') }}" alt="PDF Icon" width="50"
                        style="margin-right: 15px;">


                    <div>
                        <div><strong>File MOU:</strong> {{ basename($file->file_approval) }}</div>
                        <a href="{{ asset('storage/' . $file->file_approval) }}" target="_blank"
                            class="w-full h-20 p-1 bg-red-500 rounded mt-2 text-[10px]">
                            Download
                        </a>
                    </div>
                </div>
            @endif
        @empty
            <div class="mt-4 text-gray-500">
                <strong>Data File Belum Tersedia</strong>
            </div>
        @endforelse
        <div class="mt-4 flex flex-col gap-2">
            <!-- Checkbox di atas -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="confirmEdit3"
                    class="h-5 w-5 appearance-none border-2 border-gray-400 rounded-sm checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200" />
                <label for="confirmEdit3" class="text-sm text-gray-700">
                    Saya yakin data yang diubah sudah benar
                </label>
            </div>

            <!-- Tombol dan Progress Bar di satu baris -->
            <div class="flex items-center gap-4">
                <button type="button" id="submitFinish" data-form-id="{{ $training->id }}"
                    class="px-4 py-2 bg-gray-400 text-white rounded-md">
                    Approve Pelatihan
                </button>

                @php
                    $progressValue = $training->isprogress;
                    $progressMap = [
                        1 => ['percent' => 10, 'color' => 'bg-red-600'],
                        2 => ['percent' => 30, 'color' => 'bg-orange-500'],
                        3 => ['percent' => 50, 'color' => 'bg-yellow-400'],
                        4 => ['percent' => 75, 'color' => 'bg-lime-600'],
                        5 => ['percent' => 100, 'color' => 'bg-green-600'],
                    ];
                    $progress = $progressMap[$progressValue] ?? ['percent' => 0, 'color' => 'bg-gray-400'];
                @endphp

                <div class="w-[100px] h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                    <div class="{{ $progress['color'] }} text-[8px] font-medium text-white text-center leading-none rounded-full"
                        style="width: {{ $progress['percent'] }}%; height:8px">
                        {{ $progress['percent'] }}%
                    </div>
                </div>
            </div>
        </div>


    </div>

    @push('scripts')
        @vite('resources/js/edittrainingadmin.js')
    @endpush
@endsection
