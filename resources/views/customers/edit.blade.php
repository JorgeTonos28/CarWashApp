<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="form-input w-full mt-1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cédula</label>
                    <input type="text" name="cedula" value="{{ old('cedula', $customer->cedula) }}" class="form-input w-full mt-1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-input w-full mt-1">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="form-input w-full mt-1">
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
