<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40 backdrop-blur-custom">
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

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
                <a href="#nosotros" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-users mr-2"></i>
                    Nosotros
                </a>
                <a href="#servicios" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-briefcase mr-2"></i>
                    Servicios
                </a>
                <a href="#contacto" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-envelope mr-2"></i>
                    Contacto
                </a>
            </nav>

            <!-- User Menu / Auth Buttons -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Dropdown -->
                    <div class="relative inline-block">
                        <button 
                            id="user-button"
                            onclick="toggleUserDropdown()"
                            class="flex items-center space-x-3 p-2 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 transition-colors duration-200"
                        >
                            <!-- User Avatar -->
                            <div class="relative">
                                @if(auth()->user()->hasValidImage())
                                    <img 
                                        src="{{ auth()->user()->image_url }}" 
                                        alt="{{ auth()->user()->name }}"
                                        class="w-8 h-8 rounded-full object-cover"
                                    >
                                @else
                                    <div class="inline-block h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <!-- Online Status Dot -->
                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>
                            
                            <!-- User Info -->
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
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
                                    @if(auth()->user()->hasValidImage())
                                        <img 
                                            src="{{ auth()->user()->image_url }}" 
                                            alt="{{ auth()->user()->name }}"
                                            class="w-10 h-10 rounded-full object-cover"
                                        >
                                    @else
                                        <div class="inline-block h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-lg font-medium">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <a href="{{ route('profile.edit') }}" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-user mr-3 text-gray-400"></i>
                                Mi Perfil
                            </a>
                            <a href="{{ route('home') }}" class="block w-full text-left px-4 py-2 text-sm text-blue-600 bg-blue-50">
                                <i class="fas fa-home mr-3 text-gray-400"></i>
                                Página Principal
                            </a>
                            <a href="{{ route('dashboard') }}" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-th-large mr-3 text-gray-400"></i>
                                Inicio
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
                @else
                    <!-- Auth Buttons -->
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500 shadow-sm hover:shadow-md rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Acceder
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                        <i class="fas fa-user-plus mr-2"></i>
                        Registrarse
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button 
                    onclick="toggleMobileMenu()"
                    class="md:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
                >
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200">
                <a href="#home" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 block">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
                <a href="#nosotros" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 block">
                    <i class="fas fa-users mr-2"></i>
                    Nosotros
                </a>
                <a href="#servicios" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 block">
                    <i class="fas fa-briefcase mr-2"></i>
                    Servicios
                </a>
                <a href="#contacto" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 block">
                    <i class="fas fa-envelope mr-2"></i>
                    Contacto
                </a>
                
                @guest
                    <div class="pt-4 space-y-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500 shadow-sm hover:shadow-md rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 w-full justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Acceder
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md w-full justify-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Registrarse
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</header> 