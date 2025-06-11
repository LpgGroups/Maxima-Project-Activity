<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    {{-- @vite('resources/js/authentifikasi.js') --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<title>Maxima</title>

<body class="overflow-x-hidden">

    @include('layout.partial.navbar')
    <div class="container mt-4 font-poppins">
        @yield('container')
    </div>
    @include('layout.partial.footer')
    @stack('scripts')
</body>

</html>
