<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40 backdrop-blur-custom">
    <div class="container-custom">
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
                <a href="#home" class="nav-link">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
                <a href="#nosotros" class="nav-link">
                    <i class="fas fa-users mr-2"></i>
                    Nosotros
                </a>
                <a href="#servicios" class="nav-link">
                    <i class="fas fa-briefcase mr-2"></i>
                    Servicios
                </a>
                <a href="#contacto" class="nav-link">
                    <i class="fas fa-envelope mr-2"></i>
                    Contacto
                </a>
            </nav>

            <!-- User Menu / Auth Buttons -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Dropdown -->
                    <div class="dropdown">
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
                                    <div class="avatar-sm bg-blue-500 text-white">
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
                            class="dropdown-menu hidden"
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
                                        <div class="avatar-lg bg-blue-500 text-white">
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
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user mr-3 text-gray-400"></i>
                                Mi Perfil
                            </a>
                            <a href="{{ route('home') }}" class="dropdown-item nav-link-active">
                                <i class="fas fa-home mr-3 text-gray-400"></i>
                                Página Principal
                            </a>
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <i class="fas fa-th-large mr-3 text-gray-400"></i>
                                Inicio
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="dropdown-item dropdown-item-danger w-full text-left">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Auth Buttons -->
                    <a href="{{ route('login') }}" class="btn btn-outline">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Acceder
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
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
                <a href="#home" class="nav-link block">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
                <a href="#nosotros" class="nav-link block">
                    <i class="fas fa-users mr-2"></i>
                    Nosotros
                </a>
                <a href="#servicios" class="nav-link block">
                    <i class="fas fa-briefcase mr-2"></i>
                    Servicios
                </a>
                <a href="#contacto" class="nav-link block">
                    <i class="fas fa-envelope mr-2"></i>
                    Contacto
                </a>
                
                @guest
                    <div class="pt-4 space-y-2">
                        <a href="{{ route('login') }}" class="btn btn-outline w-full justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Acceder
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Registrarse
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</header> 