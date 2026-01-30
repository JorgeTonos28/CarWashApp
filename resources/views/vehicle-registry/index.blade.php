<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Parque Vehicular') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Placa</th>
                        <th class="border px-4 py-2">Marca</th>
                        <th class="border px-4 py-2">Modelo</th>
                        <th class="border px-4 py-2">Cliente Dueño</th>
                        <th class="border px-4 py-2">Última visita</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicles as $vehicle)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $vehicle->plate }}</td>
                            <td class="px-4 py-2">{{ $vehicle->brand }}</td>
                            <td class="px-4 py-2">{{ $vehicle->model }}</td>
                            <td class="px-4 py-2">
                                {{ $vehicle->customer?->name ?? $vehicle->customer_name ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $vehicle->last_visit ? $vehicle->last_visit->format('d/m/Y h:i A') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
