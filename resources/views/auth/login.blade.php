@extends('layouts.app')

@section('title', 'Iniciar Sesión')
@section('description', 'Accede a tu cuenta de ServiPro')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cogs text-white text-xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Iniciar Sesión
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                O
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    regístrate si no tienes una cuenta
                </a>
            </p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-100">
            <div class="px-6 py-4">
                <!-- Session Status -->
                @if(session('status'))
                    <div class="p-4 rounded-lg border-l-4 bg-green-50 border-green-400 text-green-700 mb-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="tu@email.com"
                            required
                            autofocus
                            autocomplete="username"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            required
                            autocomplete="current-password"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-4">
                        <label for="remember" class="inline-flex items-center">
                            <input 
                                id="remember" 
                                type="checkbox" 
                                name="remember"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            >
                            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md w-full">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
