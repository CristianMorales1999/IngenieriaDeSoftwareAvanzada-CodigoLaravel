<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     * En este caso, permitimos que cualquier usuario pueda intentar hacer login.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Cualquier usuario puede intentar hacer login
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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación.
     * Estos mensajes se muestran al usuario cuando la validación falla.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser texto.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser texto.',
            
            'remember.boolean' => 'El campo recordarme debe ser verdadero o falso.',
        ];
    }

    /**
     * Obtiene los nombres de atributos personalizados para los mensajes de error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'remember' => 'recordarme',
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
        // Limpiar espacios en blanco del email
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email))
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

        // Asegurar que el email esté en minúsculas
        if (isset($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        return $validated;
    }
} 