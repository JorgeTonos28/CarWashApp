<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Centro de control</p>
                <h2 class="text-2xl font-semibold text-slate-900">Panel Operativo</h2>
            </div>
            <div class="badge-pill">
                <i class="fa-solid fa-clock"></i>
                <span>{{ now()->format('d M Y, h:i A') }}</span>
            </div>
        </div>
    </x-slot>

    <div x-data="filterTable('{{ route('dashboard') }}', { onUpdate() { this.$nextTick(() => window.initDashboardVehicleChart?.()); } })" class="mx-auto max-w-7xl space-y-8">
        @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
            <div class="card-muted flex flex-wrap items-center justify-between gap-4 border border-orange-500/30 bg-gradient-to-r from-orange-500/20 via-slate-900/80 to-slate-950/80">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-orange-200">¡Atención!</p>
                    <p class="text-lg font-semibold text-white">Hay {{ $lowStockProducts->count() }} productos con stock bajo.</p>
                    <p class="text-sm text-orange-100/70">Revisa el inventario para reabastecer.</p>
                </div>
                <a href="{{ route('inventory.index') }}" class="btn-light">
                    <i class="fa-solid fa-warehouse"></i>
                    Ver Inventario
                </a>
            </div>
        @endif

        <div class="card flex flex-wrap items-end gap-4">
            <form method="GET" x-ref="form" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-500">Desde</label>
                    <input type="date" name="start" value="{{ $filters['start'] ?? '' }}" class="form-input" @change="fetchTable()">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-500">Hasta</label>
                    <input type="date" name="end" value="{{ $filters['end'] ?? '' }}" class="form-input" @change="fetchTable()">
                </div>
            </form>
            <button type="button" class="btn-neutral" @click="
                const today = new Date().toLocaleDateString('en-CA');
                document.querySelector('[name=\'start\']').value = today;
                document.querySelector('[name=\'end\']').value = today;
                fetchTable();
            ">
                <i class="fa-solid fa-bolt"></i>
                Ahora
            </button>
            <a href="{{ route('tickets.create') }}" class="btn-primary">
                <i class="fa-solid fa-receipt"></i>
                Nuevo Ticket
            </a>
            <a href="{{ route('petty-cash.create') }}" class="btn-secondary">
                <i class="fa-solid fa-money-bill-wave"></i>
                Nuevo Gasto
            </a>
            <a href="{{ route('dashboard.download', request()->all()) }}" class="btn-light">
                <i class="fa-solid fa-download"></i>
                Descargar
            </a>
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
                        borderColor: '#22d3ee',
                        backgroundColor: 'rgba(34, 211, 238, 0.18)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#38bdf8',
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { ticks: { maxTicksLimit: 8, color: '#cbd5f5' }, grid: { color: 'rgba(148,163,184,0.2)' } },
                        y: { beginAtZero: true, ticks: { precision: 0, color: '#cbd5f5' }, grid: { color: 'rgba(148,163,184,0.2)' } }
                    }
                }
            });
        };
        document.addEventListener('DOMContentLoaded', () => {
            window.initDashboardVehicleChart?.(@json($vehicleChartLabels ?? []), @json($vehicleChartData ?? []));
        });
    </script>
</x-app-layout>
