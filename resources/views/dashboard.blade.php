<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel') }}
        </h2>
    </x-slot>

    <div x-data="filterTable('{{ route('dashboard') }}', { onUpdate() { this.$nextTick(() => window.initDashboardVehicleChart?.()); } })" class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4 flex justify-between items-center">
                <div>
                    <p class="font-bold">¡Atención!</p>
                    <p>Hay {{ $lowStockProducts->count() }} productos con stock bajo.</p>
                </div>
                <a href="{{ route('inventory.index') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                    Ver Inventario
                </a>
            </div>
        @endif

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
            </form>
            <button type="button" class="px-4 py-2 bg-gray-300 rounded" @click="
                const today = new Date().toLocaleDateString('en-CA');
                document.querySelector('[name=\'start\']').value = today;
                document.querySelector('[name=\'end\']').value = today;
                fetchTable();
            ">Ahora</button>
            <a href="{{ route('tickets.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Nuevo Ticket</a>
            <a href="{{ route('petty-cash.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Nuevo Gasto</a>
            <a href="{{ route('dashboard.download', request()->all()) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Descargar</a>
        </div>

        <div x-html="tableHtml">
            @include('dashboard.partials.summary', [
                'totalFacturado' => $totalFacturado,
                'cashTotal' => $cashTotal,
                'transferTotal' => $transferTotal,
                'bankAccountTotals' => $bankAccountTotals,
                'washerPayDue' => $washerPayDue,
                'serviceTotal' => $serviceTotal,
                'productTotal' => $productTotal,
                'drinkTotal' => $drinkTotal,
                'grossProfit' => $grossProfit,
                'lastExpenses' => $lastExpenses,
                'movements' => $movements,
                'pettyCashTotal' => $pettyCashTotal,
                'accountsReceivable' => $accountsReceivable,
                'pendingTickets' => $pendingTickets,
                'vehicleChartLabels' => $vehicleChartLabels,
                'vehicleChartData' => $vehicleChartData,
                'vehicleChartTotal' => $vehicleChartTotal,
                'ticketWashes' => $ticketWashes,
            ])
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.initDashboardVehicleChart = (labels = null, values = null) => {
            const canvas = document.getElementById('dashboardChart');
            if (!canvas) {
                return;
            }
            if (!window.Chart) {
                setTimeout(() => window.initDashboardVehicleChart?.(labels, values), 200);
                return;
            }
            const labelsNode = document.getElementById('dashboard-chart-labels');
            const dataNode = document.getElementById('dashboard-chart-data');
            const resolvedLabels = labels ?? (labelsNode ? JSON.parse(labelsNode.textContent || '[]') : []);
            const resolvedValues = values ?? (dataNode ? JSON.parse(dataNode.textContent || '[]') : []);

            if (canvas._chartInstance) {
                canvas._chartInstance.destroy();
            }

            canvas._chartInstance = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: resolvedLabels,
                    datasets: [{
                        label: 'Vehículos',
                        data: resolvedValues,
                        borderColor: '#0f766e',
                        backgroundColor: 'rgba(15, 118, 110, 0.1)',
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
        };
        document.addEventListener('DOMContentLoaded', () => {
            window.initDashboardVehicleChart?.(@json($vehicleChartLabels ?? []), @json($vehicleChartData ?? []));
        });
    </script>
</x-app-layout>
