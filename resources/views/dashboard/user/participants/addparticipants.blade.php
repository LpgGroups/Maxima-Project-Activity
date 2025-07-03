@extends('dashboard.layouts.dashboardmain')
@section('container')

    <div class="flex space-x-4">
        {{-- FORM (KIRI) --}}
        <div class="max-w-[500px] mt-4 bg-white rounded-3xl shadow-lg p-8">

            <div class="flex justify-between mb-3">
                <button type="button" onclick="resetForm()"
                    class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs font-medium">
                    reset form
                </button>

                @if (isset($form_id))
                    <button type="button" onclick="window.location.href='{{ route('dashboard.form', ['id' => $form_id]) }}'"
                        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-xs font-medium">
                        Selanjutnya
                    </button>
                @endif
            </div>


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
                @php
                    use Carbon\Carbon;
                    $trainingDate = Carbon::parse($training->date);
                    $deadline = $trainingDate->copy()->subDays(3);
                    $isClosed = now()->greaterThanOrEqualTo($deadline);
                @endphp
                @csrf
                <input type="hidden" name="form_id" value="{{ $form_id }}">
                <input type="hidden" name="participant_id" id="participant_id">

                {{-- Nama Lengkap --}}
                <div class="relative mt-4 w-full">
                    <input id="name" name="name" type="text"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="Contoh: Budi Santoso" required />
                    <label for="name"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                </div>


                {{-- NIK --}}
                <div class="relative mt-4 w-full">
                    <input id="nik" name="nik" type="number"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E] placeholder-transparent"
                        placeholder="Nomor Induk Kependudukan" required />
                    <label for="nik"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        Nomor Induk Kependudukan <span class="text-red-500">*</span>
                    </label>
                </div>


                {{-- Tanggal Lahir --}}
                <div class="relative mt-4 w-full">
                    <input id="date_birth" name="date_birth" type="date"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E]"
                        required />
                    <label for="date_birth"
                        class="absolute text-base rounded-lg bg-[#ffffff] text-[#515151] transition-all duration-300 transform -translate-y-4 scale-75 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                </div>


                <div class="relative mt-4 w-full">
                    <select id="blood_type" name="blood_type"
                        class="peer block w-full appearance-none border border-[#515151] bg-transparent px-2.5 py-3 text-sm text-[#515151] rounded-md focus:border-[#1E6E9E] focus:outline-none focus:ring-1 focus:ring-[#1E6E9E]"
                        required>
                        <option value="" disabled
                            {{ old('blood_type', $participant->blood_type ?? '') == '' ? 'selected' : '' }}>Pilih Golongan
                            Darah</option>
                        <option value="A-"
                            {{ old('blood_type', $participant->blood_type ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B-"
                            {{ old('blood_type', $participant->blood_type ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB-"
                            {{ old('blood_type', $participant->blood_type ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O-"
                            {{ old('blood_type', $participant->blood_type ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                        <option value="A+"
                            {{ old('blood_type', $participant->blood_type ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="B+"
                            {{ old('blood_type', $participant->blood_type ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="AB+"
                            {{ old('blood_type', $participant->blood_type ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="O+"
                            {{ old('blood_type', $participant->blood_type ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                    </select>
                    <label for="blood_type"
                        class="absolute text-base bg-white text-[#515151] transition-all duration-300 transform scale-75 -translate-y-4 top-3 left-2.5 ml-2 z-10 origin-[0] peer-focus:text-[#1E6E9E] peer-focus:scale-75 peer-focus:-translate-y-4">
                        Golongan Darah <span class="text-red-500">*</span>
                    </label>
                </div>


                {{-- FOTO --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="photo">Foto<span
                            class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="photo" id="photo" accept="image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Format file: PNG/JPG (maksimal 2MB)</p>
                    <div class="file-info-photo mt-1 text-xs text-green-700"></div>
                </div>

                {{-- IJAZAH --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="ijazah">Ijazah (PDF)<span
                            class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="ijazah" id="ijazah" accept="application/pdf,image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Format file: PDF (maksimal 2MB)</p>
                    <div class="file-info-ijazah mt-1 text-xs text-green-700"></div>
                </div>

                {{-- SURAT Keterangan KARYAWAN --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="letter_employee">Surat Keterangan
                        Kerja (PDF)<span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="letter_employee" id="letter_employee"
                            accept="application/pdf,image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Format file: PDF (maksimal 2MB)</p>
                    <p class="mt-1 text-xs text-gray-500">
                        Download Template ini Jika <strong>Belum Bekerja</strong>
                        <a href="http://maximagroup.co.id/wp-content/uploads/2025/07/SURAT-KETERANGAN-BELUM-BEKERJA-JIKA-BELUM-BEKERJA.pdf"
                            download class="text-blue-600 underline ml-2" target="_blank">
                            Download Template
                        </a>
                    </p>
                    <div class="file-info-letter_employee mt-1 text-xs text-green-700"></div>
                </div>

                {{-- Surat Pernyataan --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="letter_statement">Surat Pernyataan
                        (PDF)<span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="letter_statement" id="letter_statement"
                            accept="application/pdf,image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Format file: PDF (maksimal 2MB)</p>
                    <p class="mt-1 text-xs text-gray-500">
                        Template Surat Pernyataan
                        <a href=" http://maximagroup.co.id/wp-content/uploads/2025/07/Template-Pernyataan-Pilih-sesuai-Pelatihan-yang-di-ikuti.zip"
                            download class="text-blue-600 underline ml-2" target="_blank">
                            Download Template
                        </a>
                    </p>
                    <div class="file-info-letter_statement mt-1 text-xs text-green-700"></div>
                </div>

                {{-- Form-Registration --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="form_registration">Formulir
                        Pendaftaran (PDF)<span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="form_registration" id="form_registration"
                            accept="application/pdf,image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Format file: PDF (maksimal 2MB)</p>
                    <p class="mt-1 text-xs text-gray-500">
                        Template Formulir Pendaftaran
                        <a href="http://maximagroup.co.id/wp-content/uploads/2025/07/FORM-ADM-01-Formulir-Pendaftaran-2025.pdf"
                            download class="text-blue-600 underline ml-2" target="_blank">
                            Download Template
                        </a>
                    </p>
                    <div class="file-info-form_registration mt-1 text-xs text-green-700"></div>
                </div>

                {{-- SURAT SEHAT --}}
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="letter_health">Surat Keterangan Sehat
                        (PDF)<span class="text-red-500">*</span></label>
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
                    <p class="mt-1 text-xs text-gray-500">Format file: PDF (maksimal 2MB)</p>
                    <div class="file-info-letter_health mt-1 text-xs text-green-700"></div>
                </div>


                @if ($training->activity == 'AK3U')
                    {{-- CV --}}
                    <div class="relative file-upload-group mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="cv">CV (PDF/DOC/DOCX)<span
                                class="text-red-500">*</span></label>
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
                        <p class="mt-1 text-xs text-gray-500">Format file: PDF/DOC/DOCX (maksimal 2MB)</p>
                        <div class="file-info-cv mt-1 text-xs text-green-700"></div>
                    </div>
                @endif

                <div class="relative">
                    <button type="submit" id="form-submit-btn"
                        class="w-full font-semibold py-2 rounded transition-colors duration-200
               {{ $isClosed ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 text-white' }}"
                        {{ $isClosed ? 'disabled' : '' }} data-training-date="{{ $trainingDate->format('Y-m-d') }}">
                        {{ $isClosed ? 'Pendaftaran sudah ditutup' : 'Simpan Peserta' }}
                    </button>
                    <div id="countdown-tooltip"
                        class="absolute left-1/2 -translate-x-1/2 mt-2 text-xs text-gray-700 bg-gray-100 rounded px-3 py-2 shadow hidden">
                    </div>
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
                            <td class="max-w-[80px] truncate whitespace-nowrap" title="{{ $participant->name }}">
                                {{ $participant->name }}</td>
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
                            <td class= "space-y-1">
                                <!-- Edit Button -->
                                <button type="button"
                                    class="edit-btn bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs"
                                    data-id="{{ $participant->id }}" data-name="{{ $participant->name }}"
                                    data-nik="{{ $participant->nik }}" data-date_birth="{{ $participant->date_birth }}"
                                    data-photo="{{ $participant->photo }}" data-ijazah="{{ $participant->ijazah }}"
                                    data-letter_employee="{{ $participant->letter_employee }}"
                                    data-letter_statement="{{ $participant->letter_statement }}"
                                    data-form_registration="{{ $participant->form_registration }}"
                                    data-letter_health="{{ $participant->letter_health }}"
                                    data-blood_type="{{ $participant->blood_type }}">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931ZM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button type="button"
                                    class="delete-btn bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-xs ml-2"
                                    data-id="{{ $participant->id }}" data-name="{{ $participant->name }}">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9M19.228 5.79c.342.052.682.107 1.022.166M19.228 5.79L18.16 19.673A2.25 2.25 0 0115.916 21H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397M6.25 6.352c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
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
        document.addEventListener('DOMContentLoaded', function() {
            window.isClosed = @json($isClosed);
            window.participantIdEdit = "{{ request()->participant_id ?? '' }}";

            // Fungsi aman untuk update file info
            const setFileInfo = (selector, filePath) => {
                const el = document.querySelector(selector);
                if (el) {
                    el.innerHTML = filePath ?
                        `File sudah ada: <span class="font-medium">${filePath.split('/').pop()}</span>` :
                        '';
                }
            };

            // Listener tombol edit
            document.querySelectorAll('.edit-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('participant_id').value = btn.getAttribute('data-id');
                    document.getElementById('name').value = btn.getAttribute('data-name');
                    document.getElementById('nik').value = btn.getAttribute('data-nik') ?? '';
                    document.getElementById('date_birth').value = btn.getAttribute(
                        'data-date_birth') ?? '';
                    document.getElementById('blood_type').value = btn.getAttribute(
                        'data-blood_type') ?? '';

                    setFileInfo('.file-info-photo', btn.getAttribute('data-photo'));
                    setFileInfo('.file-info-ijazah', btn.getAttribute('data-ijazah'));
                    setFileInfo('.file-info-letter_employee', btn.getAttribute(
                        'data-letter_employee'));
                    setFileInfo('.file-info-letter_statement', btn.getAttribute(
                        'data-letter_statement'));
                    setFileInfo('.file-info-form_registration', btn.getAttribute(
                        'data-form_registration'));
                    setFileInfo('.file-info-letter_health', btn.getAttribute('data-letter_health'));
                    setFileInfo('.file-info-cv', btn.getAttribute('data-cv'));

                    document.getElementById('form-title').innerHTML = `
                    <svg class="w-8 h-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    <h1 class="text-yellow-500">Ubah Peserta: <span class="text-yellow-500">${btn.getAttribute('data-name')}</span></h1>
                `;

                    const submitBtn = document.getElementById('form-submit-btn');
                    submitBtn.textContent = "Simpan Edit";
                    submitBtn.classList.add('bg-yellow-500', 'hover:bg-yellow-600', 'text-white');
                    submitBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                });
            });

            // Auto click tombol edit jika participantIdEdit ada
            if (window.participantIdEdit) {
                setTimeout(function() {
                    const autoBtn = document.querySelector(
                        `.edit-btn[data-id="${window.participantIdEdit}"]`);
                    if (autoBtn) {
                        autoBtn.click();
                    } else {
                        console.warn("Tidak ketemu tombol edit-btn dengan data-id:", window
                            .participantIdEdit);
                    }
                }, 100);
            }

            // Countdown untuk tombol pendaftaran
            const btn = document.getElementById("form-submit-btn");
            const tooltip = document.getElementById("countdown-tooltip");
            const trainingDateStr = btn?.dataset?.trainingDate;

            if (btn && tooltip && trainingDateStr) {
                const trainingDate = new Date(trainingDateStr);
                const deadline = new Date(trainingDate);
                deadline.setDate(trainingDate.getDate() - 5);

                function showCountdown() {
                    const now = new Date();
                    const distance = deadline - now;

                    if (distance <= 0) {
                        tooltip.textContent = "Pendaftaran sudah ditutup.";
                        tooltip.classList.remove('hidden');
                        btn.disabled = true;
                        btn.textContent = "Pendaftaran sudah ditutup";
                        btn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'text-white');
                        btn.classList.add('bg-gray-400', 'cursor-not-allowed');
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    tooltip.textContent = `Sisa waktu pendaftaran: ${days}h ${hours}j ${minutes}m ${seconds}d`;
                    tooltip.classList.remove('hidden');
                }

                if (!btn.disabled) {
                    showCountdown();
                    setInterval(showCountdown, 1000);
                }
            }

            // Reset form function
            window.resetForm = function() {
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
                submitBtn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600', 'text-white');
                submitBtn.classList.add('bg-blue-500', 'hover:bg-blue-600', 'text-white');

                setFileInfo('.file-info-photo', null);
                setFileInfo('.file-info-ijazah', null);
                setFileInfo('.file-info-letter_employee', null);
                setFileInfo('.file-info-letter_statement', null);
                setFileInfo('.file-info-form_registration', null);
                setFileInfo('.file-info-letter_health', null);
                setFileInfo('.file-info-cv', null);
            };

            // Preview icon centang saat file diunggah
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

            // Tombol hapus peserta
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name') || '(Tanpa Nama)';
                    if (confirm(`Yakin ingin menghapus peserta: ${name}?`)) {
                        fetch(`/dashboard/user/training/participant/delete/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Peserta berhasil dihapus!');
                                    location.reload();
                                } else {
                                    alert(data.message || 'Gagal menghapus peserta.');
                                }
                            })
                            .catch(() => alert('Gagal menghapus peserta.'));
                    }
                });
            });
        });
    </script>

@endsection
