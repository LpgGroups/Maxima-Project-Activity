@extends('layout.main')

@section('container')
    <div class="max-w-2xl mx-auto py-8 px-4">

        <div id="status-message" class="mb-4 rounded-lg border p-3 text-sm bg-green-50 text-green-700" style="display:none;">
        </div>

        <div class="rounded-2xl border p-6 shadow-sm bg-white">
            <h1 class="text-2xl font-semibold mb-6">Formulir Rekrutmen </h1>

            <form id="dummy-form" action="#" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input name="full_name" type="text" value="{{ old('full_name') }}" required
                        class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
                        <input name="email" type="email" value="{{ old('email') }}" required
                            class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">No whatsapp (aktif)</label>
                        <input name="phone" type="text" value="{{ old('phone') }}"
                            class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tempat Lahir <span
                                class="text-red-500">*</span></label>
                        <input name="birth_place" type="text" value="{{ old('birth_place') }}" required
                            class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Lahir <span
                                class="text-red-500">*</span></label>
                        <input name="birth_date" type="date" value="{{ old('birth_date') }}" required
                            class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Jenis Kelamain</label>
                        <select name="gender" class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2">
                            <option value="">Pilih</option>
                            <option value="male" @selected(old('gender') === 'male')>Laki-Laki</option>
                            <option value="female" @selected(old('gender') === 'female')>Perempuan</option>

                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Lulusan Terakhir <span
                                class="text-red-500">*</span></label>
                        <input name="last_education" type="text" value="{{ old('last_education') }}" required
                            placeholder="SMA/SMK, D3, S1, S2, dsb."
                            class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Alamat</label>
                    <textarea name="address" rows="3" class="w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Resume/Curiculum Vitae (PDF/DOC/DOCX) <span
                            class="text-red-500">*</span></label>
                    <input name="resume" type="file" accept=".pdf,.doc,.docx" required
                        class="w-full rounded-xl border px-3 py-2 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border file:bg-gray-50" />
                    <p class="text-xs text-gray-500 mt-1">Maks 5 MB.</p>
                </div>
                <div class="font-bold text-red-500 text-xs">
                    <li>Harap diperhatikan: Data yang dikirimkan haruslah benar dan sesuai</li>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white rounded-xl px-4 py-2 font-semibold shadow hover:shadow-md transition">
                    Kirim Lamaran
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('dummy-form').addEventListener('submit', function(event) {
            event.preventDefault(); // cegah submit asli

            // Tampilkan pesan sukses
            const statusEl = document.getElementById('status-message');
            statusEl.textContent = 'Lamaran berhasil dikirim! Terima kasih.';
            statusEl.style.display = 'block';
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            this.reset();
        });
    </script>
@endsection
