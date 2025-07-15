<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Constructor del controlador.
     * Aplica middleware guest para que solo usuarios no autenticados puedan registrarse.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Muestra el formulario de registro.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo usuario.
     * Utiliza StoreUserRequest para validación y transformación de datos.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(StoreUserRequest $request)
    {
        // Obtener datos validados y transformados del Form Request
        $validatedData = $request->validated();
        
        // Hashear la contraseña antes de crear el usuario
        $validatedData['password'] = Hash::make($validatedData['password']);
        
        // Crear el nuevo usuario con los datos validados
        $user = User::create($validatedData);

        // Iniciar sesión automáticamente después del registro
        Auth::login($user);

        // Redirigir al dashboard con mensaje de éxito
        return redirect(RouteServiceProvider::HOME)
            ->with('success', '¡Cuenta creada exitosamente! Bienvenido a ServiPro. Completa tu perfil para una mejor experiencia.');
    }
} 