<div class="mb-4 bg-white p-4 shadow sm:rounded-lg">
    Total facturado: <strong>RD$ {{ number_format($invoicedTotal,2) }}</strong>
</div>
<div class="bg-white shadow-sm sm:rounded-lg overflow-hidden max-h-96 overflow-y-auto">
    <table class="min-w-full table-auto border">
        <thead class="bg-gray-200">
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Cliente</th>
                <th class="border px-4 py-2">Facturaciones</th>
                <th class="border px-4 py-2">Descuento</th>
                <th class="border px-4 py-2">Total</th>
                <th class="border px-4 py-2">Cuenta</th>
                <th class="border px-4 py-2">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                @php $needsWasher = $ticket->washer_pending_amount > 0; @endphp
                <tr class="border-t cursor-pointer {{ $ticket->pending ? 'bg-red-100' : ($needsWasher ? 'bg-orange-100' : '') }}"
                    x-on:click="
                        if (selected === {{ $ticket->id }}) {
                            selected = null; selectedPending = false; selectedNoWasher = false; selectedHasWash = false; selectedCreated = null; selectedHasAssignedWasher = false;
                        } else {
                            selected = {{ $ticket->id }}; selectedPending = {{ $ticket->pending ? 'true' : 'false' }}; selectedNoWasher = {{ $needsWasher ? 'true' : 'false' }}; selectedHasWash = {{ $ticket->washes->isNotEmpty() ? 'true' : 'false' }}; selectedCreated = '{{ $ticket->created_at }}'; selectedHasAssignedWasher = {{ $ticket->washes->whereNotNull('washer_id')->isNotEmpty() ? 'true' : 'false' }};
                        }
                    "
                    :class="selected === {{ $ticket->id }} ? (selectedPending ? 'bg-red-300' : (selectedNoWasher ? 'bg-orange-300' : 'bg-blue-100')) : ''">
                    <td class="px-4 py-2">{{ $ticket->id }}</td>
                    <td class="px-4 py-2">{{ $ticket->customer_name }}</td>
                    <td class="px-4 py-2">
                        {{ $ticket->details->pluck('type')->unique()->map(fn($t) => match($t){
                            'service' => 'Lavado',
                            'product' => 'Productos',
                            'drink' => 'Tragos',
                            'extra' => 'Cargos',
                            'generic_service' => 'Servicios Genéricos',
                        })->implode(', ') }}
                    </td>
                    <td class="px-4 py-2">RD$ {{ number_format($ticket->discount_total, 2) }}</td>
                    <td class="px-4 py-2">RD$ {{ number_format($ticket->total_amount, 2) }}</td>
                    <td class="px-4 py-2">
                        {{ optional($ticket->bankAccount)->bank ? $ticket->bankAccount->bank.' - '.$ticket->bankAccount->account : '' }}
                    </td>
                    <td class="px-4 py-2">{{ $ticket->created_at->format('d/m/Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if (!empty($showOperationalList))
    <div class="mt-6 bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold">En Proceso</h3>
            <p class="text-sm text-gray-600">Detalle por vehículo y estado actual.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Ticket</th>
                        <th class="border px-4 py-2">Cliente</th>
                        <th class="border px-4 py-2">Placa</th>
                        <th class="border px-4 py-2">Modelo</th>
                        <th class="border px-4 py-2">Estado</th>
                        <th class="border px-4 py-2">Listo Desde</th>
                        <th class="border px-4 py-2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticketWashes ?? [] as $wash)
                        @php
                            $statusLabel = match($wash->status) {
                                'ready' => ['Listo', 'bg-green-100 text-green-800'],
                                'delivered' => ['Entregado', 'bg-blue-100 text-blue-800'],
                                default => ['Pendiente', 'bg-yellow-100 text-yellow-800'],
                            };
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">#{{ $wash->ticket_id }}</td>
                            <td class="px-4 py-2">
                                {{ $wash->ticket?->customer?->name ?? $wash->ticket?->customer_name ?? 'N/D' }}
                            </td>
                            <td class="px-4 py-2">{{ $wash->vehicle?->plate ?? 'N/D' }}</td>
                            <td class="px-4 py-2">{{ $wash->vehicle?->model ?? 'N/D' }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold {{ $statusLabel[1] }}">
                                    {{ $statusLabel[0] }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                {{ $wash->ready_at ? $wash->ready_at->format('d/m/Y h:i A') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                @if ($wash->status === 'pending')
                                    <form method="POST" action="{{ route('ticket-washes.ready', $wash) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-xs font-semibold text-green-700 border border-green-600 rounded hover:bg-green-50">
                                            ✅ Notificar listo
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                                No hay vehículos en proceso en este rango.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

    @foreach ($tickets as $ticket)
        <x-modal name="cancel-{{ $ticket->id }}" focusable>
        <form method="POST" action="{{ route('tickets.cancel', $ticket) }}" class="p-6 space-y-4">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">¿Cancelar este ticket?</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700">Concepto de cancelación</label>
                <input type="text" name="cancel_reason" class="form-input w-full" required>
            </div>
            @php
                $hasCommission = $ticket->washes->whereNotNull('washer_id')->isNotEmpty();
                $hasTip = $ticket->washes->sum('tip') > 0;
            @endphp
            @if($hasCommission)
                <div class="space-y-2 text-sm">
                    <label class="block font-medium">¿Mantener la comisión del lavador?</label>
                    <select name="keep_commission" class="form-select w-full" required>
                        <option value=""></option>
                        <option value="yes">Si</option>
                        <option value="no">No</option>
                    </select>
                </div>
            @endif
            @if($hasTip)
                <div class="space-y-2 text-sm">
                    <label class="block font-medium">¿Mantener la propina del lavador?</label>
                    <select name="keep_tip" class="form-select w-full" required>
                        <option value=""></option>
                        <option value="yes">Si</option>
                        <option value="no">No</option>
                    </select>
                </div>
            @endif
            <div class="flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-danger-button class="ms-3">Confirmar</x-danger-button>
            </div>
        </form>
    </x-modal>
    @if($ticket->pending)
    <x-modal name="pay-{{ $ticket->id }}" focusable>
        <form method="POST" action="{{ route('tickets.pay', $ticket) }}" class="p-6 space-y-4" x-data="payForm({{ $ticket->total_amount }})">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Monto Pagado</label>
                <input type="number" name="paid_amount" step="0.01" x-model.number="paid" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                <select name="payment_method" x-model="method" class="form-select w-full" required x-on:change="syncMixed()">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="mixto">Mixto</option>
                </select>
            </div>
            <div x-show="method === 'transferencia'">
                <label class="block text-sm font-medium text-gray-700">Cuenta Bancaria</label>
                <select name="bank_account_id" class="form-select w-full" x-bind:disabled="method !== 'transferencia'">
                    <option value="">-- Seleccionar --</option>
                    @foreach($bankAccounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->bank }} - {{ $acc->account }}</option>
                    @endforeach
                </select>
            </div>
            <div x-show="method === 'mixto'" class="space-y-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Monto Efectivo</label>
                    <input type="number" name="mixed_cash_amount" step="0.01" min="0" class="form-input w-full" x-model.number="mixedCash" x-on:input="syncMixed()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Monto Tarjeta</label>
                    <input type="number" name="mixed_card_amount" step="0.01" min="0" class="form-input w-full" x-model.number="mixedCard" x-on:input="syncMixed()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Monto Transferencia</label>
                    <input type="number" name="mixed_transfer_amount" step="0.01" min="0" class="form-input w-full" x-model.number="mixedTransfer" x-on:input="syncMixed()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cuenta Bancaria</label>
                    <select name="bank_account_id" class="form-select w-full" x-bind:disabled="method !== 'mixto'">
                        <option value="">-- Seleccionar --</option>
                        @foreach($bankAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->bank }} - {{ $acc->account }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="text-sm space-x-4">
                <span>Total: RD$ {{ number_format($ticket->total_amount,2) }}</span>
                <span>Cambio: RD$ <span x-text="formatCurrency(change)"></span></span>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-primary-button class="ms-3">Confirmar</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif
    <x-modal name="view-{{ $ticket->id }}" focusable>
        <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="paid_amount" value="{{ $ticket->paid_amount }}">
            <div class="text-sm space-y-1">
                <p><strong>Cliente:</strong> {{ $ticket->customer_name }}</p>
                @if($ticket->customer_phone)
                    <p><strong>Teléfono:</strong> {{ $ticket->customer_phone }}</p>
                @endif
                <p><strong>Fecha:</strong> {{ $ticket->created_at->format('d/m/Y h:i A') }}</p>
                @if($ticket->vehicle)
                    <p><strong>Placa:</strong> {{ $ticket->vehicle->plate }}</p>
                    <p><strong>Marca:</strong> {{ $ticket->vehicle->brand }}</p>
                    <p><strong>Modelo:</strong> {{ $ticket->vehicle->model }}</p>
                    <p><strong>Color:</strong> {{ $ticket->vehicle->color }}</p>
                    @if($ticket->vehicle->year)
                        <p><strong>Año:</strong> {{ $ticket->vehicle->year }}</p>
                    @endif
                @endif
                @if($ticket->vehicleType)
                    <p><strong>Tipo de Vehículo:</strong> {{ $ticket->vehicleType->name }}</p>
                @endif
            </div>
            <div>
                <h3 class="font-semibold text-sm mb-1">Detalles</h3>
                <ul class="text-sm list-disc ps-5 space-y-1">
                    @foreach($ticket->details as $d)
                        <li>
                            {{ match($d->type){
                                'service' => $d->service->name ?? 'Servicio',
                                'product' => $d->product->name ?? 'Producto',
                                'drink' => $d->drink->name ?? 'Trago',
                                'extra' => $d->description ?? 'Cargo',
                                'generic_service' => $d->genericServiceVariant
                                    ? $d->genericServiceVariant->service?->name.' - '.$d->genericServiceVariant->name
                                    : 'Servicio Genérico',
                            } }} x{{ $d->quantity }} - RD$ {{ number_format($d->unit_price,2) }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="text-sm space-y-1">
                <p><strong>Descuento:</strong> RD$ {{ number_format($ticket->discount_total, 2) }}</p>
                <p><strong>Total:</strong> RD$ {{ number_format($ticket->total_amount, 2) }}</p>
            </div>
            @php
                $hasServiceDetails = $ticket->details->where('type', 'service')->isNotEmpty();
                $hasWashServices = $ticket->washes->filter(fn($wash) => $wash->details->where('type', 'service')->isNotEmpty())->isNotEmpty();
                $showWasherSection = $hasServiceDetails || $hasWashServices;
            @endphp
            @if($showWasherSection)
                @php
                    $canChangeWasher = true;
                    if(!$ticket->pending){
                        foreach($ticket->washes as $w){
                            $tipPaid = \App\Models\WasherMovement::where('ticket_id',$ticket->id)
                                ->where('washer_id',$w->washer_id)
                                ->where('description','like','[P]%')
                                ->where('paid',true)
                                ->exists();
                            if($w->washer_paid || $tipPaid){ $canChangeWasher = false; break; }
                        }
                    }
                @endphp
                <div class="space-y-2">
                    @foreach($ticket->washes as $wash)
                        <div class="border rounded p-2">
                            <p class="text-sm font-semibold">
                                @if($wash->vehicle)
                                    {{ $wash->vehicle->brand }} | {{ $wash->vehicle->model }} | {{ $wash->vehicle->color }} | {{ $wash->vehicle->year }}
                                @else
                                    Vehículo no disponible
                                @endif
                                @if($wash->vehicleType)
                                    | {{ $wash->vehicleType->name }}
                                @endif
                            </p>
                            <p class="text-sm">Servicios: {{ $wash->details->where('type','service')->map(fn($d)=>$d->service->name)->implode(', ') }}</p>
                            @if($wash->tip > 0)
                                <p class="text-sm">Propina: RD$ {{ number_format($wash->tip,2) }}</p>
                            @endif
                            @if($canChangeWasher)
                            <div class="mt-1">
                                <label class="block text-sm font-medium text-gray-700">Lavador</label>
                                <select name="washers[{{ $wash->id }}]" class="form-select w-full">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($washers as $w)
                                        <option value="{{ $w->id }}" {{ $w->id == $wash->washer_id ? 'selected' : '' }}>{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
            @php
                $mixCash = $ticket->payments->where('payment_method', 'efectivo')->sum('amount');
                $mixCard = $ticket->payments->where('payment_method', 'tarjeta')->sum('amount');
                $mixTransfer = $ticket->payments->where('payment_method', 'transferencia')->sum('amount');
            @endphp
            <div x-data="{method: '{{ $ticket->payment_method }}', mixedCash: {{ $mixCash }}, mixedCard: {{ $mixCard }}, mixedTransfer: {{ $mixTransfer }}}">
                <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                <select name="payment_method" x-model="method" class="form-select w-full">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="mixto">Mixto</option>
                </select>
                <div x-show="method === 'transferencia'" class="mt-2">
                    <label class="block text-sm font-medium text-gray-700">Cuenta Bancaria</label>
                    <select name="bank_account_id" class="form-select w-full" x-bind:disabled="method !== 'transferencia'">
                        <option value="">-- Seleccionar --</option>
                        @foreach($bankAccounts as $acc)
                            <option value="{{ $acc->id }}" {{ $acc->id == $ticket->bank_account_id ? 'selected' : '' }}>{{ $acc->bank }} - {{ $acc->account }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="method === 'mixto'" class="mt-2 space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Monto Efectivo</label>
                        <input type="number" name="mixed_cash_amount" step="0.01" min="0" class="form-input w-full" x-model.number="mixedCash">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Monto Tarjeta</label>
                        <input type="number" name="mixed_card_amount" step="0.01" min="0" class="form-input w-full" x-model.number="mixedCard">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Monto Transferencia</label>
                        <input type="number" name="mixed_transfer_amount" step="0.01" min="0" class="form-input w-full" x-model.number="mixedTransfer">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cuenta Bancaria</label>
                        <select name="bank_account_id" class="form-select w-full" x-bind:disabled="method !== 'mixto'">
                            <option value="">-- Seleccionar --</option>
                            @foreach($bankAccounts as $acc)
                                <option value="{{ $acc->id }}" {{ $acc->id == $ticket->bank_account_id ? 'selected' : '' }}>{{ $acc->bank }} - {{ $acc->account }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cerrar</x-secondary-button>
                <x-primary-button class="ms-3">Guardar</x-primary-button>
            </div>
        </form>
    </x-modal>
@endforeach
