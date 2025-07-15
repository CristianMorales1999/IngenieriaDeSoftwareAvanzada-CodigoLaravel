<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Constructor del controlador.
     * Aplica middleware guest para que solo usuarios no autenticados puedan acceder.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Muestra el formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de login.
     * Utiliza LoginRequest para validación y transformación de datos.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        // Obtener datos validados del Form Request
        $credentials = $request->only(['email', 'password']);
        $remember = $request->boolean('remember');
        
        // Intentar autenticar al usuario
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirigir al dashboard o a la página anterior
            return redirect()->intended(RouteServiceProvider::HOME)
                ->with('success', '¡Bienvenido de vuelta!');
        }

        // Si la autenticación falla, lanzar excepción de validación
        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no coinciden con nuestros registros.'],
        ]);
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
} 