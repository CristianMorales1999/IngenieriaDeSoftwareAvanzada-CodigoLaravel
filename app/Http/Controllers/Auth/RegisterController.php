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
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(StoreUserRequest $request)
    {
        // Crear el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'address' => $request->address,
        ]);

        // Iniciar sesión automáticamente después del registro
        Auth::login($user);

        // Redirigir al dashboard
        return redirect(RouteServiceProvider::HOME)->with('success', '¡Cuenta creada exitosamente!');
    }
} 