<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Muestra la lista de todos los servicios.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $services = Service::with('user')
            ->latest()
            ->paginate(12);

        return view('services.index', compact('services'));
    }

    /**
     * Muestra un servicio específico.
     *
     * @param Service $service
     * @return \Illuminate\View\View
     */
    public function show(Service $service): \Illuminate\View\View
    {
        $service->load('user');
        
        return view('services.show', compact('service'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('services.create');
    }

    /**
     * Almacena un nuevo servicio.
     *
     * @param StoreServiceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreServiceRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        
        // Manejar la imagen si se proporcionó
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service = new Service($validated);
        $service->user_id = auth()->id();
        $service->save();

        return redirect()->route('services.show', $service)
            ->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un servicio.
     *
     * @param Service $service
     * @return \Illuminate\View\View
     */
    public function edit(Service $service): \Illuminate\View\View
    {
        // Verificar que el usuario sea el propietario del servicio
        if ($service->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para editar este servicio.');
        }

        return view('services.edit', compact('service'));
    }

    /**
     * Actualiza un servicio existente.
     *
     * @param UpdateServiceRequest $request
     * @param Service $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateServiceRequest $request, Service $service): \Illuminate\Http\RedirectResponse
    {
        // Verificar que el usuario sea el propietario del servicio
        if ($service->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para editar este servicio.');
        }

        $validated = $request->validated();
        
        // Manejar la imagen si se proporcionó
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            $service->deleteImage();
            
            // Guardar nueva imagen
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('services.show', $service)
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Elimina un servicio.
     *
     * @param Service $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Service $service): \Illuminate\Http\RedirectResponse
    {
        // Verificar que el usuario sea el propietario del servicio
        if ($service->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para eliminar este servicio.');
        }

        // Eliminar imagen si existe
        $service->deleteImage();
        
        $service->delete();

        return redirect()->route('services.my')
            ->with('success', 'Servicio eliminado exitosamente.');
    }

    /**
     * Muestra los servicios del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function myServices(): \Illuminate\View\View
    {
        $services = auth()->user()->services()
            ->latest()
            ->paginate(10);

        return view('services.my', compact('services'));
    }
} 