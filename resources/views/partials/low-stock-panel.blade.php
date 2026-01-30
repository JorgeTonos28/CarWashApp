@if($lowStockProducts->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
            <div>
                <p class="text-sm font-semibold text-red-800">Aviso de escasez</p>
                <p class="text-sm text-red-700">
                    {{ $lowStockProducts->count() }} productos requieren reposición. Revisa el detalle para actuar rápidamente.
                </p>
            </div>
        </div>
        <button type="button" data-low-stock-open class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
            Ver detalle
        </button>
    </div>

    <div class="fixed inset-0 z-50 hidden" data-low-stock-modal>
        <div class="absolute inset-0 bg-slate-900/60" data-low-stock-close></div>
        <div class="relative mx-auto mt-16 w-full max-w-3xl rounded-xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Productos con stock bajo</h3>
                    <p class="text-sm text-slate-500">{{ $lowStockProducts->count() }} items con alerta de reposición.</p>
                </div>
                <button type="button" data-low-stock-close class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700" aria-label="Cerrar">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto px-6 py-4">
                <div class="space-y-3">
                    @foreach($lowStockProducts as $product)
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500">
                                    Actual: {{ $product->stock }} · Mínimo: {{ $product->low_stock_threshold ?? $defaultThreshold }}
                                </p>
                            </div>
                            <a href="{{ route('inventory.create', ['product_id' => $product->id]) }}" class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                + Dar entrada
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4">
                <button type="button" data-low-stock-close class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.querySelector('[data-low-stock-modal]');
            const openButton = document.querySelector('[data-low-stock-open]');
            const closeButtons = document.querySelectorAll('[data-low-stock-close]');

            if (!modal || !openButton) {
                return;
            }

            const openModal = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            openButton.addEventListener('click', openModal);
            closeButtons.forEach((button) => button.addEventListener('click', closeModal));
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
@endif
