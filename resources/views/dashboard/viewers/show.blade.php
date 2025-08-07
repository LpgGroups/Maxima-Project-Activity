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
        <div class="max-w-5xl px-2 py-6">
            <h1 class="text-[24px] font-semibold">Daftar Pelatihan</h1>
            <h2 class="text-[15px] mb-6">
                Lengkapi data form ini untuk mengikuti pelatihan yang akan diselenggarakan oleh PT Maxima Aksara Jaya Utama,
                pastikan Anda mengisi form aplikasi pendaftaran dengan benar dan sesuai data yang valid.
            </h2>
            <div class="flex flex-col md:flex-row gap-8 items-start w-full">
                <!-- FORM -->
                <form id="editFormbyAdmin" action="/dashboard/admin/training/{{ $training->id }}" method="POST"
                    data-training-id="{{ $training->id }}" class="flex-1">
                    @csrf

                    <!-- GRID INPUT UTAMA 2 KOLOM -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4 mb-2">
                        <div>
                            <!-- Nama PIC -->
                            <div class="relative mb-3">
                                <input id="name_pic" name="name_pic" type="text"
                                    class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                                    placeholder="" required pattern="[A-Za-z\s]+"
                                    title="Nama PIC hanya boleh berisi huruf dan spasi."
                                    value="{{ old('name_pic', $training->name_pic ?? '') }}" />
                                <label for="name_pic"
                                    class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                                    Nama PIC
                                </label>
                            </div>
                            <!-- Nama Perusahaan -->
                            <div class="relative mb-3">
                                <input id="name_company" name="name_company" type="text"
                                    class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                                    placeholder="" required
                                    title="Nama Perusahaan hanya boleh berisi huruf, angka, dan spasi."
                                    value="{{ old('name_company', $training->name_company ?? '') }}" />
                                <label for="name_company"
                                    class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                                    Nama Perusahaan
                                </label>
                            </div>
                        </div>
                        <div>
                            <!-- Email PIC -->
                            <div class="relative mb-3">
                                <input id="email_pic" name="email_pic" type="email"
                                    class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                                    placeholder="" required value="{{ old('email_pic', $training->email_pic ?? '') }}" />
                                <label for="email_pic"
                                    class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                                    Email PIC
                                </label>
                            </div>
                            <!-- No WhatsApp -->
                            <div class="relative mb-3">
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
                    </div>
                    <!-- END GRID INPUT UTAMA -->

                    <!-- BARIS: KEGIATAN & TANGGAL -->
                    <div class="flex flex-wrap gap-x-4 gap-y-2 mb-3">
                        <div class="relative mt-2 w-64">
                            <select id="activity" name="activity"
                                class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E]"
                                required>
                                <option value="" disabled selected hidden></option>
                                <option value="TKPK1"
                                    {{ old('activity', $training->activity ?? '') == 'TKPK1' ? 'selected' : '' }}>TKPK1
                                </option>
                                <option value="TKPK2"
                                    {{ old('activity', $training->activity ?? '') == 'TKPK2' ? 'selected' : '' }}>TKPK2
                                </option>
                                <option value="TKBT1"
                                    {{ old('activity', $training->activity ?? '') == 'TKBT1' ? 'selected' : '' }}>TKBT1
                                </option>
                                <option value="TKBT2"
                                    {{ old('activity', $training->activity ?? '') == 'TKBT2' ? 'selected' : '' }}>TKBT2
                                </option>
                                <option value="BE"
                                    {{ old('activity', $training->activity ?? '') == 'BE' ? 'selected' : '' }}>BE</option>
                                <option value="P3K"
                                    {{ old('activity', $training->activity ?? '') == 'P3K' ? 'selected' : '' }}>P3K
                                </option>
                                <option value="AK3U"
                                    {{ old('activity', $training->activity ?? '') == 'AK3U' ? 'selected' : '' }}>AK3U
                                </option>
                            </select>
                            <label for="activity"
                                class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform scale-75 -translate-y-4 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                                Kegiatan
                            </label>
                        </div>
                        <div class="relative mt-2 w-64">
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
                                placeholder="" required />
                            <label for="date"
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
                    <!-- END BARIS KEGIATAN & TANGGAL -->

                    <!-- LOKASI KEGIATAN -->
                    <div class="mb-5">
                        <h3 class="text-sm font-semibold text-gray-600 mb-3">Lokasi Kegiatan</h3>
                        <div class="grid gap-4 sm:grid-cols-2 max-w-xl">
                            <div>
                                <label class="block text-sm text-gray-700 font-bold mb-1">
                                    Provinsi
                                </label>
                                <p class="text-gray-900 ">
                                    {{ $training->provience ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 font-bold mb-1">
                                    Kota
                                </label>
                                <p class="text-gray-900 ">
                                    {{ $training->city ?? '-' }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block mb-1  text-gray-700 text-sm font-bold">
                                    Alamat Lengkap
                                </label>
                                <p class="text-gray-900">
                                    {{ $training->address ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- END LOKASI -->

                    <label class="block mt-2 mb-2 text-sm font-medium text-gray-900">Tempat Pelatihan:</label>
                    <div class="flex min-h-5">
                        <div class="flex items-center justify-center gap-x-4 border border-gray-300 rounded-lg h-12 px-4">
                            <div class="flex items-center">
                                <input id="radio-online" type="radio" value="Online" name="colored-radio"
                                    class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500"
                                    {{ $training->place == 'Online' ? 'checked' : '' }}>
                                <label for="radio-online" class="ms-2 text-sm font-medium text-gray-900 ">Online</label>
                            </div>
                            <div class="flex items-center">
                                <input id="radio-offline" type="radio" value="On-Site" name="colored-radio"
                                    class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500"
                                    {{ $training->place == 'On-Site' ? 'checked' : '' }}>
                                <label for="radio-offline" class="ms-2 text-sm font-medium text-gray-900 ">On-Site</label>
                            </div>
                            <div class="flex items-center">
                                <input id="radio-blended" type="radio" value="Blended" name="colored-radio"
                                    class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500"
                                    {{ $training->place == 'Blended' ? 'checked' : '' }}>
                                <label for="radio-blended" class="ms-2 text-sm font-medium text-gray-900 ">Blended</label>
                            </div>
                        </div>
                    </div>
                </form>

                @if ($training->isfinish == 1)
                    <!-- CARD SUKSES -->
                    <div class="w-full md:w-[370px] mt-8 md:mt-0 md:sticky md:top-8">
                        <div
                            class="bg-green-50 border-2 border-green-400 rounded-2xl shadow-lg p-6 flex flex-col gap-3 items-center">
                            <img src="{{ asset('img/approvebym.webp') }}" alt="Approved" class="h-24 w-auto mb-2" />
                            <h3 class="text-lg font-bold text-green-700 text-center mb-1">Pelatihan Disetujui</h3>
                            <p class="text-sm text-green-800 text-center">
                                Selamat! Pengajuan pelatihan ini telah <span
                                    class="font-semibold text-green-700">disetujui</span>.<br>
                                oleh Management
                            </p>
                        </div>
                    </div>
                @elseif ($training->isfinish == 2)
                    <!-- CARD PENOLAKAN -->
                    <div class="w-full md:w-[370px] mt-8 md:mt-0 md:sticky md:top-8">
                        <div class="bg-red-50 border-2 border-red-400 rounded-2xl shadow-lg p-6 flex flex-col gap-2">
                            <div class="flex items-center gap-3 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 flex-shrink-0"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                                </svg>
                                <h3 class="text-lg font-bold text-red-600 mb-0">Pelatihan Ditolak</h3>
                            </div>
                            <p class="text-xs text-gray-700 mb-1">
                                Mohon maaf, pengajuan pelatihan ini <span class="font-semibold text-red-700">tidak dapat
                                    dilanjutkan</span>.
                            </p>
                            <div class="bg-red-100 border-l-4 border-red-400 p-3 rounded-md mt-2">
                                <span class="font-semibold text-red-600 block mb-1">Alasan penolakan:</span>
                                <span
                                    class="text-red-700 italic">{{ $training->reason_fail ?? 'Tidak ada alasan yang tersedia.' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="p-4 border border-t-0 border-gray-300 bg-white rounded-lg mt-4">
        <h1 class="text-[24px] font-semibold">Peserta Terdaftar</h1>
        <h2 class="text-[15px]">Sebelum menyimpan atau mengirim data pelatihan, pastikan hal-hal berikut telah diperiksa
            dan sesuai dengan data peserta:</h2>
        <div class="rounded-2xl p-2 w-full mt-4">
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
                                    <td class="max-w-[120px] truncate whitespace-nowrap"
                                        title="{{ $participant->name }}">
                                        {{ $participant->name }}</td>
                                    <td class="max-w-[100px]">
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
                                    </td>

                                </tr>
                                {{-- Dropdown row, hidden by default --}}
                                <tr class="detail-row hidden" id="detail-row-{{ $participant->id }}">
                                    <td colspan="8" class="bg-gray-100 text-left px-4 py-3">
                                        <div class="mb-2 font-semibold text-[15px]">Data Selengkapnya</div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-800">
                                            {{-- Tempat & Tanggal Lahir --}}
                                            <div
                                                class="p-4 border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-300">
                                                <span class="block text-gray-600 font-semibold mb-1">Tempat & Tanggal
                                                    Lahir</span>
                                                <div class="text-gray-900">
                                                    {{ $participant->birth_place }},
                                                    {{ $participant->date_birth ? \Carbon\Carbon::parse($participant->date_birth)->translatedFormat('d F Y') : '-' }}
                                                </div>
                                            </div>

                                            {{-- Golongan Darah --}}
                                            <div
                                                class="p-4 border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-300">
                                                <span class="block text-gray-600 font-semibold mb-1">Golongan Darah</span>
                                                <div class="text-gray-900">
                                                    {{ $participant->blood_type ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

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
                                <a href="{{ route('download.confidential', ['type' => 'file-nobatch', 'file' => basename($fileRequirement->file_nobatch)]) }}"
                                    class="underline" target="_blank">
                                    {{ basename($fileRequirement->file_nobatch) }}
                                </a>
                            </strong>
                        </p>
                    @endif
                </div>
            @endif
            <div class="">
                <label for="note" class="block mb-1 font-bold text-gray-700 text-sm">Catatan</label>
                <textarea name="note" id="note" maxlength="150"
                    class="w-full border border-gray-300 rounded p-2 min-h-[100px] focus:ring-2 focus:ring-blue-500 text-left"
                    rows="3" placeholder="Masukkan Note untuk management (opsional)">{{ old('note', $fileRequirement->note ?? '') }}</textarea>

            </div>
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


            <!-- Tombol dan Progress Bar di satu baris -->
            <div class="flex items-center gap-4">
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

    <div id="report-activity" class="p-4 border border-t-0 border-gray-300 bg-white rounded-lg mt-4">
        @if ($training->isfinish === 1)
            <div class="p-4 border border-t-0 border-gray-300 bg-white rounded-lg mt-4" id="link-section">
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <h2 class="text-2xl font-bold mb-2 text-gray-700">Laporan Kegiatan</h2>
                <p class="text-[15px]">
                    Silakan unggah laporan kegiatan setelah pelatihan selesai. Laporan ini akan menjadi bagian dari
                    dokumentasi
                    dan evaluasi internal.
                </p>
                <form action="{{ route('training.update-link.viewer', $training->id) }}" method="POST">
                    @csrf

                    <div class="relative mt-4 w-64">
                        <input id="link" name="link" type="url"
                            class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                            placeholder=" " required value="{{ old('link', $training->link ?? '') }}" />
                        <label for="link"
                            class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                            Laporan Kegiatan
                        </label>
                    </div>

                    <button type="submit" class="mt-4 rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700">
                        Simpan Link
                    </button>
                </form>
            </div>
        @endif

    </div>
    <script>
        function initShowDetailParticipant() {
            // Reset semua dropdown detail row
            $(".showDetailBtn")
                .off("click")
                .on("click", function() {
                    const id = $(this).data("id");
                    const detailRow = $("#detail-row-" + id);

                    // Hide semua detail-row kecuali yang diklik
                    $(".detail-row").not(detailRow).addClass("hidden");
                    detailRow.toggleClass("hidden");
                });
        }

        $(document).ready(function() {
            initShowDetailParticipant();
        });
    </script>
@endsection
