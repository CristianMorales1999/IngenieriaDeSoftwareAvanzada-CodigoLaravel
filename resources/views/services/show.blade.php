@extends('layouts.app')

@section('title', $service->title)
@section('description', Str::limit($service->description, 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Inicio
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('services.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Servicios
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">{{ $service->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información principal del servicio -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
                <!-- Imagen del servicio -->
                <div class="aspect-w-16 aspect-h-9">
                    @if($service->image_path)
                        <img src="{{ asset('storage/' . $service->image_path) }}" 
                             alt="{{ $service->title }}" 
                             class="w-full h-96 object-cover rounded-t-xl">
                    @else
                        <div class="w-full h-96 bg-gray-200 rounded-t-xl flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-6xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Información del servicio -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $service->category }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $service->created_at->format('d/m/Y') }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $service->title }}</h1>
                    
                    <div class="prose max-w-none text-gray-600 mb-6">
                        <p class="text-lg leading-relaxed">{{ $service->description }}</p>
                    </div>

                    @if($service->location)
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                            <span>{{ $service->location }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar con información del proveedor y precio -->
        <div class="lg:col-span-1">
            <!-- Card de precio y contacto -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-blue-600 mb-2">${{ number_format($service->price, 0, ',', '.') }}</div>
                        <p class="text-gray-600">Precio del servicio</p>
                    </div>

                    @auth
                        @if(auth()->id() === $service->user_id)
                            <!-- Botones para el propietario del servicio -->
                            <div class="space-y-3">
                                <a href="{{ route('services.edit', $service) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                    <i class="fas fa-edit mr-2"></i>
                                    Editar Servicio
                                </a>
                                <form method="POST" action="{{ route('services.destroy', $service) }}" class="inline w-full" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este servicio?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                        <i class="fas fa-trash mr-2"></i>
                                        Eliminar Servicio
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Botón de contacto para otros usuarios -->
                            <button class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                                <i class="fas fa-envelope mr-2"></i>
                                Contactar Proveedor
                            </button>
                        @endif
                    @else
                        <!-- Botón para usuarios no autenticados -->
                        <a href="{{ route('login') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Iniciar Sesión para Contactar
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Información del proveedor -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Sobre el Proveedor</h3>
                    
                    <div class="flex items-center space-x-3 mb-4">
                        @if($service->user->hasValidImage())
                            <img 
                                src="{{ $service->user->image_url }}" 
                                alt="{{ $service->user->name }}"
                                class="w-12 h-12 rounded-full object-cover"
                            >
                        @else
                            <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-lg font-medium">
                                {{ strtoupper(substr($service->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">{{ $service->user->name }}</h4>
                            <p class="text-xs text-gray-500">Miembro desde {{ $service->user->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600">
                        @if($service->user->hasMobile())
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>
                                <span>{{ $service->user->mobile }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>
                            <span>{{ $service->user->email }}</span>
                        </div>

                        @if($service->user->hasAddress())
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500 mt-0.5"></i>
                                <span>{{ $service->user->address }}</span>
                            </div>
                        @endif
                    </div>

                    @auth
                        @if(auth()->id() !== $service->user_id)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('profile.edit') }}" 
                                   class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-user mr-2"></i>
                                    Ver Perfil Completo
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Servicios relacionados -->
    @if($service->user->services()->where('id', '!=', $service->id)->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Otros servicios de {{ $service->user->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($service->user->services()->where('id', '!=', $service->id)->take(3)->get() as $relatedService)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
                        <!-- Imagen del servicio -->
                        <div class="aspect-w-16 aspect-h-9">
                            @if($relatedService->image_path)
                                <img src="{{ asset('storage/' . $relatedService->image_path) }}" 
                                     alt="{{ $relatedService->title }}" 
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
                                    {{ $relatedService->category }}
                                </span>
                                <span class="text-lg font-bold text-blue-600">${{ number_format($relatedService->price, 0, ',', '.') }}</span>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $relatedService->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($relatedService->description, 100) }}</p>

                            <a href="{{ route('services.show', $relatedService) }}" 
                               class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 w-full">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Servicio
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection 