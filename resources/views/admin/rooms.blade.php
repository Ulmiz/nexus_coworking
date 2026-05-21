<x-admin-layout>
    <div class="px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#0a192f] tracking-tight">Gestión de Salas</h1>
                <p class="text-gray-400 text-sm mt-1">Administra el inventario de salas, capacidades y tarifas.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 text-[#007060] bg-[#E0F2F1] rounded-xl border border-[#007060]/20 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 text-red-700 bg-red-50 rounded-xl border border-red-200 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <!-- Add Room Form -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 h-fit">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-7 h-7 rounded-full border-2 border-[#007060] flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-[#007060]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h2 class="font-bold text-[#0a192f] text-base">Añadir Nueva Sala</h2>
                </div>

                <form action="{{ route('rooms.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Nombre de la Sala</label>
                        <input type="text" name="name" placeholder="Ej. Sala de Juntas A"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#007060]/30 focus:border-[#007060] transition"
                               value="{{ old('name') }}" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Capacidad (Personas)</label>
                        <input type="number" name="capacity" placeholder="Ej. 12" min="1"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#007060]/30 focus:border-[#007060] transition"
                               value="{{ old('capacity') }}" required>
                        @error('capacity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1.5">Tarifa por Hora ($)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                            <input type="number" name="price_per_hour" placeholder="0.00" min="0" step="0.01"
                                   class="w-full border border-gray-200 rounded-xl pl-8 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#007060]/30 focus:border-[#007060] transition"
                                   value="{{ old('price_per_hour') }}" required>
                        </div>
                        @error('price_per_hour')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit"
                            class="w-full bg-[#007060] hover:bg-[#005a4d] text-white font-semibold py-2.5 rounded-xl transition text-sm">
                        Guardar Sala
                    </button>
                </form>
            </div>

            <!-- Rooms List -->
            <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 overflow-hidden h-fit">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <h2 class="font-bold text-[#0a192f]">Inventario Actual</h2>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">{{ $rooms->count() }} Salas</span>
                </div>

                @if($rooms->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <p class="text-gray-400 text-sm">Aún no has añadido ninguna sala.</p>
                    </div>
                @else
                    <!-- Table Header -->
                    <div class="grid grid-cols-12 px-6 py-3 bg-gray-50/50 border-b border-gray-100">
                        <div class="col-span-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sala</div>
                        <div class="col-span-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Capacidad</div>
                        <div class="col-span-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tarifa/Hr</div>
                        <div class="col-span-2 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Acciones</div>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($rooms as $room)
                        <div class="grid grid-cols-12 px-6 py-4 items-center hover:bg-gray-50/50 transition">
                            <div class="col-span-5 flex items-center gap-3">
                                <div class="w-9 h-9 bg-[#0a192f] rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#00E5C0]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <span class="font-semibold text-[#0a192f] text-sm">{{ $room->name }}</span>
                            </div>
                            <div class="col-span-3 text-sm text-gray-500">{{ $room->capacity }} pax</div>
                            <div class="col-span-2 text-sm font-bold text-[#0a192f]">${{ number_format($room->price_per_hour, 2) }}</div>
                            <div class="col-span-2 flex justify-end gap-2">
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline"
                                      onsubmit="return confirm('¿Eliminar la sala {{ addslashes($room->name) }}? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
