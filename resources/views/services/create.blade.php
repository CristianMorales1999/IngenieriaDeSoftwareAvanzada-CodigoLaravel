@extends('layouts.dashboard')

@section('title', 'Crear Servicio')
@section('description', 'Crea un nuevo servicio en ServiPro')

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
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('services.my') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                    Mis Servicios
                </a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-500">Crear Servicio</span>
            </div>
        </li>
    </ol>
</nav>

<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Crear Nuevo Servicio</h1>
            <p class="text-gray-600">Comparte tus habilidades y servicios con la comunidad</p>
        </div>
        <a href="{{ route('services.my') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-white text-gray-700 hover:bg-gray-50 focus:ring-blue-500 shadow-sm hover:shadow-md">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Mis Servicios
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-xl font-semibold text-gray-900">Información del Servicio</h2>
    </div>
    
    <div class="px-6 py-6">
        <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título del servicio -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Título del Servicio *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('title') border-red-500 @enderror" 
                           placeholder="Ej: Desarrollo de sitios web profesionales" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                    <select id="category" name="category" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('category') border-red-500 @enderror" required>
                        <option value="">Selecciona una categoría</option>
                        <option value="Desarrollo Web" {{ old('category') == 'Desarrollo Web' ? 'selected' : '' }}>Desarrollo Web</option>
                        <option value="Diseño Gráfico" {{ old('category') == 'Diseño Gráfico' ? 'selected' : '' }}>Diseño Gráfico</option>
                        <option value="Marketing Digital" {{ old('category') == 'Marketing Digital' ? 'selected' : '' }}>Marketing Digital</option>
                        <option value="Consultoría" {{ old('category') == 'Consultoría' ? 'selected' : '' }}>Consultoría</option>
                        <option value="Educación" {{ old('category') == 'Educación' ? 'selected' : '' }}>Educación</option>
                        <option value="Otros" {{ old('category') == 'Otros' ? 'selected' : '' }}>Otros</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Precio -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Precio (USD) *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01"
                               class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('price') border-red-500 @enderror" 
                               placeholder="0.00" required>
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                    <textarea id="description" name="description" rows="4" 
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('description') border-red-500 @enderror" 
                              placeholder="Describe detalladamente tu servicio, incluyendo lo que incluye, tiempo de entrega, etc." required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Imagen del servicio -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Imagen del Servicio</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-4"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Subir una imagen</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">o arrastrar y soltar</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ubicación -->
                <div class="md:col-span-2">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('location') border-red-500 @enderror" 
                           placeholder="Ej: Madrid, España o Remoto">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('services.my') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-white text-gray-700 hover:bg-gray-50 focus:ring-blue-500 shadow-sm hover:shadow-md">
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md">
                    <i class="fas fa-save mr-2"></i>
                    Crear Servicio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const uploadArea = document.querySelector('.border-dashed');
    
    // Previsualización de imagen
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Crear elemento de imagen para previsualización
                let preview = uploadArea.querySelector('.image-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.className = 'image-preview mt-4 max-w-xs mx-auto rounded-lg shadow-md';
                    uploadArea.appendChild(preview);
                }
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                // Ocultar icono y texto
                const icon = uploadArea.querySelector('.fas');
                const text = uploadArea.querySelector('.text-sm');
                if (icon) icon.style.display = 'none';
                if (text) text.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-blue-400', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            imageInput.dispatchEvent(new Event('change'));
        }
    });
});
</script>
@endpush 