@props(['action' => route('contact.store')])

<div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-xl font-semibold text-gray-900">
            <i class="fas fa-envelope mr-2 text-blue-500"></i>
            Envíanos un mensaje
        </h3>
    </div>
    <div class="px-6 py-4">
        <form action="{{ $action }}" method="POST" class="space-y-6" id="contact-form">
            @csrf
            
            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Nombre completo
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="Ingresa tu nombre completo"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                    Correo electrónico
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="tu@email.com"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Asunto -->
            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-2 text-blue-500"></i>
                    Asunto
                </label>
                <select 
                    id="subject" 
                    name="subject" 
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('subject') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    required
                >
                    <option value="">Selecciona un asunto</option>
                    <option value="consulta" {{ old('subject') == 'consulta' ? 'selected' : '' }}>
                        Consulta general
                    </option>
                    <option value="servicio" {{ old('subject') == 'servicio' ? 'selected' : '' }}>
                        Solicitud de servicio
                    </option>
                    <option value="soporte" {{ old('subject') == 'soporte' ? 'selected' : '' }}>
                        Soporte técnico
                    </option>
                    <option value="sugerencia" {{ old('subject') == 'sugerencia' ? 'selected' : '' }}>
                        Sugerencia
                    </option>
                    <option value="reclamo" {{ old('subject') == 'reclamo' ? 'selected' : '' }}>
                        Reclamo
                    </option>
                    <option value="otro" {{ old('subject') == 'otro' ? 'selected' : '' }}>
                        Otro
                    </option>
                </select>
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Mensaje -->
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment mr-2 text-blue-500"></i>
                    Mensaje
                </label>
                <textarea 
                    id="message" 
                    name="message" 
                    rows="5"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('message') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="Describe tu consulta o solicitud..."
                    required
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Botón de envío -->
            <div class="mb-4">
                <button 
                    type="submit" 
                    class="inline-flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md w-full"
                    id="submit-btn"
                >
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span id="submit-text">Enviar Mensaje</span>
                    <span id="submit-loading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Enviando...
                    </span>
                </button>
            </div>

            <!-- Información adicional -->
            <div class="text-center text-sm text-gray-600">
                <p>
                    <i class="fas fa-info-circle mr-1"></i>
                    Te responderemos en un plazo máximo de 24 horas
                </p>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');

    form.addEventListener('submit', function(e) {
        // Mostrar estado de carga
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
    });
});
</script> 