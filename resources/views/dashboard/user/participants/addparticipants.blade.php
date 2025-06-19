@extends('dashboard.layouts.dashboardmain')
@section('container')
    <div class="flex space-x-4">
        <div class="max-w-[500px] mt-4 bg-white rounded-3xl shadow-lg p-8">
            <h2 id="form-title"
                class="text-2xl font-bold mb-4 text-center text-blue-700 flex items-center justify-center gap-2">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                </svg>
                Pendaftaran Peserta
            </h2>

            <p class="mb-6 text-gray-600 text-center text-sm">Silakan isi data peserta dengan benar. Data Anda aman dan
                hanya
                digunakan untuk keperluan pendaftaran.</p>

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

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block mb-1 font-medium text-gray-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-4 py-2 text-base"
                        placeholder="Contoh: Budi Santoso" />
                </div>

                <!-- NIK -->
                <div>
                    <label for="nik" class="block mb-1 font-medium text-gray-700">
                        NIK
                    </label>
                    <input type="text" name="nik" id="nik"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-4 py-2 text-base"
                        placeholder="Nomor Induk Kependudukan" />
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="date_birth" class="block mb-1 font-medium text-gray-700">
                        Tanggal Lahir
                    </label>
                    <input type="date" name="date_birth" id="date_birth"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-4 py-2 text-base"
                        placeholder="Tanggal Lahir" />
                </div>

                <!-- Foto -->
                <!-- FOTO -->
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
                    <div class="file-success-name mt-1 text-green-600 text-sm">
                        @if (session('uploaded_files')['photo'] ?? false)
                            {{ session('uploaded_files')['photo'] }}
                        @endif
                    </div>
                </div>

                <!-- IJAZAH -->
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
                    <div class="file-success-name mt-1 text-green-600 text-sm">
                        @if (session('uploaded_files')['ijazah'] ?? false)
                            {{ session('uploaded_files')['ijazah'] }}
                        @endif
                    </div>
                </div>

                <!-- SURAT KARYAWAN -->
                <div class="relative file-upload-group mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="letter_employee">Surat Karyawan
                        (PDF/JPG/PNG)</label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="letter_employee" id="letter_employee" accept="application/pdf,image/*"
                            class="block w-full text-sm text-gray-600 border border-gray-300 rounded cursor-pointer" />
                        <span class="checkmark hidden">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </div>
                    <div class="file-success-name mt-1 text-green-600 text-sm">
                        @if (session('uploaded_files')['letter_employee'] ?? false)
                            {{ session('uploaded_files')['letter_employee'] }}
                        @endif
                    </div>
                </div>

                <!-- SURAT SEHAT -->
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
                    <div class="file-success-name mt-1 text-green-600 text-sm">
                        @if (session('uploaded_files')['letter_health'] ?? false)
                            {{ session('uploaded_files')['letter_health'] }}
                        @endif
                    </div>
                </div>

                <!-- CV -->
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
                    <div class="file-success-name mt-1 text-green-600 text-sm">
                        @if (session('uploaded_files')['cv'] ?? false)
                            {{ session('uploaded_files')['cv'] }}
                        @endif
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition-colors duration-200">
                        Kirim
                    </button>
                </div>
            </form>
        </div>

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
                                    data-status="{{ $participant->status }}">Edit</button>
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
    <!-- Tambahkan di akhir form sebelum tag </form> -->
    <script>
        // Untuk preview file
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

        // Script untuk tombol Edit di tabel peserta
        document.querySelectorAll('.edit-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Isi form sesuai data yang diklik
                document.getElementById('participant_id').value = btn.getAttribute('data-id');
                document.getElementById('name').value = btn.getAttribute('data-name');
                // Kamu bisa tambahkan isi field lain jika ingin (nik, tanggal lahir, dsb)
                // document.getElementById('nik').value = ... dst

                // Ubah judul form jadi "Edit Peserta: [nama]"
                const nama = btn.getAttribute('data-name');
                document.getElementById('form-title').innerHTML = `
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                </svg>
                Edit Peserta: <span class="text-blue-700">${nama}</span>
            `;
            });
        });

        // Tambahkan fungsi reset judul & form (misal untuk tambah baru)
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
        }
    </script>

@endsection
