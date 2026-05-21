<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-bold text-[#0a192f] mb-2 tracking-tight">Mis Reservas</h1>
                <p class="text-gray-500 text-lg">Gestiona tus espacios de trabajo y reuniones programadas.</p>
            </div>
            
            <div class="flex items-center gap-3">
                @if(Auth::user()->isAdmin())
                <form action="{{ route('reservations.purge-cancelled') }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente todas las reservas canceladas?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 px-4 rounded-lg transition inline-flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Limpiar Canceladas
                    </button>
                </form>
                @endif
                <a href="{{ route('rooms.index') }}" class="bg-[#0a192f] hover:bg-gray-800 text-white font-semibold py-2.5 px-6 rounded-lg transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Nueva Reserva
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 text-[#007060] bg-[#E0F2F1] rounded-lg border border-[#007060]/20 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 text-red-700 bg-red-100 rounded-lg border border-red-200 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Reservations List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sala</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Horario</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($reservations as $reservation)
                            @php
                                $start = \Carbon\Carbon::parse($reservation->start_time);
                                $end = \Carbon\Carbon::parse($reservation->end_time);
                                
                                // Determine icon and background color based on room id
                                $iconBg = ['bg-blue-100', 'bg-indigo-100', 'bg-gray-100'][$reservation->room->id % 3];
                                $iconText = ['text-blue-600', 'text-indigo-600', 'text-gray-600'][$reservation->room->id % 3];
                                
                                $status = $reservation->status; // pending, confirmed, cancelled
                                $now = now();
                                if ($status === 'cancelled') {
                                    $displayStatus = 'Cancelada';
                                    $badgeClass = 'bg-red-100 text-red-600 border border-red-200';
                                } elseif ($status === 'confirmed' && $end->isPast()) {
                                    $displayStatus = 'Finalizada';
                                    $badgeClass = 'bg-gray-100 text-gray-600 border border-gray-200';
                                } elseif ($status === 'confirmed') {
                                    $displayStatus = 'Confirmada';
                                    $badgeClass = 'bg-[#E0F2F1] text-[#007060] border border-[#007060]/20';
                                } else {
                                    // pending or any other status
                                    $displayStatus = 'Pendiente';
                                    $badgeClass = 'bg-yellow-100 text-yellow-600 border border-yellow-200';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 {{ $iconBg }} rounded-lg flex items-center justify-center {{ $iconText }}">
                                            @if($reservation->room->capacity > 10)
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            @elseif($reservation->room->capacity > 4)
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $reservation->room->name }}</p>
                                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                                                <p class="text-xs text-gray-500">Usuario: {{ $reservation->user->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <p class="text-sm font-medium text-gray-900">{{ $start->format('d M, Y') }}</p>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <p class="text-sm text-gray-600">{{ $start->format('h:i A') }} - {{ $end->format('h:i A') }}</p>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-right flex justify-end gap-3 items-center">
                                    <a href="{{ route('reservations.pdf', $reservation->id) }}" class="text-[#007060] hover:text-[#005a4d] text-sm font-semibold flex items-center transition">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        Comprobante PDF
                                    </a>
                                    
                                    @if($status === 'confirmed' && !$end->isPast())
                                        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition ml-2" title="Cancelar Reserva">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if($status === 'confirmed' && $end->isPast())
                                        <form action="{{ route('reservations.delete-completed', $reservation) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta reserva finalizada?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-red-500 transition ml-2" title="Eliminar reserva finalizada">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L7 5m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if(Auth::user()->isAdmin() && $status === 'cancelled')
                                        <form action="{{ route('reservations.force-destroy', $reservation) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente esta reserva cancelada?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-red-500 transition ml-2" title="Eliminar permanentemente">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                    @if(Auth::user()->isAdmin() && $status === 'cancelled')
                                        <form action="{{ route('reservations.force-destroy', $reservation) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente esta reserva cancelada?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-red-500 transition ml-2" title="Eliminar permanentemente">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Aún no tienes reservas.</p>
                                        <a href="{{ route('rooms.index') }}" class="text-[#007060] font-semibold hover:underline mt-1 text-sm">Explora nuestras salas</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($reservations instanceof \Illuminate\Pagination\LengthAwarePaginator && $reservations->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
