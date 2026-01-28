<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lavadores') }}
        </h2>
    </x-slot>

    <div x-data="filterTable('{{ route('washers.index') }}')" class="py-4 max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-wrap items-end gap-4 mb-4">
                <form method="GET" x-ref="form" class="flex items-end gap-2">
                    <div>
                        <label class="block text-sm">Desde</label>
                        <input type="date" name="start" value="{{ $filters['start'] ?? '' }}" class="form-input" @change="fetchTable()">
                    </div>
                    <div>
                        <label class="block text-sm">Hasta</label>
                        <input type="date" name="end" value="{{ $filters['end'] ?? '' }}" class="form-input" @change="fetchTable()">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="px-3 py-2 bg-gray-200 rounded" @click="
                            const today = new Date().toISOString().slice(0,10);
                            $refs.form.start.value = today;
                            $refs.form.end.value = today;
                            fetchTable();
                        ">Hoy</button>
                    </div>
                </form>
                <div class="flex items-center gap-2">
                    <a href="{{ route('washers.create') }}" class="btn-primary">Nuevo Lavador</a>
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'commission-settings')" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        Config. Comisiones
                    </button>
                </div>
            </div>

            <div x-html="tableHtml"></div>
    </div>

    <x-modal name="commission-settings" :show="$errors->has('default_amount')" focusable>
        <form method="post" action="{{ route('washers.settings.update') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">
                Configuración de Comisiones
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Define el monto fijo que ganarán los lavadores por cada servicio.
            </p>

            <div class="mt-6">
                <x-input-label for="default_amount" value="Monto Fijo por Lavado" />
                @php
                    $currentCommission = \App\Models\CommissionSetting::first()->default_amount ?? 100;
                @endphp
                <x-text-input id="default_amount" name="default_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('default_amount', $currentCommission)" required />
                <x-input-error :messages="$errors->get('default_amount')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>
                <x-primary-button class="ml-3">
                    Guardar Cambios
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
