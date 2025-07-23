<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     * En este caso, permitimos que cualquier usuario autenticado pueda actualizar usuarios.
     * En un entorno real, podrías querer restringir esto a administradores o al propio usuario.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Permitir a todos los usuarios actualizar usuarios
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,".auth()->id()],
            'mobile' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
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
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe ser texto.',
            'name.max' => 'El :attribute no puede tener más de 255 caracteres.',
            
            'email.required' => 'El :attribute es obligatorio.',
            'email.string' => 'El :attribute debe ser texto.',
            'email.email' => 'El formato del :attribute no es válido.',
            'email.max' => 'El :attribute no puede tener más de 255 caracteres.',
            'email.unique' => 'Este :attribute ya está registrado.',
            
            'mobile.string' => 'El :attribute debe ser texto.',
            'mobile.max' => 'El :attribute no puede tener más de 20 caracteres.',
            'mobile.regex' => 'El formato del :attribute no es válido.',
            
            'address.string' => 'La :attribute debe ser texto.',
            'address.max' => 'La :attribute no puede tener más de 1000 caracteres.',
            
            'password.confirmed' => 'La confirmación de :attribute no coincide.',
            'password.min' => 'La :attribute debe tener al menos 8 caracteres.',
            'password.mixed' => 'La :attribute debe contener al menos una letra mayúscula y una minúscula.',
            'password.numbers' => 'La :attribute debe contener al menos un número.',
            'password.symbols' => 'La :attribute debe contener al menos un símbolo.',
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
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'mobile' => 'número móvil',
            'address' => 'dirección',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
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
        // Limpiar y formatear el número móvil si está presente
        if ($this->has('mobile') && $this->mobile) {
            $this->merge([
                'mobile' => $this->cleanMobileNumber($this->mobile)
            ]);
        }

        // Limpiar espacios en blanco del nombre y email
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->name)
            ]);
        }

        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email))
            ]);
        }

        // Si la contraseña está vacía, removerla para que no se actualice
        if ($this->has('password') && empty($this->password)) {
            $this->request->remove('password');
            $this->request->remove('password_confirmation');
        }
    }

    /**
     * Limpia y formatea un número de teléfono móvil.
     * Elimina caracteres innecesarios y mantiene solo números, +, -, espacios y paréntesis.
     *
     * @param string $mobile
     * @return string
     */
    private function cleanMobileNumber(string $mobile): string
    {
        // Eliminar caracteres no permitidos excepto números, +, -, espacios y paréntesis
        $cleaned = preg_replace('/[^0-9+\-\s()]/', '', $mobile);
        
        // Eliminar espacios múltiples y trim
        return trim(preg_replace('/\s+/', ' ', $cleaned));
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

        // Asegurar que el email esté en minúsculas
        if (isset($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        // Remover campos vacíos para que no se actualicen
        $validated = array_filter($validated, function ($value) {
            return $value !== null && $value !== '';
        });

        return $validated;
    }

    /**
     * Obtiene el usuario que se está actualizando.
     * Útil para lógica adicional en el controlador.
     *
     * @return \App\Models\User|null
     */
    public function getTargetUser(): ?\App\Models\User
    {
        $userId = $this->route('user') ?? $this->input('id');
        
        if ($userId) {
            return \App\Models\User::find($userId);
        }

        return null;
    }
} 