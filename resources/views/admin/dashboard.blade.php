<x-admin-layout>
    <div class="px-8 py-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#0a192f] tracking-tight">Dashboard Overview</h1>
                <p class="text-gray-400 text-sm mt-1">Bienvenido, {{ Auth::user()->name }}. Aquí está lo que ocurre hoy.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#0a192f] flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Usuarios</p>
                    <p class="text-4xl font-bold text-[#0a192f]">{{ $totalUsers }}</p>
                    <p class="text-xs text-[#007060] mt-1 font-medium">Miembros registrados</p>
                </div>
                <div class="w-14 h-14 bg-[#E0F2F1] rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#007060]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Reservas Hoy</p>
                    <p class="text-4xl font-bold text-[#0a192f]">{{ $todayReservations }}</p>
                    <p class="text-xs text-[#007060] mt-1 font-medium">{{ $totalReservations }} total en el sistema</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Salas Activas</p>
                    <p class="text-4xl font-bold text-[#0a192f]">{{ $totalRooms }}</p>
                    <p class="text-xs text-gray-400 mt-1 font-medium">Espacios disponibles</p>
                </div>
                <div class="w-14 h-14 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

            <div class="space-y-4">
                <h2 class="text-lg font-bold text-[#0a192f]">Acciones Rápidas</h2>
                <a href="{{ route('admin.rooms') }}"
                   class="block bg-[#0a192f] hover:bg-[#0d2137] text-white rounded-2xl p-6 transition group cursor-pointer">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-white/20 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <p class="font-bold text-base">Gestionar Salas</p>
                    <p class="text-xs text-white/60 mt-1">Añadir, editar y eliminar salas.</p>
                </a>
                <a href="{{ route('reservations.create') }}"
                   class="block bg-[#007060] hover:bg-[#005a4d] text-white rounded-2xl p-6 transition group cursor-pointer">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-white/20 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="font-bold text-base">Nueva Reserva</p>
                    <p class="text-xs text-white/60 mt-1">Agendar un espacio para un usuario.</p>
                </a>
            </div>

            <div class="md:col-span-2 bg-white rounded-2xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-bold text-[#0a192f]">Agenda de Hoy</h2>
                    <a href="{{ route('reservations.index') }}" class="text-sm text-[#007060] font-semibold hover:underline">Ver todas →</a>
                </div>

                @forelse($todaySchedule as $res)
                    @php
                        $badge = $res->status === 'confirmed' ? 'bg-[#E0F2F1] text-[#007060]' : ($res->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700');
                        $badgeLabel = $res->status === 'confirmed' ? 'Confirmada' : ($res->status === 'cancelled' ? 'Cancelada' : ucfirst($res->status));
                    @endphp
                    <div class="flex items-start gap-4 py-3 border-b border-gray-50 last:border-0">
                        <div class="text-center min-w-[52px]">
                            <p class="text-sm font-bold text-[#0a192f]">{{ \Carbon\Carbon::parse($res->start_time)->format('h:i') }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($res->start_time)->format('A') }}</p>
                        </div>
                        <div class="w-0.5 self-stretch bg-[#00E5C0] rounded-full mx-1"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-[#0a192f] text-sm">{{ $res->room->name }}</p>
                            <p class="text-xs text-gray-400">{{ $res->user->name }} · {{ \Carbon\Carbon::parse($res->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($res->end_time)->format('h:i A') }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ $badgeLabel }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-gray-400 text-sm">Sin reservas programadas para hoy.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-[#0a192f]">Gestión de Salas</h2>
                <a href="{{ route('admin.rooms') }}" class="bg-[#0a192f] hover:bg-[#0d2137] text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    + Añadir Sala
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-50">
                            <th class="py-3 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sala</th>
                            <th class="py-3 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider">Capacidad</th>
                            <th class="py-3 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tarifa / Hr</th>
                            <th class="py-3 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($rooms as $room)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-[#0a192f] rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-[#00E5C0]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <span class="font-semibold text-[#0a192f] text-sm">{{ $room->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-6 text-sm text-gray-600">{{ $room->capacity }} pax</td>
                            <td class="py-3.5 px-6 text-sm font-semibold text-[#0a192f]">${{ number_format($room->price_per_hour, 2) }}</td>
                            <td class="py-3.5 px-6">
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar la sala {{ $room->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition" title="Eliminar sala">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-gray-50 text-xs text-gray-400">
                Mostrando {{ $rooms->count() }} sala(s)
            </div>
        </div>

    </div>
</x-admin-layout>