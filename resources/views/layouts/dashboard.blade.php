<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - ServiPro</title>
    <meta name="description" content="@yield('description', 'Panel de control de ServiPro')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow bg-white border-r border-gray-200 pt-5 pb-4 overflow-y-auto">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4 mb-8">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cogs text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">ServiPro</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors duration-200">
                        <i class="fas fa-th-large mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Dashboard
                    </a>

                    <!-- Mi Perfil -->
                    <a href="{{ route('profile.edit') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors duration-200">
                        <i class="fas fa-user mr-3 {{ request()->routeIs('profile.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Mi Perfil
                    </a>

                    <!-- Mis Servicios -->
                    <a href="{{ route('services.my') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('services.my') || request()->routeIs('services.create') || request()->routeIs('services.edit') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors duration-200">
                        <i class="fas fa-briefcase mr-3 {{ request()->routeIs('services.my') || request()->routeIs('services.create') || request()->routeIs('services.edit') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Mis Servicios
                    </a>
                </nav>

                <!-- User Info & Logout -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex items-center w-full">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            @if(auth()->check() && auth()->user()->hasValidImage())
                                <img 
                                    src="{{ auth()->user()->image_url }}" 
                                    alt="{{ auth()->user()->name }}"
                                    class="h-8 w-8 rounded-full object-cover"
                                >
                            @else
                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                    {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                                </div>
                            @endif
                        </div>

                        <!-- User Info -->
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ auth()->check() ? auth()->user()->name : 'Usuario' }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ auth()->check() ? auth()->user()->email : 'No autenticado' }}
                            </p>
                        </div>

                        <!-- Logout Button -->
                        <div class="ml-3">
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors duration-200" title="Cerrar Sesión">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-cogs text-white text-sm"></i>
                                </div>
                                <span class="text-xl font-bold text-gray-900">ServiPro</span>
                            </a>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- User Dropdown -->
                            <div class="relative inline-block">
                                <button 
                                    id="user-button"
                                    onclick="toggleUserDropdown()"
                                    class="flex items-center space-x-3 p-2 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 transition-colors duration-200"
                                >
                                    <!-- User Avatar -->
                                    <div class="relative">
                                        @if(auth()->check() && auth()->user()->hasValidImage())
                                            <img 
                                                src="{{ auth()->user()->image_url }}" 
                                                alt="{{ auth()->user()->name }}"
                                                class="w-8 h-8 rounded-full object-cover"
                                            >
                                        @else
                                            <div class="inline-block h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                                {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                                            </div>
                                        @endif
                                        <!-- Online Status Dot -->
                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    
                                    <!-- User Info -->
                                    <div class="text-left">
                                        <div class="text-sm font-semibold text-gray-900">{{ auth()->check() ? auth()->user()->name : 'Usuario' }}</div>
                                        <div class="text-xs text-gray-600">Usuario</div>
                                    </div>
                                    
                                    <!-- Dropdown Arrow -->
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </button>

                                <!-- Dropdown Menu -->
                                <div 
                                    id="user-dropdown"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-1 z-50 hidden"
                                >
                                    <!-- User Full Name Section -->
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <div class="flex items-center space-x-3">
                                            @if(auth()->check() && auth()->user()->hasValidImage())
                                                <img 
                                                    src="{{ auth()->user()->image_url }}" 
                                                    alt="{{ auth()->user()->name }}"
                                                    class="w-10 h-10 rounded-full object-cover"
                                                >
                                            @else
                                                <div class="inline-block h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-lg font-medium">
                                                    {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ auth()->check() ? auth()->user()->full_name : 'Usuario' }}</div>
                                                <div class="text-xs text-gray-500">{{ auth()->check() ? auth()->user()->email : 'No autenticado' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Menu Items -->
                                    <a href="{{ route('profile.edit') }}" class="block w-full text-left px-4 py-2 text-sm {{ request()->routeIs('profile.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors duration-200">
                                        <i class="fas fa-user mr-3 {{ request()->routeIs('profile.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                        Mi Perfil
                                    </a>
                                    <a href="{{ route('home') }}" class="block w-full text-left px-4 py-2 text-sm {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors duration-200">
                                        <i class="fas fa-home mr-3 {{ request()->routeIs('home') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                        Página Principal
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="block w-full text-left px-4 py-2 text-sm {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors duration-200">
                                        <i class="fas fa-th-large mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('services.my') }}" class="block w-full text-left px-4 py-2 text-sm {{ request()->routeIs('services.my') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors duration-200">
                                        <i class="fas fa-briefcase mr-3 {{ request()->routeIs('services.my') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                        Mis Servicios
                                    </a>
                                    <a href="{{ route('services.create') }}" class="block w-full text-left px-4 py-2 text-sm {{ request()->routeIs('services.create') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors duration-200">
                                        <i class="fas fa-plus mr-3 {{ request()->routeIs('services.create') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                        Crear Servicio
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                            <i class="fas fa-sign-out-alt mr-3"></i>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Top bar for mobile -->
                            <div class="md:hidden">
                                <button 
                                    onclick="toggleMobileSidebar()"
                                    class="p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
                                >
                                    <span class="sr-only">Abrir sidebar</span>
                                    <i class="fas fa-bars text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile Sidebar Overlay -->
            <div id="mobile-sidebar-overlay" class="fixed inset-0 flex z-40 md:hidden hidden">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileSidebar()"></div>
                
                <!-- Mobile Sidebar -->
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button 
                            onclick="toggleMobileSidebar()"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        >
                            <span class="sr-only">Cerrar sidebar</span>
                            <i class="fas fa-times text-white text-lg"></i>
                        </button>
                    </div>

                    <!-- Mobile Sidebar Content -->
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <!-- Logo -->
                        <div class="flex items-center flex-shrink-0 px-4 mb-8">
                            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-cogs text-white text-sm"></i>
                                </div>
                                <span class="text-xl font-bold text-gray-900">ServiPro</span>
                            </a>
                        </div>

                        <!-- Mobile Navigation -->
                        <nav class="px-2 space-y-1">
                            <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors duration-200">
                                <i class="fas fa-th-large mr-4 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                Dashboard
                            </a>

                            <a href="{{ route('profile.edit') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors duration-200">
                                <i class="fas fa-user mr-4 {{ request()->routeIs('profile.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                Mi Perfil
                            </a>

                            <a href="{{ route('services.my') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('services.my') || request()->routeIs('services.create') || request()->routeIs('services.edit') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors duration-200">
                                <i class="fas fa-briefcase mr-4 {{ request()->routeIs('services.my') || request()->routeIs('services.create') || request()->routeIs('services.edit') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                Mis Servicios
                            </a>
                        </nav>
                    </div>

                    <!-- Mobile User Info & Logout -->
                    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                        <div class="flex items-center w-full">
                            <div class="flex-shrink-0">
                                @if(auth()->user()->hasValidImage())
                                    <img 
                                        src="{{ auth()->user()->image_url }}" 
                                        alt="{{ auth()->user()->name }}"
                                        class="h-8 w-8 rounded-full object-cover"
                                    >
                                @else
                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                            <div class="ml-3">
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors duration-200" title="Cerrar Sesión">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript for mobile sidebar and user dropdown -->
    <script>
        function toggleMobileSidebar() {
            const overlay = document.getElementById('mobile-sidebar-overlay');
            overlay.classList.toggle('hidden');
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Cerrar dropdown cuando se hace clic fuera de él
        document.addEventListener('click', function(event) {
            const userButton = document.getElementById('user-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (!userButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html> 