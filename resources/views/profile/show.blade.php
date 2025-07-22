@extends('layouts.dashboard')

@section('title', 'Mi Perfil')
@section('description', 'Visualiza y gestiona tu perfil en ServiPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
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
                        <span class="text-sm font-medium text-gray-500">Mi Perfil</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Cabecera del Perfil -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 mb-8">
            <div class="p-6">
                <div class="flex items-center space-x-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @if($user->hasValidImage())
                            <img src="{{ $user->image_url }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full object-cover border-4 border-gray-200">
                        @else
                            <div class="h-24 w-24 rounded-full bg-blue-500 text-white text-3xl font-bold flex items-center justify-center border-4 border-gray-200">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <!-- Info y Acciones -->
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="mt-4 flex space-x-4">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
                                <i class="fas fa-pencil-alt mr-2"></i>
                                Editar Perfil
                            </a>
                            <a href="{{ route('services.my') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                                <i class="fas fa-briefcase mr-2"></i>
                                Mis Servicios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles del Perfil -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Columna Principal -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">Detalles de la Cuenta</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Nombre Completo</span>
                            <p class="text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div class="border-t border-gray-100"></div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Correo Electrónico</span>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div class="border-t border-gray-100"></div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Teléfono</span>
                            <p class="text-gray-900">{{ $user->mobile ?? 'No especificado' }}</p>
                        </div>
                        <div class="border-t border-gray-100"></div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Dirección</span>
                            <p class="text-gray-900">{{ $user->address ?? 'No especificada' }}</p>
                        </div>
                        <div class="border-t border-gray-100"></div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Miembro desde</span>
                            <p class="text-gray-900">{{ $user->created_at->format('d \d\e F \d\e Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Lateral -->
            <div class="md:col-span-1">
                <!-- Estadísticas -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Estadísticas</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Servicios Creados</span>
                            <span class="font-bold text-blue-600">{{ $user->services()->count() }}</span>
                        </div>
                    </div>
                </div>
                <!-- Seguridad -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100">
                     <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Seguridad</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">
                            Actualiza tu contraseña y gestiona la seguridad de tu cuenta.
                        </p>
                        <a href="{{ route('profile.edit') }}#security" class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                            Gestionar Credenciales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 