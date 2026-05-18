<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexusSpace - Iniciar Sesión</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .split-bg {
            background-image: linear-gradient(rgba(10, 25, 47, 0.7), rgba(10, 25, 47, 0.9)), url('{{ asset('assets/images/login_bg.png') }}');
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
            <h1 class="text-5xl font-bold text-white leading-tight mb-6 tracking-tight">Bienvenido a tu próximo espacio de éxito</h1>
            <p class="text-gray-300 text-lg mb-12">
                Donde la productividad se encuentra con la comunidad. Únete a una red global de profesionales en entornos diseñados para elevar tu potencial diario.
            </p>

            <div class="grid grid-cols-3 gap-8">
                <div>
                    <div class="text-2xl font-bold text-[#00E5C0] mb-1">500+</div>
                    <div class="text-xs tracking-wider text-gray-400 uppercase font-semibold">Miembros Activos</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-[#00E5C0] mb-1">24/7</div>
                    <div class="text-xs tracking-wider text-gray-400 uppercase font-semibold">Acceso Total</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-[#00E5C0] mb-1">15</div>
                    <div class="text-xs tracking-wider text-gray-400 uppercase font-semibold">Sedes Globales</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-[#F8F9FA] overflow-y-auto">
        <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-sm border border-gray-100">
            <!-- Mobile Logo -->
            <div class="lg:hidden mb-8 text-center">
                <a href="/" class="text-2xl font-bold tracking-tight text-[#007060]">NexusSpace</a>
            </div>

            <h2 class="text-2xl font-bold mb-2 text-gray-900">Iniciar Sesión</h2>
            <p class="text-sm text-gray-500 mb-8">Ingresa tus credenciales para acceder a tu panel.</p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nombre@empresa.com" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#007060] focus:border-[#007060] sm:text-sm text-gray-900 py-3">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#007060] focus:border-[#007060] sm:text-sm text-gray-900 py-3">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-8">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#007060] shadow-sm focus:ring-[#007060]" name="remember">
                        <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#007060] hover:text-[#005a4d]">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#007060] hover:bg-[#005a4d] text-white py-3 px-4 rounded-md font-semibold transition shadow-sm text-sm">
                    Iniciar Sesión
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-600">
                ¿Aún no eres miembro? 
                <a href="{{ route('register') }}" class="font-bold text-gray-900 hover:text-[#007060]">Crear Cuenta</a>
            </p>
        </div>

        <div class="absolute bottom-8 right-8 hidden lg:flex space-x-6 text-xs text-gray-400 font-medium">
            <a href="#" class="hover:text-gray-600">Términos</a>
            <a href="#" class="hover:text-gray-600">Privacidad</a>
            <a href="#" class="hover:text-gray-600">Soporte</a>
        </div>
    </div>

</body>
</html>
