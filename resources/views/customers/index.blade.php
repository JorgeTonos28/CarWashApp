<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Clientes') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre o cédula" class="form-input w-64">
                <button type="submit" class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">Buscar</button>
            </form>
            <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Nuevo Cliente</a>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Nombre</th>
                        <th class="border px-4 py-2">Cédula</th>
                        <th class="border px-4 py-2">Teléfono</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Visitas</th>
                        <th class="border px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $customer->name }}</td>
                            <td class="px-4 py-2">{{ $customer->cedula ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $customer->email ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $customer->tickets_count }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:underline">Ver</a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="text-gray-600 hover:underline">Editar</a>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('¿Deseas eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($customers->isEmpty())
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">No hay clientes registrados.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
