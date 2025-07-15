@extends('layouts.app')

@section('title', 'Dashboard - ServiPro')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header del Dashboard -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-600 mt-1">Bienvenido a tu panel de control</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('services.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Servicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-briefcase text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Mis Servicios
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ auth()->user()->services()->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('services.my') }}" class="font-medium text-blue-700 hover:text-blue-900">
                            Ver todos
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-eye text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Servicios Activos
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ auth()->user()->services()->where('is_active', true)->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('services.my') }}" class="font-medium text-green-700 hover:text-green-900">
                            Gestionar
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Mi Perfil
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    Actualizado
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('profile.edit') }}" class="font-medium text-purple-700 hover:text-purple-900">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('services.create') }}" class="group relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-plus text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900">Crear Servicio</p>
                            <p class="text-sm text-gray-500">Añadir un nuevo servicio</p>
                        </div>
                    </a>

                    <a href="{{ route('services.my') }}" class="group relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-list text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900">Mis Servicios</p>
                            <p class="text-sm text-gray-500">Gestionar mis servicios</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="group relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-edit text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900">Editar Perfil</p>
                            <p class="text-sm text-gray-500">Actualizar información</p>
                        </div>
                    </a>

                    <a href="{{ route('home') }}" class="group relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-home text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900">Página Principal</p>
                            <p class="text-sm text-gray-500">Volver al inicio</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Servicios Recientes -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Mis Servicios Recientes</h3>
            </div>
            <div class="p-6">
                @php
                    $recentServices = auth()->user()->services()->latest()->take(5)->get();
                @endphp
                
                @if($recentServices->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentServices as $service)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center space-x-4">
                                    @if($service->image_path)
                                        <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $service->title }}</h4>
                                        <p class="text-sm text-gray-500">{{ Str::limit($service->description, 50) }}</p>
                                        <p class="text-xs text-gray-400">{{ $service->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $service->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <a href="{{ route('services.edit', $service) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('services.my') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Ver todos mis servicios
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-briefcase text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes servicios aún</h3>
                        <p class="text-gray-500 mb-4">Comienza creando tu primer servicio para mostrar tus habilidades</p>
                        <a href="{{ route('services.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i>
                            Crear mi primer servicio
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
