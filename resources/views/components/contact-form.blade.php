@props(['action' => route('contact.store')])

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    
    <!-- Nombre -->
    <div class="form-group">
        <label for="name" class="form-label">
            <i class="fas fa-user mr-2 text-blue-500"></i>
            Nombre completo
        </label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            value="{{ old('name') }}"
            class="form-input @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
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
    <div class="form-group">
        <label for="email" class="form-label">
            <i class="fas fa-envelope mr-2 text-blue-500"></i>
            Correo electrónico
        </label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            value="{{ old('email') }}"
            class="form-input @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
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
    <div class="form-group">
        <label for="subject" class="form-label">
            <i class="fas fa-tag mr-2 text-blue-500"></i>
            Asunto
        </label>
        <select 
            id="subject" 
            name="subject" 
            class="form-select @error('subject') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
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
    <div class="form-group">
        <label for="message" class="form-label">
            <i class="fas fa-comment mr-2 text-blue-500"></i>
            Mensaje
        </label>
        <textarea 
            id="message" 
            name="message" 
            rows="5"
            class="form-textarea @error('message') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
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
    <div class="form-group">
        <button 
            type="submit" 
            class="btn btn-primary w-full py-3 text-base font-medium"
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
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