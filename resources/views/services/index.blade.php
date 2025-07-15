@extends('layouts.app')

@section('title', 'Servicios')
@section('description', 'Explora todos los servicios disponibles en ServiPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Servicios Disponibles</h1>
        <p class="text-gray-600">Encuentra profesionales expertos para tus proyectos</p>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 mb-8">
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('services.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Búsqueda -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar servicios</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           placeholder="Buscar por título, descripción o proveedor">
                </div>

                <!-- Categoría -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select id="category" name="category" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">Todas las categorías</option>
                        <option value="Desarrollo Web" {{ request('category') == 'Desarrollo Web' ? 'selected' : '' }}>Desarrollo Web</option>
                        <option value="Diseño Gráfico" {{ request('category') == 'Diseño Gráfico' ? 'selected' : '' }}>Diseño Gráfico</option>
                        <option value="Marketing Digital" {{ request('category') == 'Marketing Digital' ? 'selected' : '' }}>Marketing Digital</option>
                        <option value="Consultoría" {{ request('category') == 'Consultoría' ? 'selected' : '' }}>Consultoría</option>
                        <option value="Educación" {{ request('category') == 'Educación' ? 'selected' : '' }}>Educación</option>
                        <option value="Otros" {{ request('category') == 'Otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                </div>

                <!-- Ordenar por -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                    <select id="sort" name="sort" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio: menor a mayor</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio: mayor a menor</option>
                    </select>
                </div>

                <!-- Botón de búsqueda -->
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    @if($services->count() > 0)
        <!-- Estadísticas -->
        <div class="mb-6">
            <p class="text-gray-600">
                Mostrando {{ $services->firstItem() }}-{{ $services->lastItem() }} de {{ $services->total() }} servicios
            </p>
        </div>

        <!-- Grid de servicios -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
                    <!-- Imagen del servicio -->
                    <div class="aspect-w-16 aspect-h-9">
                        @if($service->image_path)
                            <img src="{{ asset('storage/' . $service->image_path) }}" 
                                 alt="{{ $service->title }}" 
                                 class="w-full h-48 object-cover rounded-t-xl">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-t-xl flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Información del servicio -->
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $service->category }}
                            </span>
                            <span class="text-lg font-bold text-blue-600">${{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->title }}</h3>
                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($service->description, 120) }}</p>

                        <!-- Información del proveedor -->
                        <div class="flex items-center space-x-2 mb-3">
                            @if($service->user->hasValidImage())
                                <img 
                                    src="{{ $service->user->image_url }}" 
                                    alt="{{ $service->user->name }}"
                                    class="w-6 h-6 rounded-full object-cover"
                                >
                            @else
                                <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium">
                                    {{ strtoupper(substr($service->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-sm text-gray-600">{{ $service->user->name }}</span>
                        </div>

                        <!-- Ubicación y fecha -->
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                            @if($service->location)
                                <span class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $service->location }}
                                </span>
                            @endif
                            <span>{{ $service->created_at->format('d/m/Y') }}</span>
                        </div>

                        <!-- Acciones -->
                        <div class="flex space-x-2">
                            <a href="{{ route('services.show', $service) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Detalles
                            </a>
                            @auth
                                @if(auth()->id() !== $service->user_id)
                                    <button class="inline-flex items-center justify-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                    <i class="fas fa-sign-in-alt"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-8">
            {{ $services->links() }}
        </div>
    @else
        <!-- Estado vacío -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
            <div class="px-6 py-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-search text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron servicios</h3>
                <p class="text-gray-600 mb-6">
                    @if(request('search') || request('category'))
                        No hay servicios que coincidan con tu búsqueda. Intenta con otros términos o categorías.
                    @else
                        Aún no hay servicios disponibles. ¡Sé el primero en crear uno!
                    @endif
                </p>
                @auth
                    <a href="{{ route('services.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                        <i class="fas fa-plus mr-2"></i>
                        Crear mi primer servicio
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                        <i class="fas fa-user-plus mr-2"></i>
                        Registrarse para crear servicios
                    </a>
                @endauth
            </div>
        </div>
    @endif
</div>
@endsection 