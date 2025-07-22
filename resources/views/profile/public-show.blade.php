@extends('layouts.app')

@section('title', 'Perfil de ' . $user->name)
@section('description', 'Explora el perfil y los servicios de ' . $user->name . ' en ServiPro.')

@section('content')
<div class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Cabecera del Perfil -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex flex-col md:flex-row items-center md:space-x-8">
                    <!-- Avatar -->
                    <div class="flex-shrink-0 mb-6 md:mb-0">
                        @if($user->hasValidImage())
                            <img src="{{ $user->image_url }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full object-cover border-4 border-blue-200 shadow-lg">
                        @else
                            <div class="h-32 w-32 rounded-full bg-blue-500 text-white text-5xl font-bold flex items-center justify-center border-4 border-blue-200 shadow-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <!-- Info -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-4xl font-extrabold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-gray-600 mt-1">Miembro en ServiPro desde {{ $user->created_at->format('F Y') }}</p>
                        
                        <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-4 text-gray-600">
                            @if($user->mobile)
                                <span class="inline-flex items-center">
                                    <i class="fas fa-phone-alt mr-2 text-blue-500"></i> {{ $user->mobile }}
                                </span>
                            @endif
                            @if($user->address)
                                <span class="inline-flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i> {{ $user->address }}
                                </span>
                            @endif
                        </div>
                        @if($user->email)
                                <span class="inline-flex items-center">
                                    <i class="fas fa-envelope mr-2 text-blue-500"></i> {{ $user->email }}
                                </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Servicios del Usuario -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Servicios Ofrecidos</h2>
            @if($user->services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($user->services as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                    <i class="fas fa-briefcase text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Aún no hay servicios</h3>
                    <p class="text-gray-500 mt-2">{{ $user->name }} todavía no ha publicado ningún servicio.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 