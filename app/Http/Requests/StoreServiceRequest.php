<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     * Solo usuarios autenticados pueden crear servicios.
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
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'category' => ['required', 'string', 'max:100', 'in:Desarrollo Web,Diseño Gráfico,Marketing Digital,Consultoría,Educación,Otros'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'user_id' => ['required', 'exists:users,id'],
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
            
            'category.required' => 'La :attribute es obligatoria.',
            'category.string' => 'La :attribute debe ser texto.',
            'category.max' => 'La :attribute no puede tener más de 100 caracteres.',
            
            'price.required' => 'El :attribute es obligatorio.',
            'price.numeric' => 'El :attribute debe ser un número.',
            'price.min' => 'El :attribute debe ser mayor a 0.',
            'price.max' => 'El :attribute no puede ser mayor a 999,999.99.',
            
            'location.string' => 'La :attribute debe ser texto.',
            'location.max' => 'La :attribute no puede tener más de 255 caracteres.',
            
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'image.max' => 'La imagen no puede ser mayor a 2MB.',
            
            'user_id.required' => 'El :attribute es obligatorio.',
            'user_id.exists' => 'El :attribute seleccionado no existe.',
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
            'category' => 'categoría del servicio',
            'price' => 'precio del servicio',
            'location' => 'ubicación del servicio',
            'image' => 'imagen del servicio',
            'user_id' => 'usuario',
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

        if ($this->has('location')) {
            $this->merge([
                'location' => trim($this->location)
            ]);
        }

        // Asignar automáticamente el usuario autenticado
        if (Auth::check()) {
            $this->merge([
                'user_id' => Auth::id()
            ]);
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

        // Asegurar que la ubicación tenga la primera letra en mayúscula si existe
        if (isset($validated['location'])) {
            $validated['location'] = ucfirst($validated['location']);
        }

        return $validated;
    }

    /**
     * Maneja la carga de la imagen del servicio.
     * Si se proporciona una imagen, la guarda en storage y devuelve la ruta.
     *
     * @return string|null
     */
    public function handleImageUpload(): ?string
    {
        if ($this->hasFile('image') && $this->file('image')->isValid()) {
            $file = $this->file('image');
            
            // Generar nombre único para la imagen
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Guardar en la carpeta services dentro de storage/app/public
            $path = $file->storeAs('services', $fileName, 'public');
            
            return $path;
        }

        return null;
    }

    /**
     * Obtiene el usuario autenticado que está creando el servicio.
     *
     * @return \App\Models\User
     */
    public function getAuthenticatedUser(): \App\Models\User
    {
        return Auth::user();
    }
} 