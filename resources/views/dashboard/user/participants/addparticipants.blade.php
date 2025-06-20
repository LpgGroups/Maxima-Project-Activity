@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex space-x-4">
        {{-- FORM (KIRI) --}}
        <div class="max-w-[500px] mt-4 bg-white rounded-3xl shadow-lg p-8">
            <button type="button" onclick="resetForm()"
                class="mb-3 px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs font-medium">
                reset form
            </button>
            <h2 id="form-title"
                class="text-2xl font-bold mb-4 text-center text-blue-700 flex items-center justify-center gap-2">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                </svg>
                Pendaftaran Peserta
            </h2>

            <p class="mb-6 text-gray-600 text-center text-sm">
                Silakan isi data peserta dengan benar. Data Anda aman dan hanya digunakan untuk keperluan pendaftaran.
            </p>

            @if (session('success'))
                <div class="bg-green-50 text-green-700 px-4 py-3 rounded mb-4 text-center">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="pl-4 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="form2" method="POST"
                action="{{ route('dashboard.addparticipant.save', ['form_id' => $form_id]) }}" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                <input type="hidden" name="form_id" value="{{ $form_id }}">
                <input type="hidden" name="participant_id" id="participant_id">

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block mb-1 font-medium text-gray-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-4 py-2 text-base"
                        placeholder="Contoh: Budi Santoso" />
                </div>

                {{-- NIK --}}
                <div>
                    <label for="nik" class="block mb-1 font-medium text-gray-700">
                        NIK
                    </label>
                    <input type="text" name="nik" id="nik"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-4 py-2 text-base"
                        placeholder="Nomor Induk Kependudukan" />
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label for="date_birth" class="block mb-1 font-medium text-gray-700">
                        Tanggal Lahir
                    </label>
                    <input type="date" name="date_birth" id="date_birth"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-4 py-2 text-base"
                        placeholder="Tanggal Lahir" />
                </div>

                {{-- FOTO --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="photo">Foto</label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="photo" id="photo" accept="image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <div class="file-info-photo mt-1 text-xs text-green-700"></div>
                </div>

                {{-- IJAZAH --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="ijazah">Ijazah (PDF/JPG/PNG)</label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="ijazah" id="ijazah" accept="application/pdf,image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <div class="file-info-ijazah mt-1 text-xs text-green-700"></div>
                </div>

                {{-- SURAT KARYAWAN --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="letter_employee">Surat Karyawan
                        (PDF/JPG/PNG)</label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="letter_employee" id="letter_employee" accept="application/pdf,image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <div class="file-info-letter_employee mt-1 text-xs text-green-700"></div>
                </div>

                {{-- SURAT SEHAT --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="letter_health">Surat Sehat
                        (PDF/JPG/PNG)</label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="letter_health" id="letter_health" accept="application/pdf,image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <div class="file-info-letter_health mt-1 text-xs text-green-700"></div>
                </div>

                {{-- CV --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="cv">CV (PDF/DOC/DOCX)</label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="cv" id="cv" accept="application/pdf,.doc,.docx"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <div class="file-info-cv mt-1 text-xs text-green-700"></div>
                </div>

                <div>
                    <button type="submit" id="form-submit-btn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition-colors duration-200">
                        Kirim
                    </button>
                </div>
            </form>
        </div>

        {{-- TABEL (KANAN) --}}
        <div class="w-[500px] mt-4 bg-white rounded-3xl shadow-lg p-8">
            <table class="table-auto w-full text-center align-middle">
                <thead>
                    <tr class="bg-slate-600 text-white text-sm">
                        <th class="rounded-l-lg">No</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th class="rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="space-y-2">
                    @forelse ($participants as $i => $participant)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-100' }}">
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $participant->name }}</td>
                            <td>
                                @if ($participant->status == 0)
                                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                                @elseif($participant->status == 1)
                                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-xs">Sukses</span>
                                @elseif($participant->status == 2)
                                    <span class="bg-red-200 text-red-800 px-2 py-1 rounded text-xs">Ditolak</span>
                                @else
                                    <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded text-xs">Lainnya</span>
                                @endif
                            </td>
                            <td>
                                <button type="button"
                                    class="edit-btn bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs"
                                    data-id="{{ $participant->id }}" data-name="{{ $participant->name }}"
                                    data-nik="{{ $participant->nik }}" data-date_birth="{{ $participant->date_birth }}"
                                    data-photo="{{ $participant->photo }}" data-ijazah="{{ $participant->ijazah }}"
                                    data-letter_employee="{{ $participant->letter_employee }}"
                                    data-letter_health="{{ $participant->letter_health }}"
                                    data-cv="{{ $participant->cv }}" data-status="{{ $participant->status }}">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-gray-400 py-4">Belum ada peserta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Edit tombol
        document.querySelectorAll('.edit-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('participant_id').value = btn.getAttribute('data-id');
                document.getElementById('name').value = btn.getAttribute('data-name');
                document.getElementById('nik').value = btn.getAttribute('data-nik') ?? '';
                document.getElementById('date_birth').value = btn.getAttribute('data-date_birth') ?? '';

                // Tampilkan pesan jika file sudah ada
                document.querySelector('.file-info-photo').innerHTML = btn.getAttribute('data-photo') ?
                    `File sudah ada: <span class="font-medium">${btn.getAttribute('data-photo').split('/').pop()}</span>` :
                    '';
                document.querySelector('.file-info-ijazah').innerHTML = btn.getAttribute('data-ijazah') ?
                    `File sudah ada: <span class="font-medium">${btn.getAttribute('data-ijazah').split('/').pop()}</span>` :
                    '';
                document.querySelector('.file-info-letter_employee').innerHTML = btn.getAttribute(
                        'data-letter_employee') ?
                    `File sudah ada: <span class="font-medium">${btn.getAttribute('data-letter_employee').split('/').pop()}</span>` :
                    '';
                document.querySelector('.file-info-letter_health').innerHTML = btn.getAttribute(
                        'data-letter_health') ?
                    `File sudah ada: <span class="font-medium">${btn.getAttribute('data-letter_health').split('/').pop()}</span>` :
                    '';
                document.querySelector('.file-info-cv').innerHTML = btn.getAttribute('data-cv') ?
                    `File sudah ada: <span class="font-medium">${btn.getAttribute('data-cv').split('/').pop()}</span>` :
                    '';

                // Ubah judul & tombol
                document.getElementById('form-title').innerHTML = `
           
            <svg class="w-8 h-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
            <path strokeLinecap="round" strokeLinejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>

            <h1 class="text-yellow-500">Ubah Peserta: <span class="text-yellow-500">${btn.getAttribute('data-name')}</span></h1>
        `;

                document.getElementById('form-submit-btn').textContent = "Simpan Edit";
                document.getElementById('form-submit-btn').classList.add('bg-yellow-500',
                    'hover:bg-yellow-600', 'text-white');
            });
        });

        // Reset pesan file saat reset form
        function resetForm() {
            document.getElementById('participant_id').value = '';
            document.getElementById('form2').reset();
            document.getElementById('form-title').innerHTML = `
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
        </svg>
        Pendaftaran Peserta
    `;
            const submitBtn = document.getElementById('form-submit-btn');
            submitBtn.textContent = "Kirim";
            submitBtn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600', 'text-white'); // Hapus styling edit
            submitBtn.classList.add('bg-blue-500', 'hover:bg-blue-600', 'text-white'); // Tambahkan styling kirim


            // Hapus info file
            document.querySelector('.file-info-photo').innerHTML = '';
            document.querySelector('.file-info-ijazah').innerHTML = '';
            document.querySelector('.file-info-letter_employee').innerHTML = '';
            document.querySelector('.file-info-letter_health').innerHTML = '';
            document.querySelector('.file-info-cv').innerHTML = '';
        }


        // Preview file upload (optional, sesuai kebutuhanmu)
        document.querySelectorAll('.file-upload-group input[type="file"]').forEach(function(input) {
            const group = input.closest('.file-upload-group');
            const checkmark = group.querySelector('.checkmark');
            input.addEventListener('change', function() {
                if (input.files.length > 0) {
                    checkmark.classList.remove('hidden');
                } else {
                    checkmark.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
