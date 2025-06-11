@extends('layout.main')
@section('container')
    <div class="flex min-h-screen mt-2 w-full bg-gray-100">
        <div
            class="w-[500px] max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl bg-white rounded-2xl shadow-lg p-6 sm:p-8 md:p-10 space-y-6">
            <h2 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-6 text-center">Daftar</h2>
            @if ($errors->any())
                <div class="mb-4">
                    <ul class="text-red-500 text-sm sm:text-base">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-center"
                    role="alert">
                    <strong>{{ session('success') }}</strong>
                    <br>
                    <span>Kamu akan diarahkan ke halaman login dalam <span id="countdown">5</span> detik...</span>
                    <br>
                    <a href="{{ route('login.index') }}" class="underline text-blue-600">Klik di sini untuk login</a>
                </div>
                <script>
                    let seconds = 5;
                    const countdown = document.getElementById('countdown');
                    const redirectUrl = "{{ route('login.index') }}";
                    setInterval(function() {
                        seconds--;
                        if (seconds <= 0) {
                            window.location.href = redirectUrl;
                        } else {
                            countdown.innerText = seconds;
                        }
                    }, 1000);
                </script>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-3 sm:space-y-4" autocomplete="off">
                @csrf
                <div>
                    <label class="block mb-1 font-medium text-sm sm:text-base">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 text-sm sm:text-base" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-sm sm:text-base">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 text-sm sm:text-base" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-sm sm:text-base">Perusahaan (Opsional)</label>
                    <input type="text" name="company" value="{{ old('company') }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 text-sm sm:text-base" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-sm sm:text-base">No WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 text-sm sm:text-base" />
                </div>
                <div class="sm:flex sm:space-x-4">
                    <div class="flex-1">
                        <label class="block mb-1 font-medium text-sm sm:text-base">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 text-sm sm:text-base" />

                        <!-- Password hint live -->
                        <div id="password-hint" class="mt-2 space-y-1 text-[10px] sm:text-[10px]">
                            <div id="hint-length" class="flex items-center gap-1 text-gray-500">
                                <span id="icon-length" class="w-4"></span>
                                Password Minimal 8 karakter
                            </div>
                            <div id="hint-upper" class="flex items-center gap-1 text-gray-500">
                                <span id="icon-upper" class="w-4"></span>
                                Password Memiliki Huruf besar (A-Z)
                            </div>
                            <div id="hint-lower" class="flex items-center gap-1 text-gray-500">
                                <span id="icon-lower" class="w-4"></span>
                                Password Memiliki Huruf kecil (a-z)
                            </div>
                            <div id="hint-number" class="flex items-center gap-1 text-gray-500">
                                <span id="icon-number" class="w-4"></span>
                                Password Memiliki Angka (0-9)
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 mt-3 sm:mt-0">
                        <label class="block mb-1 font-medium text-sm sm:text-base">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300 text-sm sm:text-base" />
                    </div>
                </div>
                <div class="mb-4">
                    <div class="g-recaptcha" data-sitekey="6LfonFwrAAAAAND5QBUOo83OcNtOzWE-S1Ti4g3t"></div>
                    @error('g-recaptcha-response')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-base font-semibold mt-3">
                    Register
                </button>
            </form>
        </div>
    </div>
    <script>
        // Kriteria password
        function checkPassword(pw) {
            return {
                length: pw.length >= 8,
                upper: /[A-Z]/.test(pw),
                lower: /[a-z]/.test(pw),
                number: /[0-9]/.test(pw),
            }
        }

        function updateHint() {
            const pw = document.getElementById('password').value;
            const res = checkPassword(pw);

            // Ceklis hijau jika OK, silang abu jika belum
            document.getElementById('icon-length').innerHTML = res.length ? '✅' : '⬜';
            document.getElementById('icon-upper').innerHTML = res.upper ? '✅' : '⬜';
            document.getElementById('icon-lower').innerHTML = res.lower ? '✅' : '⬜';
            document.getElementById('icon-number').innerHTML = res.number ? '✅' : '⬜';
        }

        document.getElementById('password').addEventListener('input', updateHint);
    </script>

@endsection
