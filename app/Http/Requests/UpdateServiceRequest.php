<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     * Solo usuarios autenticados pueden actualizar servicios.
     * En un entorno real, podrías querer verificar que el usuario sea el propietario del servicio.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check(); // Solo usuarios autenticados
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     * Estas reglas se aplican antes de que se ejecute el método del controlador.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255', 'min:3'],
            'description' => ['sometimes', 'required', 'string', 'min:10', 'max:2000'],
            'image_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación.
     * Estos mensajes se muestran al usuario cuando la validación falla.
     * Usamos :attribute para que se reemplace automáticamente con el nombre del campo.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El :attribute es obligatorio.',
            'title.string' => 'El :attribute debe ser texto.',
            'title.max' => 'El :attribute no puede tener más de 255 caracteres.',
            'title.min' => 'El :attribute debe tener al menos 3 caracteres.',
            
            'description.required' => 'La :attribute es obligatoria.',
            'description.string' => 'La :attribute debe ser texto.',
            'description.min' => 'La :attribute debe tener al menos 10 caracteres.',
            'description.max' => 'La :attribute no puede tener más de 2000 caracteres.',
            
            'image_path.image' => 'El archivo debe ser una imagen.',
            'image_path.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'image_path.max' => 'La imagen no puede ser mayor a 2MB.',
        ];
    }

    /**
     * Obtiene los nombres de atributos personalizados para los mensajes de error.
     * Estos nombres se usan en lugar de los nombres de campo por defecto.
     * Laravel reemplaza automáticamente :attribute con estos valores.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'título del servicio',
            'description' => 'descripción del servicio',
            'image_path' => 'imagen del servicio',
        ];
    }

    /**
     * Prepara los datos para la validación.
     * Se ejecuta antes de que se apliquen las reglas de validación.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Limpiar espacios en blanco del título y descripción
        if ($this->has('title')) {
            $this->merge([
                'title' => trim($this->title)
            ]);
        }

        if ($this->has('description')) {
            $this->merge([
                'description' => trim($this->description)
            ]);
        }

        // Si se proporciona una nueva imagen, eliminar la anterior
        if ($this->hasFile('image_path')) {
            $this->handleImageReplacement();
        }
    }

    /**
     * Obtiene datos validados y opcionalmente los transforma.
     * Se ejecuta después de que la validación sea exitosa.
     *
     * @param array<string, mixed>|int|string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function validated($key = null, $default = null): mixed
    {
        $validated = parent::validated($key, $default);

        // Asegurar que el título tenga la primera letra en mayúscula
        if (isset($validated['title'])) {
            $validated['title'] = ucfirst($validated['title']);
        }

        // Asegurar que la descripción tenga la primera letra en mayúscula
        if (isset($validated['description'])) {
            $validated['description'] = ucfirst($validated['description']);
        }

        // Remover campos vacíos para que no se actualicen
        $validated = array_filter($validated, function ($value) {
            return $value !== null && $value !== '';
        });

        return $validated;
    }

    /**
     * Maneja la carga de la nueva imagen del servicio.
     * Si se proporciona una nueva imagen, elimina la anterior y guarda la nueva.
     *
     * @return string|null
     */
    public function handleImageUpload(): ?string
    {
        if ($this->hasFile('image_path') && $this->file('image_path')->isValid()) {
            $file = $this->file('image_path');
            
            // Generar nombre único para la imagen
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Guardar en la carpeta services dentro de storage/app/public
            $path = $file->storeAs('services', $fileName, 'public');
            
            return $path;
        }

        return null;
    }

    /**
     * Maneja el reemplazo de imagen.
     * Elimina la imagen anterior si existe y prepara la nueva.
     *
     * @return void
     */
    private function handleImageReplacement(): void
    {
        // Obtener el servicio que se está actualizando
        $service = $this->getTargetService();
        
        if ($service && $service->image_path) {
            // Eliminar la imagen anterior del storage
            $service->deleteImage();
        }
    }

    /**
     * Obtiene el servicio que se está actualizando.
     * Útil para lógica adicional en el controlador.
     *
     * @return \App\Models\Service|null
     */
    public function getTargetService(): ?\App\Models\Service
    {
        $serviceId = $this->route('service') ?? $this->input('id');
        
        if ($serviceId) {
            return \App\Models\Service::find($serviceId);
        }

        return null;
    }

    /**
     * Obtiene el usuario autenticado que está actualizando el servicio.
     *
     * @return \App\Models\User
     */
    public function getAuthenticatedUser(): \App\Models\User
    {
        return Auth::user();
    }

    /**
     * Verifica si el usuario autenticado es el propietario del servicio.
     * Útil para autorización adicional en el controlador.
     *
     * @return bool
     */
    public function isOwner(): bool
    {
        $service = $this->getTargetService();
        
        if (!$service) {
            return false;
        }

        return $service->user_id === Auth::id();
    }
} 