@extends('layouts.dashboard')

@section('title', 'Mis Servicios')
@section('description', 'Gestiona tus servicios en ServiPro')

@section('content')
<!-- Breadcrumb -->
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <i class="fas fa-th-large mr-2"></i>
                Dashboard
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-500">Mis Servicios</span>
            </div>
        </li>
    </ol>
</nav>

<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mis Servicios</h1>
            <p class="text-gray-600">Gestiona todos tus servicios publicados</p>
        </div>
        <a href="{{ route('services.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Crear Nuevo Servicio
        </a>
    </div>
</div>

@if($services->count() > 0)
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
            <div class="px-6 py-4 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $services->count() }}</div>
                <div class="text-gray-600">Total de Servicios</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
            <div class="px-6 py-4 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $services->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                <div class="text-gray-600">Servicios Recientes</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
            <div class="px-6 py-4 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $services->whereNotNull('image_path')->count() }}</div>
                <div class="text-gray-600">Con Imágenes</div>
            </div>
        </div>
    </div>

    <!-- Lista de Servicios -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">Todos mis servicios</h2>
        </div>
        <div class="overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($services as $service)
                    <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                        <!-- Imagen del servicio -->
                        <div class="aspect-w-16 aspect-h-9 mb-4">
                            @if($service->image_path)
                                <img src="{{ asset('storage/' . $service->image_path) }}" 
                                     alt="{{ $service->title }}" 
                                     class="w-full h-48 object-cover rounded-lg">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Información del servicio -->
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->title }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($service->description, 100) }}</p>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $service->category }}</span>
                                <span>{{ $service->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <!-- Precio -->
                        <div class="mb-4">
                            <span class="text-2xl font-bold text-blue-600">${{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Acciones -->
                        <div class="flex space-x-2">
                            <a href="{{ route('services.show', $service) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Ver
                            </a>
                            <a href="{{ route('services.edit', $service) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('services.destroy', $service) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este servicio?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center justify-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Paginación -->
    @if($services->hasPages())
        <div class="mt-8">
            {{ $services->links() }}
        </div>
    @endif
@else
    <!-- Estado vacío -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
        <div class="px-6 py-12 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-briefcase text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes servicios creados</h3>
            <p class="text-gray-600 mb-6">Comienza creando tu primer servicio para mostrar tus habilidades</p>
            <a href="{{ route('services.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                <i class="fas fa-plus mr-2"></i>
                Crear mi primer servicio
            </a>
        </div>
    </div>
@endif
@endsection 