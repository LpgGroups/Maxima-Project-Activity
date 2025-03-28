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

            <form action="" method="POST">
                @csrf
                <div class="flex gap-x-4">
                    <!-- Nama PIC Perusahaan -->
                    <div class="relative mt-4 w-64">
                        <input id="name_pic" name="name_pic" type="text"
                            class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                            placeholder="" required pattern="[A-Za-z\s]+"
                            title="Nama PIC hanya boleh berisi huruf dan spasi." />
                        <label for="name_pic"
                            class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                            Nama PIC
                        </label>
                    </div>

                    <!-- Nama Perusahaan -->
                    <div class="relative mt-4 w-64">
                        <input id="name_company" name="name_company" type="text"
                            class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                            placeholder="" required
                            title="Nama Perusahaan hanya boleh berisi huruf, angka, dan spasi." />
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
                            placeholder="" required />
                        <label for="email_pic"
                            class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                            Email PIC
                        </label>
                    </div>

                    <!-- No WhatsApp -->
                    <div class="relative mt-4 w-64">
                        <input id="phone" name="phone" type="text"
                            class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                            placeholder="" required pattern="^\+?[1-9]\d{1,14}$"
                            title="No WhatsApp harus berupa nomor telepon yang valid." />
                        <label for="phone"
                            class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                            No WhatsApp
                        </label>
                    </div>
                </div>

                {{-- Checkbox Daftar Kegiatan --}}
                <p class="mt-4 text-[24px] font-semibold">Informasi Kegiatan</p>
                <div class="mt-4">
                    <!-- Tampilkan aktivitas yang dipilih -->
                    <p class="font-bold">Jenis Kegiatan</p>
                    <p>{{ $training->activity }}</p>
                </div>


                <div class="mt-4">
                    <p class="font-bold">Tanggal Pelatihan</p>
                    <p>{{ \Carbon\Carbon::parse($training->date)->format('d-F-Y') }}</p>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>



    <div id="content2" class="tab-content hidden">
        <div class="p-4 border border-t-0 border-gray-300 bg-white">
            <h3 class="text-xl font-semibold">Content for Tab 2</h3>
            <p>This is the content for tab 2.</p>
        </div>
    </div>

    <div id="content3" class="tab-content hidden">
        <div class="p-4 border border-t-0 border-gray-300 bg-white">
            <h3 class="text-xl font-semibold">Content for Tab 3</h3>
            <p>This is the content for tab 3.</p>
        </div>
    </div>
</div>
    <!-- Script to switch between tabs -->
    <script>
        // Initial call to showTab with tabIndex 1 to display the first tab content by default
        showTab(1);

        document.getElementById('tab1').addEventListener('click', function() {
            showTab(1);
        });
        document.getElementById('tab2').addEventListener('click', function() {
            showTab(2);
        });
        document.getElementById('tab3').addEventListener('click', function() {
            showTab(3);
        });

        function showTab(tabIndex) {
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(function(content) {
                content.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('ul li a').forEach(function(tab) {
                tab.classList.remove('text-violet-400', 'border-b-2', 'border-blue-600', 'bg-white');
                tab.classList.add('text-gray-600');
            });

            // Show the selected tab's content
            document.getElementById('content' + tabIndex).classList.remove('hidden');

            // Highlight the active tab
            const activeTab = document.getElementById('tab' + tabIndex);
            activeTab.classList.add('text-violet-400', 'border-b-2', 'border-blue-600', 'bg-white');
            activeTab.classList.remove('text-gray-600');
        }
    </script>
@endsection
