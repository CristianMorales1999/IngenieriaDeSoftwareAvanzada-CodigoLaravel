@php
    $isAuthPage = request()->routeIs('login') || request()->routeIs('register');
@endphp

<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($isAuthPage)
            <!-- Footer reducido para páginas de autenticación -->
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cogs text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-bold text-white">ServiPro</span>
                </div>
                <p class="text-gray-300 mb-4">
                    Tu plataforma confiable para encontrar y ofrecer servicios profesionales de calidad.
                </p>
                <div class="border-t border-gray-800 pt-4">
                    <p class="text-gray-400 text-sm">
                        © {{ date('Y') }} ServiPro. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        @else
            <!-- Footer completo para otras páginas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo y descripción -->
                <div class="mb-8 md:mb-0">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cogs text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-white">ServiPro</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Tu plataforma confiable para encontrar y ofrecer servicios profesionales de calidad. 
                        Conectamos talento con oportunidades.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Enlaces rápidos -->
                <div class="mb-8 md:mb-0">
                    <h3 class="text-lg font-semibold mb-4">Enlaces Rápidos</h3>
                    <a href="{{ route('home') }}#home" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2">
                        <i class="fas fa-home mr-2"></i>
                        Inicio
                    </a>
                    <a href="{{ route('home') }}#nosotros" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2">
                        <i class="fas fa-users mr-2"></i>
                        Nosotros
                    </a>
                    <a href="{{ route('services.index') }}" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2">
                        <i class="fas fa-briefcase mr-2"></i>
                        Servicios
                    </a>
                    <a href="{{ route('home') }}#contacto" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2">
                        <i class="fas fa-envelope mr-2"></i>
                        Contacto
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2">
                            <i class="fas fa-th-large mr-2"></i>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Acceder
                        </a>
                        <a href="{{ route('register') }}" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2">
                            <i class="fas fa-user-plus mr-2"></i>
                            Registrarse
                        </a>
                    @endauth
                </div>

                <!-- Servicios por Categoría -->
                <div class="mb-8 md:mb-0">
                    <h3 class="text-lg font-semibold mb-4">Categorías Populares</h3>
                    @foreach(['Desarrollo Web', 'Diseño Gráfico', 'Marketing Digital', 'Consultoría', 'Educación'] as $category)
                        <a href="{{ route('services.index', ['category' => $category]) }}" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2">
                            <i class="fas fa-chevron-right mr-2 text-xs"></i>
                            {{ $category }}
                        </a>
                    @endforeach
                    <a href="{{ route('services.index') }}" class="block text-gray-300 hover:text-white transition-colors duration-200 mb-2 font-semibold">
                        <i class="fas fa-th-large mr-2"></i>
                        Ver Todas
                    </a>
                </div>

                <!-- Contacto -->
                <div class="mb-8 md:mb-0">
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-blue-400 mt-1"></i>
                            <div>
                                <p class="text-gray-300 text-sm">
                                    Av. Principal 123<br>
                                    Ciudad, País 12345
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-blue-400"></i>
                            <a href="tel:+1234567890" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">
                                +1 (234) 567-890
                            </a>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-blue-400"></i>
                            <a href="mailto:info@servipro.com" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">
                                info@servipro.com
                            </a>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-blue-400"></i>
                            <p class="text-gray-300 text-sm">
                                Lun - Vie: 8:00 - 18:00<br>
                                Sáb: 9:00 - 14:00
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Línea divisoria -->
            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © {{ date('Y') }} ServiPro. Todos los derechos reservados.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">
                            Política de Privacidad
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">
                            Términos de Servicio
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">
                            Cookies
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</footer> 