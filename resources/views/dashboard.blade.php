<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        
        <!-- Hero Banner -->
        <div class="relative rounded-[2rem] overflow-hidden mb-12 shadow-sm bg-[#0a192f] h-72">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/dashboard_hero.png') }}" class="w-full h-full object-cover opacity-60" alt="Workspace">
                <div class="absolute inset-0 bg-gradient-to-r from-[#0a192f] via-[#0a192f]/80 to-transparent"></div>
            </div>
            <div class="relative h-full flex flex-col justify-center p-12">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">¡Bienvenido de nuevo, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                <p class="text-gray-300 text-lg max-w-xl">
                    Tu espacio ideal te espera. Gestiona tus reservas y descubre nuevas áreas de trabajo colaborativo.
                </p>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 mb-6">Resumen de hoy</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Próxima Reserva Card -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border-t-4 border-t-[#00E5C0] border border-gray-100 flex flex-col h-full relative">
                @php
                    // Fetch the next upcoming reservation for the user
                    $nextReservation = Auth::user()->reservations()->with('room')->where('start_time', '>=', now())->orderBy('start_time', 'asc')->first();
                @endphp

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-[#E0F2F1] rounded-lg flex items-center justify-center text-[#007060]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Próxima Reserva</h3>
                    
                    @if($nextReservation)
                        <span class="ml-auto bg-[#E0F2F1] text-[#007060] text-xs font-bold px-3 py-1 rounded-full">Confirmada</span>
                    @endif
                </div>

                @if($nextReservation)
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Espacio</p>
                        <p class="text-lg font-bold text-gray-900">{{ $nextReservation->room->name }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Horario</p>
                            <p class="text-sm text-gray-800 flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ \Carbon\Carbon::parse($nextReservation->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($nextReservation->end_time)->format('h:i A') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha</p>
                            <p class="text-sm text-gray-800 flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ \Carbon\Carbon::parse($nextReservation->start_time)->format('d M') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-end">
                        <a href="{{ route('reservations.index') }}" class="text-sm font-semibold text-gray-900 hover:text-[#007060] flex items-center transition">
                            Ver detalles <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center py-6">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <p class="text-gray-500 font-medium">No tienes reservas próximas.</p>
                        <p class="text-sm text-gray-400 mt-1">Es un buen momento para agendar tu próximo espacio.</p>
                    </div>
                @endif
            </div>

            <!-- Explorar Espacios Card -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 flex flex-col h-full">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center text-gray-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Explorar Espacios</h3>
                </div>
                
                <p class="text-gray-600 mb-6 leading-relaxed">
                    ¿Necesitas un lugar para concentrarte o colaborar con tu equipo? Descubre nuevas salas de reuniones, cabinas privadas y escritorios compartidos diseñados para tu productividad.
                </p>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-[#F8F9FA] rounded-lg p-3 flex items-center gap-2 border border-gray-100">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span class="text-xs font-semibold text-gray-600">Salas de Equipo</span>
                    </div>
                    <div class="bg-[#F8F9FA] rounded-lg p-3 flex items-center gap-2 border border-gray-100">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span class="text-xs font-semibold text-gray-600">Cabinas Privadas</span>
                    </div>
                </div>

                <a href="{{ route('rooms.index') }}" class="mt-auto block w-full bg-[#007060] hover:bg-[#005a4d] text-white text-center py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    Buscar Salas
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
