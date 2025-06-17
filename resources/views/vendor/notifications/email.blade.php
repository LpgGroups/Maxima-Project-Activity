<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - Maxima Group</title>
</head>

<body style="background-color:#f3f4f6; margin:0; padding:0; font-family:Segoe UI, sans-serif;">
    <div
        style="max-width:600px; margin:40px auto; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <!-- Logo -->
        <div style="text-align:center; padding:32px 20px 16px;">
            <img src="https://e-registrasi.maximagroup.co.id/img/maximalog.png" alt="Maxima Logo"
                style="width:120px; margin:0 auto;">
        </div>

        <!-- Body -->
        <div style="padding:0 32px 32px; color:#1f2937;">
            <h1 style="font-size:20px; font-weight:600; margin-bottom:16px;">Halo,</h1>
            <p style="font-size:16px; line-height:1.6; margin-bottom:24px;">
                Kamu menerima email ini karena kami menerima permintaan reset password untuk akun kamu.
            </p>

            <!-- Button -->
            <div style="text-align:center;">
                <a href="{{ $actionUrl }}"
                    style="background-color:#3b82f6; color:#ffffff; padding:12px 24px; font-weight:600; text-decoration:none; border-radius:6px; display:inline-block;">
                    Reset Password
                </a>
            </div>

            <p style="font-size:14px; color:#6b7280; margin-top:32px;">
                Link ini akan kadaluarsa dalam 60 menit.
            </p>
            <p style="font-size:14px; color:#6b7280;">
                Jika kamu tidak meminta reset password, abaikan saja email ini.
            </p>

            <p style="font-size:16px; margin-top:24px;">Regards,<br>Maxima Team</p>

            <!-- Fallback URL -->
            <div style="margin-top:32px; font-size:14px; color:#6b7280;">
                <p>Jika tombol di atas tidak bisa diklik, salin dan tempel URL berikut ke browser:</p>
                <p style="word-break:break-all;">
                    <a href="{{ $actionUrl }}" style="color:#3b82f6;">{{ $actionUrl }}</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="text-align:center; font-size:12px; color:#9ca3af; margin-top:24px;">
        © {{ date('Y') }} Maxima Group. All rights reserved.
    </div>
</body>

</html>
