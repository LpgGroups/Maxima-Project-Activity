@extends('dashboard.layouts.dashboardmain')
@section('container')
    @php
        $activityMap = [
            'TKPK1' => 'Pelatihan Tenaga Kerja Pada Ketinggian Tingkat 1',
            'TKPK2' => 'Pelatihan Tenaga Kerja Pada Ketinggian Tingkat 2',
            'TKBT1' => 'Pelatihan Tenaga Kerja Bangunan Tinggi 1',
            'TKBT2' => 'Pelatihan Tenaga Kerja Bangunan Tinggi 2',
            'BE' => 'Pelatihan Basic Electrical',
            'P3K' => 'Pelatihan Pertolongan Pertama Pada Kecelakaan (P3K)',
            'AK3U' => 'Pelatihan Ahli K3 Umum (AK3U) ',
        ];
    @endphp

    <p class="text-center text-lg font-semibold text-[#9694FF]">
        {{ $activityMap[$training->activity] ?? $training->activity }}
    </p>

    <div class="container mx-auto ">
        <!-- Tab Navigation -->
        <div class="rounded-lg">
            <ul class="flex border-b border-gray-300">
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
                        class="block text-gray-600  py-2 px-4 bg-gray-400 rounded-tr-lg text-center">Submit
                        Data</a>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div id="content1" class="tab-content">
            <div class="p-4 border border-t-0 border-gray-300 bg-white">
                <h3 class="text-[24px] font-semibold">Daftar Pelatihan</h3>
                <p class="text-[15px]">Lengkapi data form ini untuk mengikuti pelatihan yang akan diselenggarakan oleh PT
                    Maxima Aksara Jaya Utama, pastikan Anda mengisi form aplikasi pendaftaran dengan benar dan sesuai data
                    yang valid.</p>

                <form id="registrationForm" action="{{ route('dashboard.form.save') }}" method="POST">
                    @csrf
                    <input type="" id="trainingId" name="id" value="{{ old('id', $training->id ?? '') }}">
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

                    <p class="mt-4 text-[24px] font-semibold">Informasi Kegiatan</p>
                    <div class="mt-4">
                        <!-- Tampilkan aktivitas yang dipilih -->
                        <p class="font-bold">Jenis Kegiatan</p>
                        <p id="activity">{{ $training->activity }}</p>
                    </div>


                    <div class="mt-4">
                        <p class="font-bold">Tanggal Pelatihan</p>
                        <p id="date">{{ \Carbon\Carbon::parse($training->date)->format('d-F-Y') }}</p>
                    </div>

                    <div class="flex">
                        <div id="loadingSpinner" class="hidden ml-2">
                            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </div>
                        <div id="responseMessage" class="mt-4 hidden"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4 flex">
                        <button type="button" id="submitBtn"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                            Simpan Data
                        </button>
                        @if ($training->updated_at)
                            <div class="ml-2 text-green-400">
                                <strong>Update Terakhir:</strong><br>
                                {{ $training->updated_at->setTimezone('Asia/Jakarta')->format('d-F-Y H:i') }} WIB
                            </div>
                        @else
                            <div class="ml-2 text-red-400">
                                <strong>Update Terakhir:</strong><br>
                                Belum pernah diupdate
                            </div>
                        @endif

                        <button type="button" id="nextBtnform1"
                            class="
                            {{ $training->isComplete() ? 'bg-green-500' : 'bg-gray-400' }}
                            text-white px-4 py-2 ml-[500px] rounded-md
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
                <h1 class="text-xl font-semibold">Submit Peserta</h1>
                <p>Lengkapi Data Peserta yang akan mengikuti pelatihan yang diselenggarakan oleh PT. Maxima Aksara Jaya
                    Utama</p>

                <input type="" id="form_id" value="{{ $training->id }}">

                <div class="flex gap-6 mt-4">
                    <!-- KIRI: Form Input Peserta -->
                    <div class="w-1/2">
                        <h2 class="text-lg font-medium mb-2">Input Nama Peserta</h2>
                        <div id="input-fields-container" class="max-h-80 overflow-y-auto space-y-2">
                            <div class="flex items-center space-x-2 mb-2">
                                <input type="text" class="form-control px-3 py-2 border border-gray-300 rounded-md"
                                    placeholder="Nama Peserta" />
                                <button
                                    class="text-lg font-bold text-blue-500 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-100"
                                    onclick="addInputField()">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: Preview Data yang Sudah Masuk (dari DB) -->
                    <div class="preview w-1/2 border border-gray-300 rounded-md p-4 bg-gray-50">
                        <h2 class="text-lg font-semibold mb-2">Peserta yang Sudah Tersimpan</h2>

                        @if (session('success'))
                            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <ul class="list-decimal list-inside space-y-1 text-sm text-gray-700 max-h-60 overflow-y-auto pr-2">
                            @forelse ($training->participants as $index => $participant)
                                <li
                                    class="flex items-center justify-between px-2 py-1 {{ $index % 2 === 0 ? 'bg-gray-200' : 'bg-[#fffef5]' }}">
                                    <span>{{ $index + 1 }}. {{ $participant->name }}</span>
                                    <form action="{{ route('dashboard.form2.destroy', $participant->id) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin menghapus peserta ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 text-xs">Hapus</button>
                                    </form>
                                </li>
                            @empty
                                <h1 class="text-center text-gray-500 w-full py-2">Data peserta belum ada</h1>
                            @endforelse
                        </ul>

                    </div>


                </div>

                <!-- Link GDrive -->
                <div class="border-b-2 border-[#CAC9FF] mt-4"></div>
                <div class="container">
                    <p class="mt-2">
                        Upload data peserta melalui Google Drive dengan download template yang telah Kami sediakan, lalu
                        copy link Google Drive untuk dipaste ke dalam box di bawah ini.
                    </p>

                    <div class="relative mt-4 w-64">
                        <input id="link" name="link" type="text"
                            class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                            placeholder="" required value="{{ old('link', $training->link ?? '') }}" />
                        <label for="link"
                            class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                            Link Persyaratan
                        </label>
                    </div>

                    <div class="flex">
                        <div class="mt-4">
                            <button type="button" id="submitBtnForm2"
                                class="w-48 bg-blue-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                                Simpan Data
                            </button>
                        </div>
                        <div class="mt-4 flex ml-[500px] gap-x-2">
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
                        <p class="text-[12px]">Pastikan data dibawah ini sesuai dengan informasi Anda</p>

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
                                <div>: {{ \Carbon\Carbon::parse($training->date)->format('d-F-Y') }}</div>

                                <div class="font-semibold">Tempat Kegiatan</div>
                                <div>: {{ $training->place }}</div>

                                <div class="font-semibold">Jumlah Peserta</div>
                                <div>: {{ $training->participants->count() }}</div>
                            </div>
                        </div>

                        <hr class="h-px mt-2 bg-gray-200 border-0 dark:bg-gray-700 w-[600px]">

                        <!-- Upload Persetujuan -->
                        <div class="border border-gray-300 w-[600px] mt-4 rounded-lg p-2">
                            <h3 class="text-[24px] font-semibold">Upload Persetujuan</h3>
                            <p class="text-[12px]">PIC diharapkan untuk mengupload kembali berkas MoU dan Quotation yang
                                telah disetujui dan ditandatangani oleh PIC.</p>

                            <form id="form3" enctype="multipart/form-data">
                                @csrf
                                <input type="" name="file_id" value="{{ $training->id }}">

                                <div class="mt-2">
                                    <label class="block mb-2 mt-2 text-sm font-medium text-gray-900" for="file_mou">Upload
                                        file MoU</label>
                                    <input name="file_mou" id="file_mou" type="file" accept="application/pdf"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                    <p class="mt-1 text-sm text-gray-500">Format File: PDF (Maks Size: 2MB).</p>
                                </div>

                                <div class="mt-2">
                                    <label class="block mb-2 text-sm font-medium text-gray-900"
                                        for="file_quotation">Upload file Quotation</label>
                                    <input name="file_quotation" id="file_quotation" type="file"
                                        accept="application/pdf"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                    <p class="mt-1 text-sm text-gray-500">Format File: PDF (Maks Size: 2MB).</p>
                                </div>
                            </form>
                        </div>

                        <hr class="h-px mt-2 bg-gray-200 border-0 w-[600px]">

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
                                        @forelse ($training->participants as $index => $participant)
                                            <tr>
                                                <td class="w-[10px]">{{ $index + 1 }}</td>
                                                <td class="w-[100px]">{{ $participant->name }}</td>
                                                <td class="w-[80px]">
                                                    {{ $participant->isprogress ? 'Aktif' : 'Belum Aktif' }}</td>
                                                <td class="w-[200px]">{{ $participant->reason }}</td>
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
                <div class="w-70 h-50 rounded-[20px] text-red-600 border-2 border-red-600 p-2">
                    <h3 class="text-xl font-bold mb-2">Informasi Data</h3>
                    <ul class="list-disc text-xs ml-2">
                        <li>Harap lengkapi data-data peserta H-3 sebelum hari pelatihan di mulai.</li>
                        <li>Data dapat di ubah H-3 sebelum hari pelatihan.</li>
                        <li>Mohon untuk input data dengan baik dan benar</li>
                        <li>PIC diharapkan Mengupload kembali MoU dan Quotation yang telah ditanda tangan.</li>
                    </ul>
                </div>


                <div class="mt-60 flex justify-between">
                    <!-- Previous Button -->
                    <button type="button" id="prevBtnForm3"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Previous
                    </button>

                    <!-- Next Button -->
                    <button type="button" id="submitBtnForm3"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Simpan Data
                    </button>
                </div>
            </aside>

        </div>

    </div>
@endsection
@push('scripts')
    @vite('resources/js/registertraining.js')
@endpush
