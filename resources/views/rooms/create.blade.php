<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Sala') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <form method="POST" action="{{ route('rooms.store') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nombre de la Sala')" class="text-gray-700 font-semibold" />
                            <x-text-input id="name" class="block mt-2 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-shadow" type="text" name="name" :value="old('name')" required autofocus placeholder="Ej. Sala de Juntas A" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Descripción (Opcional)')" class="text-gray-700 font-semibold" />
                            <textarea id="description" name="description" rows="3" class="block mt-2 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition-shadow" placeholder="Detalles sobre equipamiento, ubicación, etc.">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Capacity -->
                            <div>
                                <x-input-label for="capacity" :value="__('Capacidad (Personas)')" class="text-gray-700 font-semibold" />
                                <x-text-input id="capacity" class="block mt-2 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" type="number" name="capacity" :value="old('capacity')" required min="1" placeholder="Ej. 10" />
                                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                            </div>

                            <!-- Price per hour -->
                            <div>
                                <x-input-label for="price_per_hour" :value="__('Precio por Hora ($)')" class="text-gray-700 font-semibold" />
                                <div class="relative mt-2">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <x-text-input id="price_per_hour" class="block w-full pl-7 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" type="number" step="0.01" name="price_per_hour" :value="old('price_per_hour')" required min="0" placeholder="0.00" />
                                </div>
                                <x-input-error :messages="$errors->get('price_per_hour')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('rooms.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition-transform transform hover:scale-105 duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Guardar Sala') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
