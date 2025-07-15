@extends('layouts.app')

@section('title', 'Editar Perfil')
@section('description', 'Edita tu información de perfil')

@section('content')
<div class="container-custom py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Perfil</h1>
            <p class="text-gray-600">Actualiza tu información personal</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información del perfil -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-xl font-semibold text-gray-900">Información Personal</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="name" class="form-label">Nombre</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', auth()->user()->name) }}" 
                                    class="form-input @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', auth()->user()->email) }}" 
                                    class="form-input @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="mobile" class="form-label">Teléfono</label>
                                <input 
                                    type="tel" 
                                    id="mobile" 
                                    name="mobile" 
                                    value="{{ old('mobile', auth()->user()->mobile) }}" 
                                    class="form-input @error('mobile') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                >
                                @error('mobile')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address" class="form-label">Dirección</label>
                                <textarea 
                                    id="address" 
                                    name="address" 
                                    rows="3" 
                                    class="form-textarea @error('address') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                >{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image" class="form-label">Imagen de Perfil</label>
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/*"
                                    class="form-input @error('image') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                >
                                <p class="mt-1 text-sm text-gray-500">
                                    Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                                </p>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cambiar contraseña -->
                <div class="card mt-6">
                    <div class="card-header">
                        <h2 class="text-xl font-semibold text-gray-900">Cambiar Contraseña</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                <input 
                                    type="password" 
                                    id="current_password" 
                                    name="current_password" 
                                    class="form-input @error('current_password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required
                                >
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required
                                >
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-input"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-key mr-2"></i>
                                    Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Vista previa del perfil -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-xl font-semibold text-gray-900">Vista Previa</h2>
                    </div>
                    <div class="card-body text-center">
                        <!-- Avatar -->
                        <div class="mb-4">
                            @if(auth()->user()->hasValidImage())
                                <img 
                                    src="{{ auth()->user()->image_url }}" 
                                    alt="{{ auth()->user()->name }}"
                                    class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-gray-200"
                                >
                            @else
                                <div class="w-24 h-24 rounded-full bg-blue-500 text-white text-2xl font-bold flex items-center justify-center mx-auto border-4 border-gray-200">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Información del usuario -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                            {{ auth()->user()->name }}
                        </h3>
                        <p class="text-gray-600 mb-4">{{ auth()->user()->email }}</p>

                        @if(auth()->user()->hasMobile())
                            <p class="text-sm text-gray-500 mb-2">
                                <i class="fas fa-phone mr-1"></i>
                                {{ auth()->user()->mobile }}
                            </p>
                        @endif

                        @if(auth()->user()->hasAddress())
                            <p class="text-sm text-gray-500 mb-4">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ auth()->user()->address }}
                            </p>
                        @endif

                        <!-- Estadísticas -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ auth()->user()->services()->count() }}</div>
                                    <div class="text-gray-500">Servicios</div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ auth()->user()->created_at->diffForHumans() }}</div>
                                    <div class="text-gray-500">Miembro desde</div>
                                </div>
                            </div>
                        </div>

                        <!-- Eliminar imagen -->
                        @if(auth()->user()->hasValidImage())
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <form method="POST" action="{{ route('profile.delete-image') }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar tu imagen de perfil?')">
                                        <i class="fas fa-trash mr-1"></i>
                                        Eliminar Imagen
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 