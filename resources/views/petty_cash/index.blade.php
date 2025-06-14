<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Caja Chica') }}
        </h2>
    </x-slot>

    <div x-data="pettyCashFilter()" x-init="init()" class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 font-medium text-sm text-green-600">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <a href="{{ route('petty-cash.create') }}" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Nuevo Gasto</a>
        </div>

        <div class="mb-4">
            <form method="GET" x-ref="form" class="flex flex-wrap items-end gap-4">
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
            @include('petty_cash.partials.table', ['expenses' => $expenses])
        </div>
        <script>
            function pettyCashFilter() {
                return {
                    start_date: '{{ request('start_date') }}',
                    end_date: '{{ request('end_date') }}',
                    fetchData() {
                        axios.get('{{ route('petty-cash.index') }}', {
                            params: {
                                start_date: this.start_date,
                                end_date: this.end_date
                            }
                        }).then(res => { this.$refs.table.innerHTML = res.data; });
                    },
                    init() {
                        this.$watch('start_date', () => this.fetchData());
                        this.$watch('end_date', () => this.fetchData());
                    }
                }
            }
        </script>
    </div>
</x-app-layout>
