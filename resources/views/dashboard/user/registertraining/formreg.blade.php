@extends('dashboard.layouts.dashboardmain')
@section('container')

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full gap-2">
        <p class="text-center sm:text-left text-base sm:text-[15px] font-semibold text-[#9694FF]">
            {{ config('activity_map.' . $training->activity) ?? $training->activity }}
        </p>

        <p class="text-center sm:text-right text-sm sm:text-base text-gray-600">
            No: {{ $training->no_letter }}
        </p>
    </div>

    <div class="container mx-auto">
        <!-- Tab Navigation -->
        <div class="rounded-lg">
            <ul class="flex flex-col sm:flex-row border-b border-gray-300">
                <li class="flex-1">
                    <a href="#" id="tab1"
                        class="flex justify-center items-center gap-2 text-violet-400 py-2 px-4 bg-gray-400 rounded-tl-lg text-center">
                        Daftar Pelatihan
                        @if ($training->isComplete())
                            <img src="{{ asset('img/svg/success.svg') }}" alt="Success" class="w-4 h-4">
                        @endif
                    </a>

                </li>
                <li class="flex-1">
                    <a href="#" id="tab2"
                        class="flex justify-center items-center gap-2 text-violet-400 py-2 px-4 bg-gray-400 text-center">
                        Pendaftaran Peserta

                        @if ($training->isLinkFilled())
                            <img src="{{ asset('img/svg/success.svg') }}" alt="Success" class="w-4 h-4">
                        @endif
                    </a>
                </li>
                <li class="flex-1">
                    <a href="#" id="tab3"
                        class="flex justify-center items-center gap-2 text-violet-400 py-2 px-4 bg-gray-400 text-center rounded-r-lg">Submit
                        Data
                        @if ($training->isfinish == 1)
                            <img src="{{ asset('img/svg/success.svg') }}" alt="Success" class="w-4 h-4">
                        @endif
                    </a>

                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div id="content1" class="tab-content">
            <div class="p-4 border border-t-0 border-gray-300 bg-white">
                <div class="relative flex items-start space-x-2">
                    <h3 class="text-[24px] font-semibold">Daftar Pelatihan</h3>

                    <!-- Icon + Tooltip -->
                    <div class="relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 mt-1 cursor-pointer"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.313-1.17 1.29-2 2.522-2 1.54 0 2.75 1.21 2.75 2.75 0 1.052-.597 1.976-1.469 2.406a2.25 2.25 0 00-1.281 2.156m0 3h.008M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                        </svg>

                        <!-- Tooltip container -->
                        <div class="absolute top-0 left-full ml-2 w-[500px] hidden group-hover:flex z-50">
                            <video src="/img/vid/tutorreg1.webm" autoplay muted loop
                                class="w-[500px] h-auto rounded shadow-lg" playsinline>
                                Browser anda tidak mendukung video tag.
                            </video>
                        </div>

                    </div>
                </div>


                <p class="text-[15px]">Lengkapi data form ini untuk mengikuti pelatihan yang akan diselenggarakan oleh PT
                    Maxima Aksara Jaya Utama, pastikan Anda mengisi form aplikasi pendaftaran dengan benar dan sesuai data
                    yang valid.</p>

                <form id="registrationForm" action="{{ route('dashboard.form.save') }}" method="POST">
                    @csrf
                    <input type="hidden" id="trainingId" name="id" value="{{ old('id', $training->id ?? '') }}">
                    <div class="flex gap-x-4">
                        <!-- Nama PIC Perusahaan -->
                        <div class="relative mt-4 w-64">
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
                                placeholder="" required
                                value="{{ old('email_pic', $training->email_pic ?? Auth::user()->email) }}" />
                            <label for="email_pic"
                                class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                                Email PIC
                            </label>
                        </div>

                        <!-- No WhatsApp -->
                        <div class="relative mt-4 w-64">
                            <input id="phone_pic" name="phone_pic" type="tel"
                                pattern="^\+\d{1,3}\s\d{1,4}-\d{1,4}-\d{4}$"
                                class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                                placeholder="" required title="No WhatsApp harus berupa nomor telepon yang valid."
                                value="{{ old('phone_pic', $training->phone_pic ?? (Auth::user()->phone ?? '')) }}"
                                oninput="this.value = this.value.replace(/[^0-9+]/g, '')" />

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

                    <div class="mt-2 p-2 bg-white rounded-lg border">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Informasi Kegiatan</h2>

                        <!-- Jenis Kegiatan -->
                        <div class="mb-4">
                            <p class="text-gray-600 text-base font-bold">Jenis Kegiatan</p>
                            <p id="activity" class="text-[15px] text-gray-800 font-medium">
                                {{ config('activity_map.' . $training->activity) ?? $training->activity }}
                            </p>
                        </div>

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

                        @if (Str::lower($training->place) === 'blended' || 'On-Site')
                            <div class="mb-4">
                                <label for="city" class="block text-gray-600 text-base font-bold mb-1">Lokasi
                                    Pelatihan:</label>
                                <select name="city" id="city"
                                    class="w-[200px] p-2 border border-gray-300 rounded-lg text-gray-800">
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach ($cityOptions as $city)
                                        <option value="{{ $city }}"
                                            {{ $training->city === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif



                        <!-- Tanggal Pelatihan -->
                        <div class="mb-4">
                            <p class="text-gray-600 text-base font-bold">Tanggal Pelatihan</p>
                            <p id="date" class="text-[15px] text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($training->date)->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        <!-- Tanggal Selesai Pelatihan -->
                        <div class="mb-6">
                            <p class="text-gray-600 text-base font-bold">Tanggal Selesai Pelatihan</p>
                            <p id="date_end" class="text-[15px] text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($training->date_end)->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        <!-- Loading Spinner dan Pesan -->
                        <div class="flex items-center space-x-2">
                            <div id="loadingSpinner" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                            </div>
                            <div id="responseMessage" class="hidden text-sm text-blue-600"></div>
                        </div>
                    </div>


                    <!-- Submit Button -->
                    <div class="mt-4 flex flex-col lg:flex-row gap-2 w-full max-w-[1920px] p-4">
                        <!-- Tombol Simpan -->
                        <button type="button" id="submitBtn"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                            data-training-date="{{ $training->date }}">
                            Simpan Data
                        </button>

                        <!-- Info Update -->
                        @if ($training->updated_at)
                            <div class="text-green-400 text-sm">
                                <strong>Update Terakhir:</strong><br>
                                {{ $training->updated_at->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB

                            </div>
                        @else
                            <div class="text-red-400 text-sm">
                                <strong>Update Terakhir:</strong><br>
                                Belum pernah diupdate
                            </div>
                        @endif

                        <!-- Tombol Selanjutnya -->
                        <button type="button" id="nextBtnform1"
                            class="mt-2 lg:mt-0 lg:ml-auto
                                {{ $training->isComplete() ? 'bg-green-500' : 'bg-gray-400' }}
                         text-white px-4 py-2 rounded-md
                             focus:outline-none focus:ring-2
                                {{ $training->isComplete() ? 'focus:ring-green-400' : 'focus:ring-gray-400' }}
                                {{ $training->isComplete() ? '' : 'opacity-50 cursor-not-allowed' }}"
                            data-enabled="{{ $training->isComplete() ? 'true' : 'false' }}">
                            Selanjutnya
                        </button>
                    </div>


                </form>
            </div>
        </div>

        <div id="content2" class="tab-content hidden">
            <div class="p-4 border border-t-0 border-gray-300 bg-white">
                <div class="relative flex items-start space-x-2">
                    <h3 class="text-[24px] font-semibold">Submit Peserta</h3>

                    <!-- Icon + Tooltip -->
                    <div class="relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 mt-1 cursor-pointer"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.313-1.17 1.29-2 2.522-2 1.54 0 2.75 1.21 2.75 2.75 0 1.052-.597 1.976-1.469 2.406a2.25 2.25 0 00-1.281 2.156m0 3h.008M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                        </svg>

                        <!-- Tooltip container -->
                        <div class="absolute top-0 left-full ml-2 w-[500px] hidden group-hover:flex z-50">
                            <video src="/img/vid/tutorregparticipants.webm" autoplay muted loop
                                class="w-[500px] h-auto rounded shadow-lg" playsinline>
                                Browser anda tidak mendukung video tag.
                            </video>
                        </div>
                    </div>
                </div>
                <p class="mb-2">Lengkapi Data Peserta yang akan mengikuti pelatihan yang diselenggarakan oleh PT. Maxima
                    Aksara Jaya
                    Utama</p>

                <a href="{{ route('dashboard.addparticipant', ['form_id' => $training->id]) }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2 rounded-lg shadow-md transition duration-200 ease-in-out mt-4 mb-2">
                    Tambah Peserta
                </a>

                <div class="sm:w-full w-full h-auto bg-white rounded-2xl shadow-2xl p-4 sm:mb-0">
                    <div class="rounded-2xl w-full">
                        {{-- Scroll container --}}
                        <div class="max-h-[500px] overflow-y-auto">
                            <table class="table-auto w-full text-center align-middle border-separate border-spacing-y-1">
                                <thead>
                                    <tr class="bg-slate-600 lg:text-sm text-white text-[10px]">
                                        <th class="rounded-l-lg">No</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th class="rounded-r-lg">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="lg:text-[14px] text-[10px]">
                                    @forelse ($training->participants as $i => $participant)
                                        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-100' }}">
                                            <td class="py-2">{{ $i + 1 }}</td>
                                            <td class="max-w-[100px] truncate whitespace-nowrap py-2">
                                                {{ $participant->name }}</td>
                                            <td class="max-w-[100px] truncate whitespace-nowrap py-2">
                                                {{ $participant->nik }}</td>
                                            <td class="max-w-[100px] truncate whitespace-nowrap py-2">
                                                @php
                                                    $status = $participant->status;
                                                    $statusText = [
                                                        0 => 'Pending',
                                                        1 => 'Sukses',
                                                        2 => 'Ditolak',
                                                    ];
                                                    $statusClass = [
                                                        0 => 'bg-yellow-200 text-yellow-800',
                                                        1 => 'bg-green-200 text-green-800',
                                                        2 => 'bg-red-200 text-red-800',
                                                    ];
                                                @endphp
                                                <span
                                                    class="px-2 py-1 rounded text-sm font-medium {{ $statusClass[$status] ?? 'bg-gray-200 text-gray-800' }}">
                                                    {{ $statusText[$status] ?? 'Tidak Diketahui' }}
                                                </span>
                                            </td>
                                            <td class="py-2">{{ $participant->reason ?? '-' }}</td>
                                            <td class="py-2">
                                                <a href="{{ route('dashboard.addparticipant', ['form_id' => $trainingId]) }}?participant_id={{ $participant->id }}"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-gray-500">
                                                Data peserta belum tersedia. Silakan tambahkan peserta terlebih dahulu.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="flex">
                    <div class="flex justify-end gap-x-2 w-full items-end max-w-[1920px] p-4">
                        <!-- Tombol Previous -->
                        <button type="button" id="prevBtnForm2"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Sebelumnya
                        </button>

                        <!-- Tombol Next -->
                        <button type="button" id="nextBtnForm2"
                            class="{{ $training->isLinkFilled() ? 'bg-green-500 focus:ring-green-400' : 'bg-gray-400 focus:ring-gray-400' }}
                                       text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2
                                       {{ $training->isLinkFilled() ? '' : 'opacity-50 cursor-not-allowed' }}"
                            data-enabled="{{ $training->isLinkFilled() ? 'true' : 'false' }}">
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen overflow-y-auto">
        <!-- KONTEN UTAMA (scrollable) -->
        <div class="flex-1  ">
            <div id="content3" class="tab-content">
                <div class="p-4 border border-t-0 border-gray-300 bg-white">
                    <h3 class="text-[24px] font-semibold">Informasi Pendaftaran</h3>
                    <p class="text-[14px]">Pastikan data dibawah ini sesuai dengan informasi Anda</p>

                    <div id="inf_training" class="border rounded-lg w-[600px] mt-2 p-2 bg-white">
                        <div class="grid grid-cols-2 gap-y-2 text-[14px] leading-none">
                            <div class="font-semibold">Nama PIC</div>
                            <div>: {{ $training->name_pic }}</div>

                            <div class="font-semibold">Nama Perusahaan</div>
                            <div>: {{ $training->name_company }}</div>

                            <div class="font-semibold">Email PIC</div>
                            <div>: {{ $training->email_pic }}</div>

                            <div class="font-semibold">No WhatsApp</div>
                            <div>: {{ $training->phone_pic }}</div>

                            <div class="font-semibold">Kegiatan</div>
                            <div>: {{ $training->activity }}</div>

                            <div class="font-semibold">Tanggal Kegiatan</div>
                            <div>:
                                @php
                                    $start = \Carbon\Carbon::parse($training->date)->locale('id');
                                    $end = \Carbon\Carbon::parse($training->date_end)->locale('id');

                                    if ($start->year != $end->year) {
                                        // Beda tahun: tampilkan full untuk keduanya
                                        $date =
                                            $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
                                    } else {
                                        // Tahun sama
                                        if ($start->month == $end->month) {
                                            // Bulan sama → 12 - 15 Mei 2025
                                            $date =
                                                $start->translatedFormat('d F') .
                                                ' - ' .
                                                $end->translatedFormat('d F Y');
                                        } else {
                                            // Bulan beda → 30 Mei - 1 Juni 2025
                                            $date =
                                                $start->translatedFormat('d F') .
                                                ' - ' .
                                                $end->translatedFormat('d F Y');
                                        }
                                    }
                                @endphp
                                {{ $date }}
                            </div>

                            <div class="font-semibold">Tempat Kegiatan</div>
                            <div>: {{ $training->place }} @if (Str::lower($training->place) === 'blended' || ('On-Site' && $training->city))
                                    - {{ $training->city }}
                                @endif
                            </div>

                            <div class="font-semibold">Jumlah Peserta</div>
                            <div>: {{ $training->participants->count() }} Peserta</div>
                        </div>
                    </div>

                    <hr class="h-px mt-2 bg-gray-200 border-0 dark:bg-gray-700 w-[600px]">

                    <!-- Upload Persetujuan -->
                    <div class="border border-gray-300 w-[600px] mt-4 rounded-lg p-2">
                        <div class="relative flex items-start space-x-2">
                            <h3 class="text-[24px] font-semibold">Upload File Persetujuan</h3>

                            <!-- Icon + Tooltip -->
                            <div class="relative group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 mt-1 cursor-pointer"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.313-1.17 1.29-2 2.522-2 1.54 0 2.75 1.21 2.75 2.75 0 1.052-.597 1.976-1.469 2.406a2.25 2.25 0 00-1.281 2.156m0 3h.008M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                                </svg>

                                <!-- Tooltip container -->
                                <div class="absolute top-0 left-full ml-2 w-[500px] hidden group-hover:flex z-50">
                                    <video src="/img/vid/uploadfinish.webm" autoplay muted loop
                                        class="w-[500px] h-auto rounded shadow-lg" playsinline>
                                        Browser anda tidak mendukung video tag.
                                    </video>
                                </div>
                            </div>
                        </div>
                        <p class="text-[14px]">
                            PIC diharapkan untuk mengupload kembali berkas
                            <strong>MoU/Quotation/PO dan Bukti Pembayaran</strong>
                            yang telah disetujui dan ditandatangani oleh PIC.
                        </p>

                        <form id="form3" enctype="multipart/form-data">
                            @csrf
                            <input class="hidden" type="hidden" name="file_id" value="{{ $training->id }}">

                            <div class="mt-2 border  px-2 rounded">
                                <label class="block mb-2 mt-2 text-sm font-bold text-gray-900" for="file_approval">
                                    Upload File Mou/Quotation/PO
                                </label>
                                <input name="file_approval" id="file_approval" type="file" accept="application/pdf"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                <p class="mt-1 text-sm text-gray-500">Format File: PDF (Maks Size: 2MB).</p>

                                @if (!empty($fileRequirement?->file_approval))
                                    <p class="text-sm text-green-600">File sudah diupload:
                                        <strong>{{ basename($fileRequirement->file_approval) }}</strong>
                                    </p>
                                @endif
                            </div>

                            <div class="mt-2 border px-2 rounded">
                                <label class="block mb-2 mt-2 text-sm font-bold text-gray-900" for="proof_payment">
                                    Upload Bukti Pembayaran
                                </label>
                                <input name="proof_payment" id="proof_payment" type="file"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                <p class="mt-1 text-sm text-gray-500">Format File: PDF, JPG, JPEG, PNG (Maks Size: 2MB).
                                </p>

                                @if (!empty($fileRequirement?->proof_payment))
                                    <p class="text-sm text-green-600">Bukti sudah diupload:
                                        <strong>{{ basename($fileRequirement->proof_payment) }}</strong>
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <hr class="h-px mt-2 bg-gray-200 border-0 dark:bg-gray-700 w-[600px]">

                    <!-- Daftar Peserta -->
                    <div class="border border-gray-300 w-[600px] mt-4 rounded-lg p-2">
                        <h3 class="text-[24px] font-semibold">Daftar Peserta</h3>
                        <p class="text-[12px]">Berikut ini adalah data peserta yang telah di daftarkan. Peserta yang
                            telah memenuhi persyaratan memiliki tanda (✓) dan peserta yang belum melengkapi persyaratan
                            memiliki tanda (X)</p>

                        <div class="rounded-2xl p-2 w-full">
                            <table class="table-auto w-full text-center align-middle">
                                <thead>
                                    <tr class="bg-slate-600 text-white text-[8px] lg:text-sm">
                                        <th class="rounded-l-lg w-[10px]">No</th>
                                        <th class="w-[100px]">Peserta</th>
                                        <th class="w-[80px]">Status</th>
                                        <th class="rounded-r-lg w-[200px]">Informasi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[8px] lg:text-[12px]">
                                    @forelse ($training->participants->sortBy('name') as $index => $participant)
                                        <tr class="odd:bg-white even:bg-gray-100">
                                            <td class="w-[10px]">{{ $loop->iteration }}</td>
                                            <td class="w-[100px]">{{ $participant->name }}</td>

                                            @php
                                                $statusInfo = [
                                                    0 => ['file' => 'waiting.svg', 'label' => 'Waiting'],
                                                    1 => ['file' => 'success.svg', 'label' => 'Success'],
                                                    2 => ['file' => 'rejected.svg', 'label' => 'Rejected'],
                                                ];
                                                $info = $statusInfo[$participant->status] ?? [
                                                    'file' => 'unknown.svg',
                                                    'label' => 'Unknown',
                                                ];
                                            @endphp

                                            <td class="w-[80px] text-center relative group py-1">
                                                <img src="{{ asset('img/svg/' . $info['file']) }}"
                                                    alt="{{ $info['label'] }}" class="w-4 h-4 mx-auto cursor-pointer">
                                                <div
                                                    class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-10">
                                                    {{ $info['label'] }}
                                                </div>
                                            </td>

                                            <td class="w-[200px]">
                                                @php
                                                    $statusReasonMap = [
                                                        0 => 'Dalam Pemeriksaan',
                                                        1 => 'Berhasil Terverifikasi',
                                                    ];

                                                    $rawReason = $participant->reason;

                                                    if (!empty($rawReason)) {
                                                        $displayReason = e($rawReason);
                                                    } else {
                                                        $statusBasedReason =
                                                            $statusReasonMap[$participant->status] ??
                                                            'tidak ada catatan';
                                                        $displayReason =
                                                            '<span class="text-gray-400 italic">' .
                                                            e($statusBasedReason) .
                                                            '</span>';
                                                    }
                                                @endphp

                                                {!! $displayReason !!}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-2">Tidak ada peserta</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ASIDE -->
        <aside id="side-panel" class="w-[300px] sticky top-0 h-screen bg-gray-50 border-l border-gray-300 p-4 hidden">

            <div @class([
                'w-auto h-auto rounded-[20px] p-4 border-2',
            
                // kondisi khusus: isprogress 5 dan isfinish 2 -->merah
                'text-red-600 border-yellow-600' =>
                    $training->isprogress == 5 && $training->isfinish == 2,
            
                // kondisi lain
                'text-green-600 border-green-600' =>
                    $training->isprogress == 6 && $training->isfinish != 2,
                'text-yellow-600 border-yellow-600' =>
                    $training->isprogress == 5 && $training->isfinish != 2,
            
                // default merah kalau bukan progress 5 atau 6
                'text-red-500 border-red-500' =>
                    $training->isprogress != 6 && $training->isprogress != 5,
            ])>

                @if ($training->isfinish == 1)
                    <h3 class="text-xl font-bold mb-2 text-green-600">Halo Sobat Maxima,</h3>
                    <p class="text-[10px] text-gray-700 text-justify">
                        Kami mengucapkan terima kasih atas kerja sama dan perhatian Bapak/Ibu dalam melengkapi
                        berkas-berkas persyaratan pengajuan pelatihan dengan baik dan tepat waktu. Berkat kelengkapan
                        dokumen tersebut, proses pengajuan pelatihan kini dapat kami lanjutkan ke tahap berikutnya.

                        <br><br>
                        Apabila terdapat pertanyaan atau informasi tambahan yang diperlukan, jangan sungkan untuk
                        menghubungi kami.
                        <br><br>
                        Terima kasih atas perhatian dan kerja sama yang luar biasa.
                        <br><br>
                        Hormat kami,
                        <br>
                        Maxima Group
                    </p>
                @elseif ($training->isprogress == 5)
                    <h3 class="text-xl font-bold mb-2 text-yellow-600">Menunggu Persetujuan</h3>
                    <p class="text-[10px] text-gray-700 text-justify">
                        Semua data dan dokumen pengajuan pelatihan sudah dikirimkan.
                        Silakan menunggu proses verifikasi dan persetujuan dari tim Maxima.
                        <br><br>
                        Anda akan mendapatkan notifikasi apabila proses sudah selesai.
                        <br><br>
                        Jika ada pertanyaan tambahan, silakan hubungi kami.
                    </p>
                @else
                    <h3 class="text-xl font-bold mb-2 text-red-600">Informasi Data</h3>
                    <ul class="list-disc text-xs ml-2 text-red-600">
                        <li>Harap lengkapi data-data peserta H-3 sebelum hari pelatihan di mulai.</li>
                        <li>Data dapat di ubah H-3 sebelum hari pelatihan.</li>
                        <li>Mohon untuk input data dengan baik dan benar</li>
                        <li>PIC diharapkan Mengupload kembali MoU/Quotation/PO yang telah ditanda tangan.</li>
                    </ul>
                @endif
            </div>

            <div class="text-center">
                @if ($training->isfinish == 1)
                    <img class="mt-4 w-[250px] h-[150px] mx-auto" src="/img/complete.png" alt="LPG">
                @endif
            </div>

            <div class="mt-4 flex gap-x-1">
                <!-- Previous Button -->
                <button type="button" id="prevBtnForm3"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Sebelumnya
                </button>

                <button type="button" id="submitBtnForm3"
                    class="bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-400"
                    data-training-date="{{ $training->date }}" disabled>
                    Simpan Data
                </button>
            </div>
        </aside>


    </div>

    </div>
@endsection
<script>
    const maxTab = @json(session('maxTab'));
    // showTabs(maxTab);
    document.addEventListener("DOMContentLoaded", function() {
        const maxTab = @json($maxTab);
        const defaultTab = 1;

        // Ambil tab terakhir dari localStorage (opsional)
        let lastTab = localStorage.getItem("lastTab");
        let targetTab = parseInt(lastTab) || defaultTab;

        // Batasi agar tidak melewati maxTab
        if (targetTab > maxTab) {
            targetTab = maxTab;
        }


        document.querySelectorAll("ul li a").forEach(function(tabEl, idx) {
            tabEl.addEventListener("click", function() {
                const tabNum = idx + 1;
                if (tabNum <= maxTab) {
                    localStorage.setItem("lastTab", tabNum);
                    showTab(tabNum);
                }
            });
        });
    });
</script>
@push('scripts')
    @vite('resources/js/registertraining.js')
@endpush
