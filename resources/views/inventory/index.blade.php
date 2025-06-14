<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Movimientos de Inventario') }}
        </h2>
    </x-slot>

    <div x-data="inventoryFilter()" x-init="init()" class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            <form method="GET" x-ref="form" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Producto</label>
                    <input type="text" name="product_name" x-model="product_name" class="form-input mt-1 rounded" placeholder="Nombre">
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Desde</label>
                    <input type="date" name="start_date" x-model="start_date" class="form-input mt-1 rounded">
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Hasta</label>
                    <input type="date" name="end_date" x-model="end_date" class="form-input mt-1 rounded">
                </div>
            </form>
        </div>

        <div x-ref="table">
            @include('inventory.partials.table', ['movements' => $movements])
        </div>
        <script>
            function inventoryFilter() {
                return {
                    product_name: '{{ request('product_name') }}',
                    start_date: '{{ request('start_date') }}',
                    end_date: '{{ request('end_date') }}',
                    fetchData(page = 1) {
                        axios.get('{{ route('inventory.index') }}', {
                            params: {
                                page,
                                product_name: this.product_name,
                                start_date: this.start_date,
                                end_date: this.end_date
                            }
                        }).then(res => { this.$refs.table.innerHTML = res.data; });
                    },
                    init() {
                        this.$watch('product_name', () => this.fetchData());
                        this.$watch('start_date', () => this.fetchData());
                        this.$watch('end_date', () => this.fetchData());

                        this.$refs.table.addEventListener('click', (e) => {
                            if (e.target.tagName === 'A' && e.target.closest('ul')) {
                                e.preventDefault();
                                const url = new URL(e.target.href);
                                const page = url.searchParams.get('page') || 1;
                                this.fetchData(page);
                            }
                        });
                    }
                }
            }
        </script>
    </div>
</x-app-layout>
