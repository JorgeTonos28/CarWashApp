@if($lowStockProducts->count() > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 w-full">
                <h3 class="text-sm leading-5 font-medium text-red-800">
                    Aviso de Escasez: {{ $lowStockProducts->count() }} Productos requieren atención
                </h3>
                <div class="mt-2 text-sm leading-5 text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($lowStockProducts as $product)
                            <li class="flex justify-between items-center mb-1">
                                <span>
                                    <strong>{{ $product->name }}</strong>
                                    (Actual: {{ $product->stock }} / Mín: {{ $product->low_stock_threshold ?? $defaultThreshold }})
                                </span>
                                <a href="{{ route('inventory.create', ['product_id' => $product->id]) }}" class="text-xs bg-red-100 hover:bg-red-200 text-red-800 px-2 py-1 rounded">
                                    + Dar Entrada
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
