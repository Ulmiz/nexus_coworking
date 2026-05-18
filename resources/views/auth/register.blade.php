<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexusSpace - Crear Cuenta</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .split-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('{{ asset('assets/images/register_bg.png') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="antialiased bg-[#F8F9FA] text-gray-900 h-screen flex overflow-hidden">

    <!-- Left Side: Visual -->
    <div class="hidden lg:flex lg:w-1/2 split-bg p-12 flex-col justify-center relative">
        <div class="absolute top-12 left-12">
            <a href="/" class="text-2xl font-bold tracking-tight text-[#00E5C0]">NexusSpace</a>
        </div>
        
        <div class="max-w-md mt-20">
            <span class="text-[#00E5C0] text-sm font-bold tracking-widest uppercase mb-4 block">NexusSpace Premium</span>
            <h1 class="text-5xl font-bold text-white leading-tight mb-6 tracking-tight">Únete a la red de espacios más productiva</h1>
            <p class="text-gray-300 text-lg mb-12">
                Diseñado para líderes que buscan el equilibrio perfecto entre eficiencia, tecnología y confort arquitectónico.
            </p>
        </div>
    </div>

    <!-- Right Side: Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-[#F8F9FA] overflow-y-auto">
        <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-sm border border-gray-100">
            <!-- Mobile Logo -->
            <div class="lg:hidden mb-8 text-center">
                <a href="/" class="text-2xl font-bold tracking-tight text-[#007060]">NexusSpace</a>
            </div>

            <!-- Logo Header for form -->
            <div class="flex items-center mb-6 hidden lg:flex">
                <div class="w-8 h-8 bg-[#0a192f] rounded-md flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2l2.5 5.5L18 8l-4 3.5L15 17l-5-3-5 3 1-5.5L2 8l5.5-.5L10 2z"/></svg>
                </div>
                <span class="text-xl font-bold text-[#0a192f]">NexusSpace</span>
            </div>

            <h2 class="text-3xl font-bold mb-2 text-gray-900">Crear Cuenta</h2>
            <p class="text-sm text-gray-500 mb-8">Empieza tu jornada en un entorno diseñado para el éxito.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#007060] focus:border-[#007060] sm:text-sm text-gray-900 py-3 px-4">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Corporativo</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="email@compañia.com" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#007060] focus:border-[#007060] sm:text-sm text-gray-900 py-3 px-4">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#007060] focus:border-[#007060] sm:text-sm text-gray-900 py-3 px-4 pr-10">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                            <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <p class="text-xs text-gray-400 mb-6">Mínimo 8 caracteres, una mayúscula y un número.</p>

                <!-- Confirm Password -->
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#007060] focus:border-[#007060] sm:text-sm text-gray-900 py-3 px-4 pr-10">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#007060] hover:bg-[#005a4d] text-white py-3 px-4 rounded-md font-semibold transition shadow-sm text-sm">
                    Create Account
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-600">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:text-[#007060]">Iniciar Sesión</a>
            </p>
        </div>
    </div>

</body>
</html>
