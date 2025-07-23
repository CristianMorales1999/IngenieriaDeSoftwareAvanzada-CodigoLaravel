@extends('layouts.dashboard')

@section('title', 'Editar Perfil')
@section('description', 'Edita tu información de perfil')

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
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('profile.show') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            Mi Perfil
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Editar Perfil</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Editar Perfil</h1>
            <p class="text-gray-600">Actualiza tu información personal</p>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg border-l-4 bg-green-50 border-green-400 text-green-700 mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información del perfil -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">Información Personal</h2>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', auth()->user()->name) }}" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', auth()->user()->email) }}" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input 
                                    type="tel" 
                                    id="mobile" 
                                    name="mobile" 
                                    value="{{ old('mobile', auth()->user()->mobile) }}" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('mobile') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                >
                                @error('mobile')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                <textarea 
                                    id="address" 
                                    name="address" 
                                    rows="3" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('address') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                >{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sección de imagen de perfil mejorada -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Imagen de Perfil
                                </label>
                                
                                <!-- Contenedor de imagen actual/previsualización -->
                                <div class="flex items-center space-x-6">
                                    <div class="flex-shrink-0 relative">
                                        <!-- Imagen actual -->
                                        @if(auth()->user()->hasValidImage())
                                            <img id="current-image" 
                                                 src="{{ auth()->user()->image_url }}" 
                                                 class="w-32 h-32 rounded-full object-cover border-4 border-gray-200"
                                                 alt="Imagen actual">
                                        @else
                                            <div id="no-image-placeholder" 
                                                 class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-400 text-4xl"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Previsualización de nueva imagen -->
                                        <img id="image-preview" 
                                             class="w-32 h-32 rounded-full object-cover border-4 border-blue-200 hidden" 
                                             alt="Vista previa">
                                    </div>

                                    <div class="flex flex-col space-y-2">
                                        <!-- Input de archivo oculto -->
                                        <input type="file" 
                                               id="image" 
                                               name="image" 
                                               accept="image/*"
                                               class="hidden"
                                               onchange="handleImagePreview()">

                                        <!-- Botón personalizado para seleccionar imagen -->
                                        <button type="button" 
                                                onclick="document.getElementById('image').click()" 
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-camera mr-2"></i>
                                            {{ auth()->user()->hasValidImage() ? 'Cambiar foto' : 'Subir foto' }}
                                        </button>

                                        <!-- Botón para eliminar imagen -->
                                        @if(auth()->user()->hasValidImage())
                                            <button type="button" 
                                                    onclick="confirmDeleteImage()" 
                                                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <i class="fas fa-trash mr-2"></i>
                                                Eliminar foto
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Mensajes de error -->
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <!-- Información de formato -->
                                <p class="mt-2 text-sm text-gray-500">
                                    Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB
                                </p>
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cambiar contraseña -->
                <div id="security" class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100 mt-6 scroll-mt-24">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">Cambiar Contraseña</h2>
                    </div>
                    <div class="px-6 py-4">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                                <input 
                                    type="password" 
                                    id="current_password" 
                                    name="current_password" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('current_password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required
                                >
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required
                                >
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required
                                >
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500 shadow-sm hover:shadow-md">
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
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">Vista Previa</h2>
                    </div>
                    <div class="px-6 py-4 text-center">
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
                        <p class="text-gray-600 text-sm mb-4">{{ auth()->user()->email }}</p>

                        @if(auth()->user()->mobile)
                            <div class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-phone mr-2"></i>
                                {{ auth()->user()->mobile }}
                            </div>
                        @endif

                        @if(auth()->user()->address)
                            <div class="text-sm text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ auth()->user()->address }}
                            </div>
                        @endif

                        <!-- Botón para eliminar imagen -->
                        @if(auth()->user()->hasValidImage())
                            <form method="POST" action="{{ route('profile.delete-image') }}" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1 border border-red-300 text-red-700 bg-white hover:bg-red-50 focus:ring-red-500 shadow-sm hover:shadow-md rounded text-xs font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2" onclick="return confirm('¿Estás seguro de que quieres eliminar tu imagen de perfil?')">
                                    <i class="fas fa-trash mr-1"></i>
                                    Eliminar Imagen
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@push('scripts')
<script>
function handleImagePreview() {
    const input = document.getElementById('image');
    const preview = document.getElementById('image-preview');
    const previewContainer = document.getElementById('image-preview-container');
    const currentImage = document.getElementById('current-image');
    const noImagePlaceholder = document.getElementById('no-image-placeholder');

    input.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (currentImage) currentImage.classList.add('hidden');
                if (noImagePlaceholder) noImagePlaceholder.classList.add('hidden');
                previewContainer.classList.remove('hidden');
            }
            
            reader.readAsDataURL(file);
        }
    });
}

function confirmDeleteImage() {
    if (confirm('¿Estás seguro de que quieres eliminar tu imagen de perfil?')) {
        document.querySelector('form[action="{{ route('profile.delete-image') }}"]').submit();
    }
}
</script>
@endpush