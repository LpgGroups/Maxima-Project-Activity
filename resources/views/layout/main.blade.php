<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <script type="module">
        import('/resources/js/app.js')
            .then(() => console.log("🧪 app.js loaded manually"))
            .catch(err => console.error("❌ Failed to load app.js:", err));
    </script>
    @vite('resources/js/app.js')
    {{-- @vite('resources/js/authentifikasi.js') --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<title>Maxima</title>

<body class="">

    @include('layout.partial.navbar')
    <div class="container mt-4 font-poppins">
        @yield('container')
    </div>
    @include('layout.partial.footer')

</body>

</html>
