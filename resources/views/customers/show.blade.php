<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Detalle del Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">{{ $customer->name }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><strong>Cédula:</strong> {{ $customer->cedula ?? '-' }}</div>
                <div><strong>Teléfono:</strong> {{ $customer->phone ?? '-' }}</div>
                <div><strong>Email:</strong> {{ $customer->email ?? '-' }}</div>
                <div><strong>Visitas:</strong> {{ $customer->tickets->count() }}</div>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold">Historial de Tickets</h3>
            </div>
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Ticket</th>
                        <th class="border px-4 py-2">Fecha</th>
                        <th class="border px-4 py-2">Total</th>
                        <th class="border px-4 py-2">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->tickets as $ticket)
                        <tr class="border-t">
                            <td class="px-4 py-2">#{{ $ticket->id }}</td>
                            <td class="px-4 py-2">{{ $ticket->created_at->format('d/m/Y h:i A') }}</td>
                            <td class="px-4 py-2">RD$ {{ number_format($ticket->total_amount, 2) }}</td>
                            <td class="px-4 py-2">
                                {{ $ticket->pending ? 'Pendiente' : 'Pagado' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                No hay tickets para este cliente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            <a href="{{ route('customers.index') }}" class="text-blue-600 hover:underline">&laquo; Volver a clientes</a>
        </div>
    </div>
</x-app-layout>
