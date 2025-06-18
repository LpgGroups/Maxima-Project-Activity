@extends('dashboard.layouts.dashboardmain')

@section('container')
    <div class="max-w-xl mx-auto mt-8 bg-white rounded-xl shadow p-8">
        <h2 class="text-xl font-semibold mb-4">Pengaturan Profil</h2>

        @if (session('status'))
            <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-medium mb-1">Nama</label>
                <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $user->name) }}"
                    required>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Perusahaan</label>
                <input type="text" name="company" class="w-full border rounded p-2"
                    value="{{ old('company', $user->company) }}">
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">No. HP</label>
                <input type="text" name="phone" class="w-full border rounded p-2"
                    value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="mb-3 relative">
                <label for="password" class="block text-xs font-medium text-[#10496C]">Password Baru</label>
                <input type="password" id="password" name="password"
                    class="w-full px-3 py-2 border border-[#515151] rounded-md focus:ring focus:border-blue-300 mt-1 text-sm pr-10"
                    autocomplete="new-password" oninput="updateHint(); checkMatch();" />
                <div class="absolute right-3 top-7 cursor-pointer" onclick="toggleVisibility('password', this)">
                    <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <!-- Hint password -->
                <div class="mt-1 text-xs space-y-1">
                    <div><span id="icon-length">⬜</span> Minimal 8 karakter</div>
                    <div><span id="icon-upper">⬜</span> Ada huruf besar (A-Z)</div>
                    <div><span id="icon-lower">⬜</span> Ada huruf kecil (a-z)</div>
                    <div><span id="icon-number">⬜</span> Ada angka (0-9)</div>
                </div>
            </div>

            <div class="mb-3 relative">
                <label for="password_confirmation" class="block text-xs font-medium text-[#10496C]">Konfirmasi
                    Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-3 py-2 border border-[#515151] rounded-md focus:ring focus:border-blue-300 mt-1 text-sm pr-10"
                    autocomplete="new-password" oninput="checkMatch();" />
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
                <div id="match-msg" class="mt-1 text-xs"></div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </form>
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

        // Password rule checker
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

        // Password match checker
        function checkMatch() {
            const pw = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const msg = document.getElementById('match-msg');
            if (!confirm) {
                msg.textContent = '';
                return;
            }
            if (pw === confirm) {
                msg.textContent = '✅ Password cocok';
                msg.classList.remove('text-red-600');
                msg.classList.add('text-green-600');
            } else {
                msg.textContent = '❌ Password tidak cocok';
                msg.classList.remove('text-green-600');
                msg.classList.add('text-red-600');
            }
        }

        // Attach to input events
        document.getElementById('password').addEventListener('input', function() {
            updateHint();
            checkMatch();
        });
        document.getElementById('password_confirmation').addEventListener('input', checkMatch);
    </script>
@endsection
