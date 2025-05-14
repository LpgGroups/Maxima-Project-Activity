@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="p-4 border border-t-0 border-gray-300 bg-white rounded-lg mt-6">
        <h3 class="text-[24px] font-semibold">Daftar Pelatihan</h3>
        <p class="text-[15px]">Lengkapi data form ini untuk mengikuti pelatihan yang akan diselenggarakan oleh PT
            Maxima Aksara Jaya Utama, pastikan Anda mengisi form aplikasi pendaftaran dengan benar dan sesuai data
            yang valid.</p>
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
                    <input id="activity" name="activity" type="activity"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="" required value="{{ old('activity', $training->activity ?? '') }}" />
                    <label for="activity"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
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
                </div>
            </div>
            <label class="block mt-2 mb-2 text-sm font-medium text-gray-900">Tempat Pelatihan:</label>
            <div class="flex min-h-5">
                <div class="flex flex-wrap items-center justify-center border border-gray-300 rounded-lg h-12 w-[200px]">
                    <div class="flex me-4 ">
                        <input id="red-radio" type="radio" value="Online" name="colored-radio"
                            class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ $training->place == 'Online' ? 'checked' : '' }}>
                        <label for="red-radio"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Online</label>
                    </div>
                    <div class="flex items-center me-4">
                        <input id="green-radio" type="radio" value="Offline" name="colored-radio"
                            class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            {{ $training->place == 'Offline' ? 'checked' : '' }}>
                        <label for="green-radio"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Offline</label>
                    </div>
                </div>
            </div>
            <button id="submitBtn" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Update Data
            </button>
        </form>
    </div>

    @push('scripts')
        @vite('resources/js/edittrainingadmin.js')
    @endpush
@endsection
