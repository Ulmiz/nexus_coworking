<x-admin-layout>
    <div class="px-8 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#0a192f] tracking-tight">Gestión de Usuarios</h1>
                <p class="text-gray-400 text-sm mt-1">Administra el acceso y los roles de los miembros del espacio.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 text-[#007060] bg-[#E0F2F1] rounded-xl border border-[#007060]/20 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Bar -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="relative flex-1 max-w-md">
                    <svg class="w-4 h-4 text-gray-300 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input id="userSearch" type="text" placeholder="Buscar usuarios..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#007060]/30 focus:border-[#007060] transition">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left" id="usersTable">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-3.5 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider">Usuario</th>
                            <th class="py-3.5 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider">Correo Electrónico</th>
                            <th class="py-3.5 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider">Rol</th>
                            <th class="py-3.5 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="usersBody">
                        @foreach($users as $user)
                            @php
                                $initials = collect(explode(' ', $user->name))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
                                $colors = ['bg-blue-100 text-blue-600','bg-indigo-100 text-indigo-600','bg-purple-100 text-purple-600','bg-emerald-100 text-emerald-600','bg-amber-100 text-amber-600'];
                                $color = $colors[$user->id % count($colors)];
                                $roleBadge = match($user->role) {
                                    'admin'  => 'bg-[#0a192f] text-white',
                                    'staff'  => 'bg-blue-100 text-blue-700',
                                    default  => 'bg-gray-100 text-gray-600',
                                };
                                $roleLabel = match($user->role) {
                                    'admin'  => 'Administrador',
                                    'staff'  => 'Staff',
                                    default  => 'Miembro',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full {{ $color }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <span class="font-semibold text-[#0a192f] text-sm">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $roleBadge }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('users.update', $user) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role"
                                                class="border border-gray-200 text-sm rounded-lg px-3 py-1.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-[#007060]/30 focus:border-[#007060] transition">
                                            <option value="client" {{ $user->role === 'client' ? 'selected' : '' }}>Miembro</option>
                                            <option value="staff"  {{ $user->role === 'staff'  ? 'selected' : '' }}>Staff</option>
                                            <option value="admin"  {{ $user->role === 'admin'  ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit"
                                                class="bg-[#007060] hover:bg-[#005a4d] text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                            Guardar
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-xs text-gray-300 italic">Tu cuenta</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination info -->
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400">Mostrando {{ $users->count() }} usuario(s)</p>
            </div>
        </div>
    </div>

    <script>
        // Live search filter
        document.getElementById('userSearch').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.user-row').forEach(row => {
                const name  = row.dataset.name  || '';
                const email = row.dataset.email || '';
                row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
            });
        });
    </script>
</x-admin-layout>
