@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex justify-between items-start p-4 bg-gray-50 border rounded-xl shadow mb-4">
        <!-- Kiri: Token -->
        <div class="flex items-center gap-2 flex-wrap">
            <span class="font-semibold text-gray-700">Token:</span>
            <span id="token-value"
                class="px-3 py-1 rounded-md font-mono bg-gray-200 text-blue-600 text-base tracking-wide select-all">
                {{ $training->code_training }}
            </span>
            <button id="copy-token"
                class="flex items-center gap-1 px-2 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold shadow"
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"
                        fill="none" />
                    <rect x="2" y="2" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"
                        fill="none" />
                </svg>
                Copy
            </button>
            <span id="copied-msg"
                class="ml-2 text-green-500 text-xs opacity-0 transition-opacity duration-300">Copied!</span>
        </div>

        <!-- Kanan: No Surat -->
        <div class="flex items-center gap-2 whitespace-nowrap">
            <span class="text-sm text-gray-600 font-semibold" id="no-letter-value">
                No: {{ $training->no_letter }}
            </span>
            <button id="copy-letter"
                class="flex items-center gap-1 px-2 py-1 rounded-lg bg-green-600 hover:bg-green-700 transition text-white text-sm font-semibold shadow"
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"
                        fill="none" />
                    <rect x="2" y="2" width="13" height="13" rx="2" stroke="currentColor" stroke-width="2"
                        fill="none" />
                </svg>
                Copy
            </button>
            <span id="copied-letter-msg"
                class="text-green-500 text-xs opacity-0 transition-opacity duration-300">Copied!</span>
        </div>
    </div>


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
                @if ($training->isfinish == 2)
                    <div class="border border-red-500 h-30 w-[300px] rounded-lg p-2">
                        <h3 class="text-xl font-bold mb-2 text-red-600 text-center">Pelatihan Ditolak</h3>
                        <p class="text-[10px] text-gray-700 text-justify">
                            Mohon maaf, pengajuan pelatihan ini <strong>tidak dapat dilanjutkan</strong>.
                            <br><br>
                            <strong>Alasan penolakan:</strong><br>
                            <span class="text-red-600 italic">
                                {{ $training->reason_fail ?? 'Tidak ada alasan yang tersedia.' }}
                            </span>
                        </p>
                    </div>
                @endif
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
                        <option value="P3K"
                            {{ old('activity', $training->activity ?? '') == 'P3K' ? 'selected' : '' }}>
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

            <div>

                @php
                    $cityOptions = [
                        'Bali',
                        'Balikpapan',
                        'Bogor',
                        'Ciracas',
                        'Jakarta',
                        'Makassar',
                        'Malang',
                        'Medan',
                        'Palangkaraya',
                        'Palembang',
                        'Pekanbaru',
                        'Pontianak',
                        'Semarang',
                        'Surabaya',
                    ];
                @endphp

                <div class="mb-4">
                    <label for="city" class="block text-gray-600 text-base font-bold mb-1">Lokasi Pelatihan:</label>
                    <select name="city" id="city"
                        class="w-[200px] p-2 border border-gray-300 rounded-lg text-gray-800">
                        <option value="">-- Pilih Kota --</option>
                        @foreach ($cityOptions as $city)
                            <option value="{{ $city }}" {{ $training->city === $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
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
                        <input id="radio-offline" type="radio" value="On-Site" name="colored-radio"
                            class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ $training->place == 'On-Site' ? 'checked' : '' }}>
                        <label for="radio-offline" class="ms-2 text-sm font-medium text-gray-900 ">On-Site</label>
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
            {{-- <button id="submitParticipation" class="mb-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Tambah Peserta
            </button> --}}

            <!-- Form untuk update semua peserta -->
            <form id="participantTableForm" data-form-id="{{ $training->id }}">
                @csrf
                <input type="hidden" name="form_id" value="{{ $training->id }}">
                <div class="overflow-y-auto max-h-[400px] shadow-lg rounded-md border bg-white">
                    <table class="table-auto w-full text-center align-middle" id="participantsTable">
                        <thead>
                            <tr class="bg-slate-600 text-white text-xs">
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Tgl Lahir</th>
                                <th>Gol Darah</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($training->participants as $index => $participant)
                                <tr class="main-row odd:bg-[#d9f6fd] even:bg-white text-sm"
                                    data-participant-id="{{ $participant->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="max-w-[80px] truncate whitespace-nowrap" title="{{ $participant->name }}">
                                        {{ $participant->name }}</td>
                                    <td class="max-w-[90px]">
                                        @php
                                            $nik = $participant->nik;
                                            $repeat = max(0, strlen($nik) - 4);
                                            $hiddenNik = str_repeat('*', $repeat) . substr($nik, -4);
                                        @endphp

                                        <div class="flex items-center">
                                            <span class="nik-text"
                                                data-full="{{ $nik }}">{{ $hiddenNik }}</span>
                                            <button type="button"
                                                class="toggle-nik-btn text-blue-500 text-xs hover:underline">
                                                👁️
                                            </button>
                                        </div>
                                    </td>

                                    <td class="max-w-[50px] truncate whitespace-nowrap"
                                        title="{{ $participant->date_birth ? \Carbon\Carbon::parse($participant->date_birth)->translatedFormat('d F Y') : '-' }}">
                                        {{ $participant->date_birth ? \Carbon\Carbon::parse($participant->date_birth)->translatedFormat('d F Y') : '-' }}
                                    </td>

                                    <td class="max-w-[10px] truncate whitespace-nowrap">
                                        {{ $participant->blood_type }}
                                    </td>

                                    <td class="max-w-[70px]">
                                        <select name="participants[{{ $participant->id }}][status]"
                                            class="px-4 py-1 bg-transparent">
                                            <option value="0" {{ $participant->status == 0 ? 'selected' : '' }}>
                                                🔄Waiting</option>
                                            <option value="1" {{ $participant->status == 1 ? 'selected' : '' }}>
                                                ✅Success</option>
                                            <option value="2" {{ $participant->status == 2 ? 'selected' : '' }}>
                                                ❌Rejected</option>

                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" name="participants[{{ $participant->id }}][reason]"
                                            value="{{ $participant->reason }}" placeholder="Masukkan alasan"
                                            style="width: 100%;">
                                    </td>
                                    <td>
                                        <!-- Tombol Click (pakai ikon pointer) -->
                                        <button type="button" class="text-blue-700 showDetailBtn rounded"
                                            data-id="{{ $participant->id }}">
                                            <!-- SVG Ikon Kursor -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                class="w-5 h-5 mb-2 inline-block" stroke-width="1.5"
                                                stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>

                                        </button>

                                        <!-- Tombol Delete (pakai ikon tempat sampah) -->
                                        <button type="button" class="text-red-600 deleteButtonParticipant rounded"
                                            data-id="{{ $participant->id }}">
                                            <!-- SVG Ikon Tempat Sampah -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-2 inline-block"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path
                                                    d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6z" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                            </svg>
                                        </button>
                                    </td>

                                </tr>
                                {{-- Dropdown row, hidden by default --}}
                                <tr class="detail-row hidden" id="detail-row-{{ $participant->id }}">
                                    <td colspan="8" class="bg-gray-100 text-left px-4 py-3">
                                        <div class="mb-2 font-semibold text-[15px]">Dokumen Peserta</div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @php
                                                $files = [
                                                    'Foto' => $participant->photo,
                                                    'Ijazah' => $participant->ijazah,
                                                    'SK Kerja' => $participant->letter_employee,
                                                    'Surat Pernyataan' => $participant->letter_statement,
                                                    'Form Pendaftaran' => $participant->form_registration,
                                                    'SK Kesehatan' => $participant->letter_health,
                                                    'CV' => $participant->cv,
                                                ];
                                                $icons = [
                                                    'Foto' => '🖼️',
                                                    'Ijazah' => '📄',
                                                    'SK Kerja' => '📃',
                                                    'Surat Pernyataan' => '✍️',
                                                    'Form Pendaftaran' => '📝',
                                                    'SK Kesehatan' => '📑',
                                                    'CV' => '📁',
                                                ];
                                            @endphp
                                            @foreach ($files as $label => $file)
                                                <div
                                                    class="flex items-center gap-3 bg-white rounded-md shadow-sm px-3 py-2">
                                                    <span class="text-xl">{{ $icons[$label] ?? '📎' }}</span>
                                                    <span
                                                        class="font-medium min-w-[80px] w-[120px]">{{ $label }}</span>
                                                    @if ($file)
                                                        <a href="{{ asset('storage/' . $file) }}" download
                                                            class="ml-auto px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 transition text-xs font-semibold flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 inline-block" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                                            </svg>
                                                            Download
                                                        </a>
                                                    @else
                                                        <span class="ml-auto text-gray-400 text-xs italic">Belum
                                                            diunggah</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
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

    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm w-full mt-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-700">Upload File Budget Plan & Surat Pelaksanaan</h2>
        <form id="adminFileUploadForm" class="space-y-6">
            <input type="hidden" name="training_id" value="{{ $training->id }}">
            <div class="mt-2">
                <label class="block mb-2 mt-2 text-sm font-medium text-gray-900" for="budget_plan">
                    Upload Budget Plan (.pdf, .docx, .xls)
                </label>
                <input name="budget_plan" id="budget_plan" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                <p class="mt-1 text-sm text-gray-500">Format File: PDF, DOC, DOCX, XLS (Maks Size: 5MB).</p>

                @if (!empty($fileRequirement?->budget_plan))
                    <p class="text-sm text-green-600 mt-1">
                        File sudah diupload:
                        <strong>
                            <a href="{{ route('download.confidential', ['type' => 'budget-plan', 'file' => basename($fileRequirement->budget_plan)]) }}"
                                class="underline" target="_blank">
                                {{ basename($fileRequirement->budget_plan) }}
                            </a>

                        </strong>
                    </p>
                @endif
            </div>

            <div class="mt-2">
                <label class="block mb-2 mt-2 text-sm font-medium text-gray-900" for="letter_implementation">
                    Upload Surat Pelaksanaan (.pdf, .docx)
                </label>
                <input name="letter_implementation" id="letter_implementation" type="file" accept=".pdf,.doc,.docx"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                <p class="mt-1 text-sm text-gray-500">Format File: PDF, DOC, DOCX (Maks Size: 5MB).</p>

                @if (!empty($fileRequirement?->letter_implementation))
                    <p class="text-sm text-green-600 mt-1">
                        File sudah diupload:
                        <strong>
                            <a href="{{ route('download.confidential', ['type' => 'letter-implementation', 'file' => basename($fileRequirement->letter_implementation)]) }}"
                                class="underline">
                                {{ basename($fileRequirement->letter_implementation) }}
                            </a>
                        </strong>
                    </p>
                @endif
            </div>

            @if ($training->isfinish === 1)
                <div class="mt-2">
                    <label class="block mb-2 mt-2 text-sm font-medium text-gray-900" for="file_nobatch">
                        Upload Nomor Batch Kegiatan (.pdf)
                    </label>
                    <input name="file_nobatch" id="file_nobatch" type="file" accept=".pdf"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="mt-1 text-sm text-gray-500">Format File: PDF saja (Max. 2MB).</p>

                    @if (!empty($fileRequirement?->file_nobatch))
                        <p class="text-sm text-green-600 mt-1">
                            File sudah diupload:
                            <strong>
                                <a href="{{ route('download.confidential', ['type' => 'file-kemnaker', 'file' => basename($fileRequirement->file_nobatch)]) }}"
                                    class="underline" target="_blank">
                                    {{ basename($fileRequirement->file_nobatch) }}
                                </a>
                            </strong>
                        </p>
                    @endif
                </div>
            @endif


            <button type="button" id="uploadFileForAdminBtn"
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Upload
            </button>
            <div id="uploadAdminFileStatus" class="text-center text-sm mt-2"></div>
        </form>
    </div>


    <div class="p-4 border border-t-0 border-gray-300 bg-white rounded-lg mt-4">
        <h1 class="text-[24px] font-semibold">Upload persetujuan</h1>
        <p class="text-[15px]">Pastikan bahwa dokumen MoU/Quotation/PO dan Bukti Bayar
            dari client
            diperiksa dengan cermat:</p>

        @forelse ($training->files as $file)
            {{-- FILE APPROVAL --}}
            @if ($file->file_approval)
                <div class="flex mt-4">
                    <img src="{{ asset('img/icon_pdf_mou.png') }}" alt="PDF Icon" width="50"
                        style="margin-right: 15px;">
                    <div>
                        <div><strong>File MOU/Quoatation/PO:</strong> {{ basename($file->file_approval) }}</div>
                        <a href="{{ asset('storage/' . $file->file_approval) }}" target="_blank"
                            class="w-full h-20 p-1 bg-red-500 rounded mt-2 text-[10px] text-white">
                            Download
                        </a>
                    </div>
                </div>
            @endif

            {{-- PROOF OF PAYMENT --}}
            @if ($file->proof_payment)
                <div class="flex mt-4">
                    <img src="{{ asset('img/icon_pdf_mou.png') }}" alt="PDF Icon" width="50"
                        style="margin-right: 15px;">
                    <div>
                        <div><strong>Bukti Bayar:</strong> {{ basename($file->proof_payment) }}</div>
                        <a href="{{ asset('storage/' . $file->proof_payment) }}" target="_blank"
                            class="w-full h-20 p-1 bg-blue-500 rounded mt-2 text-[10px] text-white">
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
                    Saya memastikan bahwa seluruh dokumen yang masuk telah lengkap, valid, dan siap untuk diproses lebih
                    lanjut
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
                    $isFinish = $training->isfinish;

                    // Default progress map
                    $progressMap = [
                        1 => ['percent' => 10, 'color' => 'bg-red-600'],
                        2 => ['percent' => 30, 'color' => 'bg-orange-500'],
                        3 => ['percent' => 50, 'color' => 'bg-yellow-400'],
                        4 => ['percent' => 75, 'color' => 'bg-lime-600'],
                        5 => ['percent' => 100, 'color' => 'bg-green-600'],
                    ];

                    // Override jika pelatihan ditolak (isfinish = 2)
                    if ($isFinish == 2) {
                        $progress = ['percent' => 100, 'color' => 'bg-red-600'];
                    } else {
                        $progress = $progressMap[$progressValue] ?? ['percent' => 0, 'color' => 'bg-gray-400'];
                    }
                @endphp

                <div class="text-center">
                    @if ($isFinish == 2)
                        <p class="text-sm text-red-600 font-semibold mb-1">Pelatihan Ditolak</p>
                    @endif

                    <div class="w-[100px] h-2 bg-gray-200 rounded-full dark:bg-gray-700 mx-auto">
                        <div class="{{ $progress['color'] }} text-[8px] font-medium text-white text-center leading-none rounded-full"
                            style="width: {{ $progress['percent'] }}%; height:8px">
                            {{ $progress['percent'] }}%
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>

    @push('scripts')
        @vite('resources/js/edittrainingadmin.js')
    @endpush
@endsection
