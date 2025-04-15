@extends('dashboard.layouts.dashboardmain')

@section('container')
    <p>hellok</p>
    <div class="container mx-auto ">
        <!-- Tab Navigation -->
        <div class="rounded-lg">
            <ul class="flex border-b border-gray-300">
                <li class="flex-1">
                    <a href="#" id="tab1"
                        class="block text-violet-400 hover:text-blue-800 py-2 px-4 bg-gray-400 rounded-tl-lg text-center">Daftar
                        Pelatihan</a>
                </li>
                <li class="flex-1">
                    <a href="#" id="tab2"
                        class="block text-gray-600 hover:text-blue-800 py-2 px-4 bg-gray-400 text-center">Pendaftaran
                        Peserta</a>
                </li>
                <li class="flex-1">
                    <a href="#" id="tab3"
                        class="block text-gray-600 hover:text-blue-800 py-2 px-4 bg-gray-400 rounded-tr-lg text-center">Submit
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

                    {{-- Checkbox Daftar Kegiatan --}}
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
                    <div id="responseMessage" class="mt-4 hidden"></div>
                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="button" id="submitBtn"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                            Daftar
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
                    <div class="w-1/2 border border-gray-300 rounded-md p-4 bg-gray-50">
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
                            placeholder="" required />
                        <label for="link"
                            class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                            Link Persyaratan
                        </label>
                    </div>

                    <div class="mt-4">
                        <button type="button" id="submitBtnForm2"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                            Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <div id="content3" class="tab-content hidden">
            <div class="p-4 border border-t-0 border-gray-300 bg-white">
                <h3 class="text-xl font-semibold">Informasi Pendaftaran</h3>
                <p>Pastikan data dibawah ini sesuai dengan informasi Anda</p>

                <div id="inf_training" class="border w-[600px] mt-4">test
                    <p>Nama
                        Pic&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $training->name_pic }}</p>
                    <p>Nama
                        Perusahaan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $training->name_company }}</p>
                    <p>Email Pic
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $training->email_pic }}</p>
                    <p>No WhatsApp
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $training->phone_pic }}</p>
                    <p>Kegiatan
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $training->activity }}</p>
                    <p>Tanggal Kegiatan
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ \Carbon\Carbon::parse($training->date)->format('d-F-Y') }}</p>
                    <p>Tempat Kegiatan
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $training->place }}</p>
                    <p>Jumlah Peserta
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                        {{ $training->participants->count() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/registertraining.js') }}"></script>
@endpush
