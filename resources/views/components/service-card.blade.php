@props(['service'])

<div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 animate-fade-in-up group border border-gray-100">
    <!-- Imagen del servicio -->
    <div class="relative overflow-hidden rounded-t-xl">
        @if($service->has_image)
            <img 
                src="{{ $service->image_url }}" 
                alt="{{ $service->title }}"
                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
            >
        @else
            <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                <i class="fas fa-briefcase text-4xl text-blue-400"></i>
            </div>
        @endif
        
        <!-- Badge de estado -->
        @if($service->isRecent())
            <div class="absolute top-3 left-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-star mr-1"></i>
                    Nuevo
                </span>
            </div>
        @endif
        
        <!-- Overlay con botón de acción -->
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
            <a 
                href="{{ route('services.show', $service) }}" 
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-4 group-hover:translate-y-0"
            >
                <i class="fas fa-eye mr-2"></i>
                Ver Detalles
            </a>
        </div>
    </div>

    <!-- Contenido de la tarjeta -->
    <div class="px-6 py-4">
        <!-- Título del servicio -->
        <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-200">
            {{ $service->title }}
        </h3>
        
        <!-- Descripción -->
        <p class="text-gray-600 mb-4 line-clamp-3">
            {{ $service->excerpt }}
        </p>
        
        <!-- Información adicional -->
        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <i class="fas fa-user mr-1"></i>
                    {{ $service->creator_name }}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    {{ $service->time_ago }}
                </span>
            </div>
        </div>
        
        <!-- Botones de acción -->
        <div class="flex space-x-2">
            <a 
                href="{{ route('services.show', $service) }}" 
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md flex-1 justify-center"
            >
                <i class="fas fa-eye mr-2"></i>
                Ver Detalles
            </a>
            <button 
                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500 shadow-sm hover:shadow-md rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 px-3"
                title="Guardar en favoritos"
                onclick="toggleFavorite({{ $service->id }})"
            >
                <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors duration-200"></i>
            </button>
        </div>
    </div>
</div>

<script>
function toggleFavorite(serviceId) {
    // Implementar lógica de favoritos
    console.log('Toggle favorite for service:', serviceId);
    // Aquí se podría hacer una llamada AJAX para guardar/quitar de favoritos
}
</script> 