<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Servicios Disponibles') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (auth()->user()->role === 'admin')
            <div class="mb-4 flex flex-wrap gap-3">
                <a href="{{ route('services.create') }}" class="btn-primary">
                    Nuevo Servicio
                </a>
                <button type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" x-on:click="$dispatch('open-modal', 'vehicle-types')">
                    Gestionar Tipos de Vehículos
                </button>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-h-96 overflow-y-auto">
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">Nombre</th>
                        <th class="px-4 py-2 border">Descripción</th>
                        <th class="px-4 py-2 border">Estado</th>
                        <th class="px-4 py-2 border">Modalidad</th>
                        <th class="px-4 py-2 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        @php
                            $hasVehiclePrices = $service->prices->whereNotNull('vehicle_type_id')->count() > 0;
                            $hasGenericPrices = $service->prices->whereNull('vehicle_type_id')->count() > 0;
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $service->name }}</td>
                            <td class="px-4 py-2">{{ $service->description }}</td>
                            <td class="px-4 py-2">{{ $service->active ? 'Activo' : 'Inactivo' }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-wrap gap-2">
                                    @if($hasVehiclePrices)
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">Por vehículo</span>
                                    @endif
                                    @if($hasGenericPrices)
                                        <span class="text-xs px-2 py-1 bg-purple-100 text-purple-800 rounded">Genérico</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('services.edit', $service) }}"
                                       class="text-yellow-600 hover:underline">Editar</a>

                                    <form method="POST" action="{{ route('services.destroy', $service) }}"
                                          onsubmit="return confirm('Â¿Eliminar este servicio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline" type="submit">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 italic">Sólo lectura</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if (auth()->user()->role === 'admin')
        <x-modal name="vehicle-types" focusable>
            <div class="p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Gestionar Tipos de Vehículos</h2>
                    <p class="text-sm text-gray-500">Agrega o elimina tipos de vehículos para los precios por servicio.</p>
                </div>

                <div class="space-y-2">
                    @forelse($vehicleTypes as $type)
                        <div class="flex items-center justify-between border rounded px-3 py-2">
                            <span>{{ $type->name }}</span>
                            <form method="POST" action="{{ route('vehicle-types.destroy', $type) }}" onsubmit="return confirm('¿Eliminar este tipo de vehículo?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline" type="submit">Eliminar</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No hay tipos de vehículos registrados.</p>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('vehicle-types.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nuevo tipo</label>
                        <input type="text" name="name" class="form-input w-full" required>
                    </div>
                    <div class="flex justify-end gap-2">
                        <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                        <x-primary-button>Agregar</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif
</x-app-layout>
