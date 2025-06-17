@extends('layout.main')

@section('container')
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="w-full max-w-sm mx-auto bg-white shadow-lg rounded-2xl p-8">
            <img class="mx-auto w-auto h-12 mb-3" src="/img/maximalog.png" alt="LPG">
            <h2 class="text-center text-2xl font-bold text-[#10496C] mb-1">Lupa Password?</h2>
            <p class="text-xs text-center mb-5">Masukkan email terdaftar, link reset password akan dikirim ke email kamu.</p>

            @if (session('status'))
                <div class="mb-4 text-center text-green-700 bg-green-100 border border-green-400 px-4 py-2 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-center text-red-700 bg-red-100 border border-red-400 px-4 py-2 rounded">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                @csrf
                <div class="mb-3">
                    <label for="email" class="block text-xs font-medium text-[#10496C]">Email</label>
                    <input type="email" id="email" name="email" required autofocus
                        class="w-full px-3 py-2 border border-[#515151] rounded-md focus:ring focus:border-blue-300 mt-1 text-sm" />
                </div>
                <button type="submit" id="submit-btn"
                    class="w-full bg-gradient-to-b from-yellow-400 to-red-600 text-white py-2 rounded-lg font-semibold text-base mt-2">
                    Kirim Link Reset Password
                </button>
            </form>
            <div class="mt-5 text-center text-xs">
                <a href="{{ route('login.index') }}" class="text-indigo-600 hover:underline">Kembali ke Login</a>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const submitBtn = document.getElementById('submit-btn');
        const form = document.getElementById('forgot-password-form');
        const COOLDOWN_KEY = 'forgotPwdCooldown';
        const COOLDOWN_TIME = 120; // detik

        function disableButton(secondsLeft) {
            submitBtn.disabled = true;
            updateButtonText(secondsLeft);
            let countdown = secondsLeft;
            let interval = setInterval(() => {
                countdown--;
                updateButtonText(countdown);
                if (countdown <= 0) {
                    clearInterval(interval);
                    enableButton();
                    localStorage.removeItem(COOLDOWN_KEY);
                }
            }, 1000);
        }

        function updateButtonText(sisa) {
            submitBtn.textContent = 'Tunggu ' + sisa + ' detik...';
        }

        function enableButton() {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim Link Reset Password';
        }

        // Check cooldown on load
        document.addEventListener('DOMContentLoaded', function() {
            const cooldownUntil = localStorage.getItem(COOLDOWN_KEY);
            if (cooldownUntil) {
                const now = Math.floor(Date.now() / 1000);
                const secondsLeft = cooldownUntil - now;
                if (secondsLeft > 0) {
                    disableButton(secondsLeft);
                } else {
                    enableButton();
                    localStorage.removeItem(COOLDOWN_KEY);
                }
            }
        });

        // Handle submit with AJAX
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            enableButton(); // Reset (just in case)

            // Ambil data
            const formData = new FormData(form);

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';

            // Loading alert
            Swal.fire({
                title: 'Mengirim...',
                text: 'Kami sedang mengirimkan link reset password ke email kamu.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX POST request
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(async (response) => {
                    const res = await response.json();
                    Swal.close();
                    if (response.ok) {
                        // Success: aktifkan countdown & disable tombol
                        Swal.fire('Berhasil!', res.message || 'Link reset password sudah dikirim.',
                            'success');
                        const now = Math.floor(Date.now() / 1000);
                        localStorage.setItem(COOLDOWN_KEY, now + COOLDOWN_TIME);
                        disableButton(COOLDOWN_TIME);
                    } else {
                        // Error (email tidak ditemukan, dsb): aktifkan tombol lagi
                        let errorMsg = res.message || 'Terjadi kesalahan. Silakan coba lagi.';
                        if (res.errors && res.errors.email) {
                            errorMsg = res.errors.email[0];
                        }
                        Swal.fire('Gagal!', errorMsg, 'error');
                        enableButton();
                    }
                })
                .catch(() => {
                    Swal.close();
                    Swal.fire('Gagal!', 'Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
                    enableButton();
                });
        });
    </script>


@endsection
