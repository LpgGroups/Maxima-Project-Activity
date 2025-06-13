@extends('layout.main')
@section('container')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="w-full max-w-sm mx-auto bg-white shadow-lg rounded-2xl p-8">
            <img class="mx-auto w-auto h-12 mb-3" src="/img/maximalog.png" alt="LPG">
            <h2 class="text-center text-2xl font-bold text-[#10496C] mb-1">Reset Password</h2>
            <p class="text-xs text-center mb-5">Masukkan password baru untuk akun kamu.</p>

            @if ($errors->any())
                <div class="mb-4 text-center text-red-700 bg-red-100 border border-red-400 px-4 py-2 rounded">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
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

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ old('email', $email) }}">

                {{-- Password Baru --}}
                <div class="mb-3 relative">
                    <label for="password" class="block text-xs font-medium text-[#10496C]">Password Baru</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-3 py-2 border border-[#515151] rounded-md focus:ring focus:border-blue-300 mt-1 text-sm pr-10" />

                    {{-- Eye Toggle --}}
                    <div class="absolute right-3 top-7 cursor-pointer" onclick="toggleVisibility('password', this)">
                        {{-- Eye icon by default --}}
                        <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>

                    {{-- Password Hint --}}
                    <div id="password-hint" class="mt-2 space-y-1 text-[10px]">
                        <div id="hint-length" class="flex items-center gap-1 text-gray-500">
                            <span id="icon-length" class="w-4"></span>
                            Password minimal 8 karakter
                        </div>
                        <div id="hint-upper" class="flex items-center gap-1 text-gray-500">
                            <span id="icon-upper" class="w-4"></span>
                            Mengandung huruf besar (A-Z)
                        </div>
                        <div id="hint-lower" class="flex items-center gap-1 text-gray-500">
                            <span id="icon-lower" class="w-4"></span>
                            Mengandung huruf kecil (a-z)
                        </div>
                        <div id="hint-number" class="flex items-center gap-1 text-gray-500">
                            <span id="icon-number" class="w-4"></span>
                            Mengandung angka (0-9)
                        </div>
                    </div>
                </div>


                {{-- Konfirmasi Password --}}
                <div class="mb-3 relative">
                    <label for="password_confirmation" class="block text-xs font-medium text-[#10496C]">Konfirmasi
                        Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-3 py-2 border border-[#515151] rounded-md focus:ring focus:border-blue-300 mt-1 text-sm pr-10" />

                    {{-- Eye Toggle --}}
                    <div class="absolute right-3 top-7 cursor-pointer"
                        onclick="toggleVisibility('password_confirmation', this)">
                        <svg id="eye-icon-password_confirmation" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>


                <button type="submit"
                    class="w-full bg-gradient-to-b from-yellow-400 to-red-600 text-white py-2 rounded-lg font-semibold text-base mt-2">
                    Reset Password
                </button>
            </form>

            <div class="mt-5 text-center text-xs">
                <a href="{{ route('login.index') }}" class="text-indigo-600 hover:underline">Kembali ke Login</a>
            </div>
        </div>
    </div>

    <script>
        function toggleVisibility(id, iconWrapper) {
            const input = document.getElementById(id);
            const icon = document.getElementById(`eye-icon-${id}`);

            const showIcon = `
            <svg id="eye-icon-${id}" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        `;

            const hideIcon = `
            <svg id="eye-icon-${id}" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.984 9.984 0 012.441-3.899m3.09-2.21A9.935 9.935 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.043 5.306M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3l18 18" />
            </svg>
        `;

            if (input.type === "password") {
                input.type = "text";
                iconWrapper.innerHTML = hideIcon;
            } else {
                input.type = "password";
                iconWrapper.innerHTML = showIcon;
            }
        }
        // Password checking
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

            document.getElementById('icon-length').innerHTML = res.length ? '✅' : '⬜';
            document.getElementById('icon-upper').innerHTML = res.upper ? '✅' : '⬜';
            document.getElementById('icon-lower').innerHTML = res.lower ? '✅' : '⬜';
            document.getElementById('icon-number').innerHTML = res.number ? '✅' : '⬜';
        }

        document.getElementById('password').addEventListener('input', updateHint);
    </script>
@endsection
