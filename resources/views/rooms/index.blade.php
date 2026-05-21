<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-bold text-[#0a192f] mb-2 tracking-tight">Explorar Salas</h1>
                <p class="text-gray-500 text-lg">Encuentra el espacio perfecto para tu próxima reunión.</p>
            </div>
            
            <!-- Search Bar -->
            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#007060] focus:border-[#007060] sm:text-sm transition" placeholder="Buscar salas...">
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 text-[#007060] bg-[#E0F2F1] rounded-lg border border-[#007060]/20 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Rooms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                // Pre-assigned mock images for the rooms to match the premium feel
                $mockImages = [
                    'assets/images/space_zenith.png',
                    'assets/images/space_library.png',
                    'assets/images/space_pod.png',
                    'assets/images/dashboard_hero.png',
                    'assets/images/register_bg.png',
                    'assets/images/login_bg.png',
                ];
            @endphp

            @forelse($rooms as $room)
                @php
                    // Get an image based on the room ID so it's consistent
                    $imageIndex = ($room->id - 1) % count($mockImages);
                    $imageUrl = asset($mockImages[$imageIndex]);
                    
                    // Mock ratings between 4.5 and 5.0
                    $rating = number_format(4.5 + ($room->id % 5) * 0.1, 1);
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col group relative">
                    <!-- Image -->
                    <div class="h-56 w-full relative overflow-hidden bg-gray-200">
                        <img src="{{ $imageUrl }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Rating Badge -->
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-full flex items-center gap-1 shadow-sm">
                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-xs font-bold text-gray-700">{{ $rating }}</span>
                        </div>

                        <!-- Admin Actions -->
                        @if(Auth::user()->role === 'admin')
                        <div class="absolute top-4 left-4">
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta sala?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500/90 hover:bg-red-600 text-white p-2 rounded-full shadow-sm backdrop-blur-sm transition" title="Eliminar Sala">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-gray-900 leading-tight pr-4">{{ $room->name }}</h3>
                            <div class="text-right whitespace-nowrap">
                                <span class="text-xl font-bold text-[#007060]">${{ number_format($room->price_per_hour, 0) }}</span><span class="text-xs text-gray-400 font-semibold">/hr</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                {{ $room->capacity }} personas
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                @if($room->capacity > 10)
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    Pantalla 65"
                                @elseif($room->capacity > 4)
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                                    Pizarrón
                                @else
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.906 14.142 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>
                                    Wi-Fi 6
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('reservations.create') }}?room={{ $room->id }}" class="mt-auto block w-full bg-[#007060] hover:bg-[#005a4d] text-white text-center py-3 rounded-lg font-semibold transition text-sm">
                            Reservar ahora
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-16 bg-white rounded-2xl shadow-sm border border-gray-100 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No hay salas disponibles</h3>
                    <p class="text-gray-500 max-w-sm mb-6">Comienza agregando tu primera sala al sistema para que los usuarios puedan reservarla.</p>
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                    <a href="{{ route('rooms.create') }}" class="bg-[#0a192f] hover:bg-gray-800 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Crear una Sala
                    </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
