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
                <div><strong>Total facturado:</strong> RD$ {{ number_format($customer->tickets->sum('total_amount'), 2) }}</div>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-lg font-semibold">Visitas diarias</h3>
                <form method="GET" class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Periodo</label>
                    <select name="timeframe" class="form-select" onchange="this.form.submit()">
                        <option value="1m" @selected($timeframe === '1m')>Último mes</option>
                        <option value="3m" @selected($timeframe === '3m')>Últimos 3 meses</option>
                        <option value="6m" @selected($timeframe === '6m')>Últimos 6 meses</option>
                        <option value="1y" @selected($timeframe === '1y')>Último año</option>
                        <option value="2y" @selected($timeframe === '2y')>Últimos 2 años</option>
                        <option value="5y" @selected($timeframe === '5y')>Últimos 5 años</option>
                    </select>
                </form>
            </div>
            <p class="text-sm text-gray-600">Total facturado en el período: <strong>RD$ {{ number_format($billedTotal, 2) }}</strong></p>
            <canvas id="customerVisitsChart" height="120"></canvas>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const customerCtx = document.getElementById('customerVisitsChart');
        if (customerCtx) {
            new Chart(customerCtx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Visitas',
                        data: @json($chartData),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 0,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { ticks: { maxTicksLimit: 8 } },
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        }
    </script>
</x-app-layout>
