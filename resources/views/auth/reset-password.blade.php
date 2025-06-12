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

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ old('email', $email) }}">

                <div class="mb-3">
                    <label for="password" class="block text-xs font-medium text-[#10496C]">Password Baru</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-3 py-2 border border-[#515151] rounded-md focus:ring focus:border-blue-300 mt-1 text-sm" />

                    <!-- Password Hint -->
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

                <div class="mb-3">
                    <label for="password_confirmation" class="block text-xs font-medium text-[#10496C]">Konfirmasi
                        Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-3 py-2 border border-[#515151] rounded-md focus:ring focus:border-blue-300 mt-1 text-sm" />
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
