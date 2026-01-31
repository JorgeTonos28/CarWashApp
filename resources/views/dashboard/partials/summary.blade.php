<div class="space-y-8">
    <div class="grid gap-4 md:grid-cols-2">
        <div class="stat-card">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Total en caja</p>
                    <p class="stat-value">RD$ {{ number_format($totalFacturado, 2) }}</p>
                </div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500/20 text-cyan-200">
                    <i class="fa-solid fa-coins text-xl"></i>
                </span>
            </div>
            <div class="mt-6 badge-pill">
                <i class="fa-solid fa-arrow-trend-up"></i>
                Flujo diario consolidado
            </div>
        </div>
        @if(Auth::user()->role === 'admin')
        <div class="stat-card">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="stat-label">Beneficio bruto</p>
                    <p class="stat-value">RD$ {{ number_format($grossProfit, 2) }}</p>
                </div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-200">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </span>
            </div>
            <div class="mt-6 badge-pill">
                <i class="fa-solid fa-sparkles"></i>
                Margen optimizado
            </div>
        </div>
        @endif
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="card max-h-96 overflow-y-auto">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Cuentas por cobrar</h3>
                    <p class="text-sm text-slate-500">Total: <strong>RD$ {{ number_format($accountsReceivable, 2) }}</strong></p>
                </div>
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-500">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </span>
            </div>
            <table class="table-modern mt-4">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingTickets as $t)
                        <tr>
                            <td>{{ $t->created_at->format('d/m h:i A') }}</td>
                            <td>{{ $t->customer_name }}</td>
                            <td>
                                {{ $t->details->pluck('type')->unique()->map(fn($tt) => match($tt){
                                    'service' => 'Lavado',
                                    'product' => 'Producto',
                                    'drink' => 'Tragos',
                                    'extra' => 'Cargo',
                                    default => ''
                                })->implode(', ') }}
                            </td>
                            <td class="text-right">RD$ {{ number_format($t->total_amount,2) }}</td>
                        </tr>
                    @endforeach
                    @foreach($washerDebts as $w)
                        <tr>
                            <td>{{ $w->created_at->format('d/m h:i A') }}</td>
                            <td>Lavador - {{ $w->washer->name }}</td>
                            <td>{{ $w->description }}</td>
                            <td class="text-right">RD$ {{ number_format(abs($w->amount),2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card space-y-2">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Resumen</h3>
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-100 text-sky-500">
                    <i class="fa-solid fa-layer-group"></i>
                </span>
            </div>
            <div class="grid gap-2 text-sm text-slate-600">
                <p>Efectivo: <strong class="text-slate-900">RD$ {{ number_format($cashTotal, 2) }}</strong></p>
                <p>Tarjeta: <strong class="text-slate-900">RD$ {{ number_format($cardPayments, 2) }}</strong></p>
                <p>Transferencias: <strong class="text-slate-900">RD$ {{ number_format($transferTotal, 2) }}</strong></p>
                <p>Total facturado: <strong class="text-slate-900">RD$ {{ number_format($invoicedTotal, 2) }}</strong></p>
                <p>Caja chica: <strong class="text-slate-900">RD$ {{ number_format($pettyCashAmount, 2) }}</strong></p>
                <p>Gastos de caja chica: <strong class="text-slate-900">RD$ {{ number_format($pettyCashTotal, 2) }}</strong></p>
                <p>Para lavadores: <strong class="text-slate-900">RD$ {{ number_format($washerPayDue, 2) }}</strong></p>
                <p>Ventas de lavados: <strong class="text-slate-900">RD$ {{ number_format($serviceTotal, 2) }}</strong></p>
                <p>Ventas de productos: <strong class="text-slate-900">RD$ {{ number_format($productTotal, 2) }}</strong></p>
                <p>Ventas de tragos: <strong class="text-slate-900">RD$ {{ number_format($drinkTotal, 2) }}</strong></p>
                <p>Ventas de servicios genéricos: <strong class="text-slate-900">RD$ {{ number_format($genericServiceTotal, 2) }}</strong></p>
            </div>
            @if(Auth::user()->role === 'admin')
                <!-- Beneficio bruto se muestra en la sección superior -->
            @endif
        </div>
    </div>
    <div class="card overflow-hidden">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Vehículos en Proceso</h3>
                <p class="text-sm text-slate-500">Monitoreo en tiempo real.</p>
            </div>
            <span class="badge-pill bg-slate-900/90 text-white">
                <i class="fa-solid fa-car-side"></i>
                Servicio activo
            </span>
        </div>
        <div class="max-h-80 overflow-x-auto overflow-y-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Placa / Modelo</th>
                        <th>Cliente</th>
                        <th>Lavador</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ticketWashes ?? [] as $wash)
                        <tr class="border-t">
                            <td class="font-semibold text-slate-900">#{{ $wash->ticket_id }}</td>
                            <td>
                                {{ $wash->vehicle?->plate ?? 'N/D' }} - {{ $wash->vehicle?->model ?? 'N/D' }}
                            </td>
                            <td>
                                {{ $wash->ticket?->customer?->name ?? $wash->ticket?->customer_name ?? 'N/D' }}
                            </td>
                            <td>
                                {{ $wash->washer?->name ?? 'Sin asignar' }}
                            </td>
                            <td>
                                @if($wash->status === 'ready')
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Listo</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                @if($wash->status === 'pending')
                                    <form method="POST" action="{{ route('ticket-washes.ready', $wash) }}">
                                        @csrf
                                        <button type="submit" class="btn-success">
                                            <i class="fa-solid fa-circle-check"></i>
                                            Marcar listo
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs font-semibold text-slate-500">
                                        <i class="fa-solid fa-bell me-1"></i>
                                        Esperando entrega
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-3 text-center text-sm text-slate-500">
                                No hay vehículos en proceso para este rango.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div class="card">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Últimos gastos de caja chica</h3>
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-500">
                    <i class="fa-solid fa-receipt"></i>
                </span>
            </div>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                @foreach($lastExpenses as $expense)
                    <li class="flex items-center justify-between gap-3">
                        <span>{{ $expense->created_at->format('d/m h:i A') }} - {{ $expense->description }}</span>
                        <span class="font-semibold text-slate-900">RD$ {{ number_format($expense->amount,2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Transferencias Bancarias</h3>
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-500">
                    <i class="fa-solid fa-building-columns"></i>
                </span>
            </div>
            <p class="mt-2 text-sm text-slate-600">Total: <strong class="text-slate-900">RD$ {{ number_format($transferTotal, 2) }}</strong></p>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                @foreach($bankAccountTotals as $acc)
                    <li class="flex items-center justify-between gap-3">
                        <span>{{ $acc->bankAccount->bank }} - {{ $acc->bankAccount->account }}</span>
                        <span class="font-semibold text-slate-900">RD$ {{ number_format($acc->total, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="card space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Vehículos atendidos</h3>
                <p class="text-sm text-slate-500">Total: <strong class="text-slate-900">{{ number_format($vehicleChartTotal ?? 0) }}</strong></p>
            </div>
            <span class="badge-pill bg-slate-900/90 text-white">
                <i class="fa-solid fa-road"></i>
                Últimos 30 días
            </span>
        </div>
        <script type="application/json" id="dashboard-chart-labels">@json($vehicleChartLabels ?? [])</script>
        <script type="application/json" id="dashboard-chart-data">@json($vehicleChartData ?? [])</script>
        <canvas
            id="dashboardChart"
            height="120"
        ></canvas>
    </div>
    <div>
        <h3 class="text-lg font-semibold mb-3 text-white">Movimientos</h3>
        @include('dashboard.partials.movements-table', ['movements' => $movements])
    </div>
</div>
