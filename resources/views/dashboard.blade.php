@extends('layouts.app')

@section('title', 'Dashboard')
@section('description', 'Panel de control de ServiPro')

@section('content')
<div class="container-custom py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Bienvenido a tu panel de control</p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">0</div>
                <div class="text-gray-600">Total de Servicios</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">0</div>
                <div class="text-gray-600">Servicios Recientes</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">0</div>
                <div class="text-gray-600">Con Imágenes</div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="card mb-8">
        <div class="card-header">
            <h2 class="text-xl font-semibold text-gray-900">Acciones Rápidas</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('services.create') }}" class="btn btn-primary w-full">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Servicio
                </a>
                <a href="{{ route('services.my') }}" class="btn btn-outline w-full">
                    <i class="fas fa-list mr-2"></i>
                    Mis Servicios
                </a>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline w-full">
                    <i class="fas fa-user-edit mr-2"></i>
                    Editar Perfil
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline w-full">
                    <i class="fas fa-home mr-2"></i>
                    Ir al Inicio
                </a>
            </div>
        </div>
    </div>

    <!-- Servicios recientes -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-xl font-semibold text-gray-900">Mis Servicios Recientes</h2>
        </div>
        <div class="card-body">
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-briefcase text-4xl mb-4"></i>
                <p>No tienes servicios creados aún.</p>
                <a href="{{ route('services.create') }}" class="btn btn-primary mt-4">
                    <i class="fas fa-plus mr-2"></i>
                    Crear mi primer servicio
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 