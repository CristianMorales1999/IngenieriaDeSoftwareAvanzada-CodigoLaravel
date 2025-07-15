@extends('layouts.app')

@section('title', 'Inicio')
@section('description', 'ServiPro - Tu plataforma confiable para encontrar y ofrecer servicios profesionales de calidad')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content animate-fade-in">
            <h1 class="hero-title">
                Encuentra los mejores 
                <span class="text-yellow-300">servicios profesionales</span>
            </h1>
            <p class="hero-subtitle">
                Conectamos talento con oportunidades. Miles de profesionales verificados 
                listos para ayudarte con cualquier proyecto.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#servicios" class="btn btn-primary text-lg px-8 py-4">
                    <i class="fas fa-search mr-2"></i>
                    Explorar Servicios
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline text-lg px-8 py-4 bg-white text-blue-600 hover:bg-blue-50">
                    <i class="fas fa-user-plus mr-2"></i>
                    Registrarse
                </a>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-300 mb-2">500+</div>
                <div class="text-blue-100">Profesionales</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-300 mb-2">1000+</div>
                <div class="text-blue-100">Servicios</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-300 mb-2">98%</div>
                <div class="text-blue-100">Satisfacción</div>
            </div>
        </div>
    </section>

    <!-- Nosotros Section -->
    <section id="nosotros" class="section bg-white">
        <div class="container-custom">
            <div class="text-center mb-16">
                <h2 class="section-title">Sobre Nosotros</h2>
                <p class="section-subtitle">
                    Somos una plataforma innovadora que conecta profesionales talentosos 
                    con clientes que necesitan servicios de calidad.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Contenido -->
                <div class="space-y-6 animate-fade-in-up">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        Tu confianza es nuestra prioridad
                    </h3>
                    <p class="text-gray-600 mb-6">
                        En ServiPro, creemos que cada proyecto merece la mejor atención y profesionalismo. 
                        Nuestra plataforma está diseñada para facilitar la conexión entre profesionales 
                        verificados y clientes que buscan servicios de calidad.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Profesionales Verificados</h4>
                                <p class="text-gray-600 text-sm">Todos nuestros profesionales pasan por un riguroso proceso de verificación.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Garantía de Calidad</h4>
                                <p class="text-gray-600 text-sm">Ofrecemos garantía en todos los servicios para tu tranquilidad.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Soporte 24/7</h4>
                                <p class="text-gray-600 text-sm">Nuestro equipo de soporte está disponible para ayudarte en cualquier momento.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Imagen -->
                <div class="relative animate-slide-in-right">
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl p-8">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-4 shadow-md">
                                <i class="fas fa-tools text-3xl text-blue-600 mb-2"></i>
                                <h4 class="font-semibold text-gray-900">Mantenimiento</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-md">
                                <i class="fas fa-paint-brush text-3xl text-green-600 mb-2"></i>
                                <h4 class="font-semibold text-gray-900">Limpieza</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-md">
                                <i class="fas fa-wrench text-3xl text-purple-600 mb-2"></i>
                                <h4 class="font-semibold text-gray-900">Reparaciones</h4>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-md">
                                <i class="fas fa-leaf text-3xl text-orange-600 mb-2"></i>
                                <h4 class="font-semibold text-gray-900">Jardinería</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios" class="section bg-gray-50">
        <div class="container-custom">
            <div class="text-center mb-16">
                <h2 class="section-title">Nuestros Servicios</h2>
                <p class="section-subtitle">
                    Descubre una amplia gama de servicios profesionales 
                    para satisfacer todas tus necesidades.
                </p>
            </div>

            <!-- Servicios destacados -->
            <div class="services-grid mb-12">
                @forelse($featuredServices as $service)
                    <x-service-card :service="$service" />
                @empty
                    <!-- Placeholder cards cuando no hay servicios -->
                    @for($i = 0; $i < 6; $i++)
                        <div class="card animate-pulse">
                            <div class="h-48 bg-gray-200 rounded-t-xl"></div>
                            <div class="card-body">
                                <div class="h-6 bg-gray-200 rounded mb-2"></div>
                                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                                <div class="h-4 bg-gray-200 rounded mb-4"></div>
                                <div class="h-10 bg-gray-200 rounded"></div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>

            <!-- Botón ver todos -->
            <div class="text-center">
                <a href="{{ route('services.index') }}" class="btn btn-primary text-lg px-8 py-4">
                    <i class="fas fa-th-large mr-2"></i>
                    Ver Todos los Servicios
                </a>
            </div>
        </div>
    </section>

    <!-- Contacto Section -->
    <section id="contacto" class="section bg-white">
        <div class="container-custom">
            <div class="text-center mb-16">
                <h2 class="section-title">Contáctanos</h2>
                <p class="section-subtitle">
                    ¿Tienes alguna pregunta o necesitas ayuda? 
                    Estamos aquí para ayudarte.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Información de contacto -->
                <div class="space-y-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">
                            Información de Contacto
                        </h3>
                        <p class="text-gray-600 mb-8">
                            Nuestro equipo está disponible para responder tus consultas 
                            y brindarte el mejor servicio posible.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Dirección</h4>
                                <p class="text-gray-600">Av. Principal 123, Ciudad, País 12345</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Teléfono</h4>
                                <p class="text-gray-600">+1 (234) 567-890</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Email</h4>
                                <p class="text-gray-600">info@servipro.com</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-orange-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Horarios</h4>
                                <p class="text-gray-600">Lun - Vie: 8:00 - 18:00<br>Sáb: 9:00 - 14:00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Redes sociales -->
                    <div class="pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-4">Síguenos</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700 transition-colors duration-200">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white hover:bg-blue-500 transition-colors duration-200">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-pink-600 rounded-lg flex items-center justify-center text-white hover:bg-pink-700 transition-colors duration-200">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white hover:bg-blue-800 transition-colors duration-200">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Formulario de contacto -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-xl font-semibold text-gray-900">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>
                            Envíanos un mensaje
                        </h3>
                    </div>
                    <div class="card-body">
                        <x-contact-form />
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
// Animaciones al hacer scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in-up');
        }
    });
}, observerOptions);

// Observar elementos para animación
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.services-grid > div, .space-y-6, .space-y-8');
    animatedElements.forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush