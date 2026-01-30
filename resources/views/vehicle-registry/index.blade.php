<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Parque Vehicular') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="flex items-center gap-2">
            <input type="text" id="vehicle-search" placeholder="Buscar por placa" class="form-input w-64">
        </div>
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border" id="vehicles-table">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Placa</th>
                        <th class="border px-4 py-2">Marca</th>
                        <th class="border px-4 py-2">Modelo</th>
                        <th class="border px-4 py-2">Visitas</th>
                        <th class="border px-4 py-2">Última visita</th>
                        <th class="border px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicles as $vehicle)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $vehicle->plate }}</td>
                            <td class="px-4 py-2">{{ $vehicle->brand }}</td>
                            <td class="px-4 py-2">{{ $vehicle->model }}</td>
                            <td class="px-4 py-2">
                                {{ $vehicle->visits_count ?? 0 }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $vehicle->last_visit ? $vehicle->last_visit->format('d/m/Y h:i A') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('vehicle-registry.show', $vehicle) }}" class="text-blue-600 hover:underline">Ver detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
<script>
    const vehicleSearch = document.getElementById('vehicle-search');
    const vehiclesTable = document.getElementById('vehicles-table');
    if (vehicleSearch && vehiclesTable) {
        const rows = Array.from(vehiclesTable.querySelectorAll('tbody tr'));
        vehicleSearch.addEventListener('input', (event) => {
            const term = event.target.value.toLowerCase();
            rows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                row.classList.toggle('hidden', term && !text.includes(term));
            });
        });
    }
</script>
