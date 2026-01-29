<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Servicio') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow sm:rounded-lg">
        <form action="{{ route('services.store') }}" method="POST" class="space-y-6" x-data="{ tab: 'vehicles', genericRows: @js(old('prices_generic', [])) }" x-init="if (genericRows.length === 0) { genericRows = [{ label: '', price: '' }]; }">
            @csrf

            <div>
                <label for="name" class="block font-medium text-sm text-gray-700">Nombre</label>
                <input type="text" name="name" required class="form-input w-full">
            </div>

           <div>
               <label for="description" class="block font-medium text-sm text-gray-700">Descripción</label>
               <textarea name="description" class="form-input w-full"></textarea>
           </div>

            <div class="space-y-4">
                <div class="flex gap-2 border-b">
                    <button type="button" class="px-4 py-2" :class="tab === 'vehicles' ? 'border-b-2 border-blue-600 text-blue-700' : 'text-gray-500'" @click="tab = 'vehicles'">Por Vehículo</button>
                    <button type="button" class="px-4 py-2" :class="tab === 'generic' ? 'border-b-2 border-blue-600 text-blue-700' : 'text-gray-500'" @click="tab = 'generic'">Otros / Genéricos</button>
                </div>

                <div x-show="tab === 'vehicles'" class="space-y-2">
                    <label class="block font-medium text-sm text-gray-700">Precios por tipo de vehículo</label>
                    @forelse ($vehicleTypes as $type)
                        <div class="flex items-center gap-2">
                            <span class="w-32">{{ $type->name }}</span>
                            <input type="number" name="prices_vehicles[{{ $type->id }}]" step="0.01" value="{{ old('prices_vehicles.' . $type->id) }}" class="form-input w-full">
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No hay tipos de vehículos activos.</p>
                    @endforelse
                </div>

                <div x-show="tab === 'generic'" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block font-medium text-sm text-gray-700">Variantes genéricas</label>
                        <button type="button" class="text-blue-600 text-sm" @click="genericRows.push({ label: '', price: '' })">+ Agregar Variante</button>
                    </div>
                    <template x-for="(row, index) in genericRows" :key="index">
                        <div class="flex flex-wrap gap-2 items-center">
                            <input type="text" class="form-input flex-1" placeholder="Nombre de la variante" x-model="row.label" :name="`prices_generic[${index}][label]`">
                            <input type="number" step="0.01" class="form-input w-40" placeholder="Precio" x-model="row.price" :name="`prices_generic[${index}][price]`">
                            <button type="button" class="text-red-600 text-sm" @click="genericRows.splice(index, 1)">Eliminar</button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center">
                <label class="mr-2 text-sm">Activo</label>
                <input type="checkbox" name="active" value="1" checked>
            </div>


            <div class="flex items-center gap-4">
                <x-primary-button>Guardar</x-primary-button>
                <x-secondary-button type="button" onclick="window.location='{{ route('services.index') }}'">Cancelar</x-secondary-button>
            </div>
        </form>
        </div>
    </div>
</x-app-layout>
