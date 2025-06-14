<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Movimientos de Inventario') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('inventory.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Nueva Entrada
            </a>
        </div>

        <div class="mb-4">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Producto</label>
                    <input type="text" name="product_name" value="{{ request('product_name') }}" class="form-input mt-1 rounded" placeholder="Nombre" oninput="this.form.submit()">
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Desde</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input mt-1 rounded" onchange="this.form.submit()">
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Hasta</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input mt-1 rounded" onchange="this.form.submit()">
                </div>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">Fecha</th>
                        <th class="px-4 py-2 border">Producto</th>
                        <th class="px-4 py-2 border">Tipo</th>
                        <th class="px-4 py-2 border">Cantidad</th>
                        <th class="px-4 py-2 border">Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movements as $move)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $move->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $move->product->name }}</td>
                            <td class="px-4 py-2">{{ ucfirst($move->movement_type) }}</td>
                            <td class="px-4 py-2">{{ $move->quantity }}</td>
                            <td class="px-4 py-2">{{ optional($move->user)->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $movements->links() }}
        </div>
    </div>
</x-app-layout>
