<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'ServiPro'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Header personalizado -->
    @include('partials.header')

    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer personalizado -->
    @include('partials.footer')

    <!-- Scripts adicionales -->
    <script>
        // Toggle para el menú móvil
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Toggle para el dropdown de usuario
        function toggleUserDropdown() {
            const userDropdown = document.getElementById('user-dropdown');
            userDropdown.classList.toggle('hidden');
        }

        // Cerrar dropdowns cuando se hace clic fuera
        document.addEventListener('click', function(event) {
            const userButton = document.getElementById('user-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userButton && userDropdown && !userButton.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
