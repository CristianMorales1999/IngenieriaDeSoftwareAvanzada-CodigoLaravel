<footer class="footer">
    <div class="footer-content">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Logo y descripción -->
            <div class="footer-section">
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
            <div class="footer-section">
                <h3 class="footer-title">Enlaces Rápidos</h3>
                <a href="#home" class="footer-link">
                    <i class="fas fa-home mr-2"></i>
                    Inicio
                </a>
                <a href="#nosotros" class="footer-link">
                    <i class="fas fa-users mr-2"></i>
                    Nosotros
                </a>
                <a href="#servicios" class="footer-link">
                    <i class="fas fa-briefcase mr-2"></i>
                    Servicios
                </a>
                <a href="#contacto" class="footer-link">
                    <i class="fas fa-envelope mr-2"></i>
                    Contacto
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="footer-link">
                        <i class="fas fa-th-large mr-2"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="footer-link">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Acceder
                    </a>
                    <a href="{{ route('register') }}" class="footer-link">
                        <i class="fas fa-user-plus mr-2"></i>
                        Registrarse
                    </a>
                @endauth
            </div>

            <!-- Servicios -->
            <div class="footer-section">
                <h3 class="footer-title">Nuestros Servicios</h3>
                <a href="#" class="footer-link">
                    <i class="fas fa-tools mr-2"></i>
                    Mantenimiento
                </a>
                <a href="#" class="footer-link">
                    <i class="fas fa-paint-brush mr-2"></i>
                    Limpieza
                </a>
                <a href="#" class="footer-link">
                    <i class="fas fa-wrench mr-2"></i>
                    Reparaciones
                </a>
                <a href="#" class="footer-link">
                    <i class="fas fa-leaf mr-2"></i>
                    Jardinería
                </a>
                <a href="#" class="footer-link">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Seguridad
                </a>
                <a href="#" class="footer-link">
                    <i class="fas fa-truck mr-2"></i>
                    Mudanzas
                </a>
            </div>

            <!-- Contacto -->
            <div class="footer-section">
                <h3 class="footer-title">Contacto</h3>
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
    </div>
</footer> 