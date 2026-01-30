<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajustes del Sistema</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Acceso desde Dispositivos Móviles</h3>
                            <p class="text-sm text-gray-500">Permitir que los usuarios accedan a la aplicación desde celulares/tablets.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_mobile_access" value="1" class="sr-only peer" {{ $settings->allow_mobile_access ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <hr class="my-6">

                    <div class="mb-6 max-w-xs">
                        <x-input-label for="default_low_stock" value="Stock Mínimo por Defecto" />
                        <p class="text-xs text-gray-500 mb-2">Se usará este valor para productos que no tengan un aviso de escasez específico.</p>
                        <x-text-input id="default_low_stock" name="default_low_stock" type="number" class="w-full" :value="$settings->default_low_stock" required />
                    </div>

                    <hr class="my-6">

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Correo de vehículo listo</h3>
                            <p class="text-sm text-gray-500">
                                Personaliza el asunto y el contenido del correo. Variables disponibles: {cliente}, {placa}, {modelo}, {marca}, {ticket}.
                            </p>
                        </div>
                        <div>
                            <x-input-label for="vehicle_ready_subject" value="Asunto" />
                            <x-text-input id="vehicle_ready_subject" name="vehicle_ready_subject" type="text" class="w-full" :value="$settings->vehicle_ready_subject" />
                        </div>
                        <div>
                            <x-input-label for="vehicle_ready_greeting" value="Saludo" />
                            <x-text-input id="vehicle_ready_greeting" name="vehicle_ready_greeting" type="text" class="w-full" :value="$settings->vehicle_ready_greeting" />
                        </div>
                        <div>
                            <x-input-label for="vehicle_ready_body" value="Mensaje principal" />
                            <textarea id="vehicle_ready_body" name="vehicle_ready_body" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4">{{ $settings->vehicle_ready_body }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="vehicle_ready_footer" value="Pie de mensaje" />
                            <x-text-input id="vehicle_ready_footer" name="vehicle_ready_footer" type="text" class="w-full" :value="$settings->vehicle_ready_footer" />
                        </div>
                    </div>

                    <x-primary-button>Guardar Ajustes</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
