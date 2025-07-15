<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el formulario de edición del perfil.
     *
     * @return \Illuminate\View\View
     */
    public function edit(): \Illuminate\View\View
    {
        return view('profile.edit');
    }

    /**
     * Actualiza el perfil del usuario.
     *
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Manejar la imagen de perfil si se proporcionó
        if ($request->hasFile('image')) {
            $user->saveImage($request->file('image'));
        }

        // Actualizar otros campos
        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Actualiza la contraseña del usuario.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = auth()->user();
        $user->updatePassword($request->password);

        return redirect()->route('profile.edit')
            ->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Elimina la imagen de perfil del usuario.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteImage(): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        $user->deleteImage();

        return redirect()->route('profile.edit')
            ->with('success', 'Imagen de perfil eliminada exitosamente.');
    }
} 