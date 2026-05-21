<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NexusSpace') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-[#F8F9FA]">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="flex-1 w-full">
                {{ $slot }}
            </main>
            
            <!-- Minimal Footer for Auth Area -->
            <footer class="bg-[#F8F9FA] border-t border-gray-200 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                    <div class="flex items-center gap-2 mb-4 md:mb-0">
                        <div class="w-5 h-5 bg-[#007060] rounded flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2l2.5 5.5L18 8l-4 3.5L15 17l-5-3-5 3 1-5.5L2 8l5.5-.5L10 2z"/></svg>
                        </div>
                        <span class="font-bold text-gray-900">NexusSpace</span>
                    </div>
                    
                    <div class="flex space-x-6 mb-4 md:mb-0 font-medium">
                        <a href="#" class="hover:text-gray-900 transition">Privacidad</a>
                        <a href="#" class="hover:text-gray-900 transition">Términos</a>
                        <a href="#" class="hover:text-gray-900 transition">Contacto</a>
                    </div>
                    
                    <div>
                        &copy; {{ date('Y') }} NexusSpace. Todos los derechos reservados.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
