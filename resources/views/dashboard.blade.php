@extends('layouts.app')

@section('title', 'Dashboard')
@section('description', 'Panel de control de ServiPro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Bienvenido a tu panel de control</p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
            <div class="px-6 py-4 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">0</div>
                <div class="text-gray-600">Total de Servicios</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
            <div class="px-6 py-4 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">0</div>
                <div class="text-gray-600">Servicios Recientes</div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
            <div class="px-6 py-4 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">0</div>
                <div class="text-gray-600">Con Imágenes</div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">Acciones Rápidas</h2>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('services.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md w-full">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Servicio
                </a>
                <a href="{{ route('services.my') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500 shadow-sm hover:shadow-md rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 w-full">
                    <i class="fas fa-list mr-2"></i>
                    Mis Servicios
                </a>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500 shadow-sm hover:shadow-md rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 w-full">
                    <i class="fas fa-user-edit mr-2"></i>
                    Editar Perfil
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500 shadow-sm hover:shadow-md rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 w-full">
                    <i class="fas fa-home mr-2"></i>
                    Ir al Inicio
                </a>
            </div>
        </div>
    </div>

    <!-- Servicios recientes -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">Mis Servicios Recientes</h2>
        </div>
        <div class="px-6 py-4">
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-briefcase text-4xl mb-4"></i>
                <p>No tienes servicios creados aún.</p>
                <a href="{{ route('services.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md mt-4">
                    <i class="fas fa-plus mr-2"></i>
                    Crear mi primer servicio
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 