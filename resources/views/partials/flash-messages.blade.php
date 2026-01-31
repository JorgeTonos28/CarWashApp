@if (session('success'))
    <x-modal name="flash-success" :show="true">
        <div class="p-6 text-slate-900">
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/15 text-emerald-600">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </span>
                <div>
                    <h2 class="text-lg font-semibold">Éxito</h2>
                    <p class="text-sm text-slate-600">Operación completada con éxito.</p>
                </div>
            </div>
            <p class="mt-4 text-emerald-600 font-semibold">{{ session('success') }}</p>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'flash-success' }))">Cerrar</x-secondary-button>
            </div>
        </div>
    </x-modal>
@endif

@if (session('error'))
    <x-modal name="flash-error" :show="true">
        <div class="p-6 text-slate-900">
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-500/15 text-rose-600">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </span>
                <div>
                    <h2 class="text-lg font-semibold">Error</h2>
                    <p class="text-sm text-slate-600">Necesitamos tu atención.</p>
                </div>
            </div>
            <p class="mt-4 text-rose-600 font-semibold">{{ session('error') }}</p>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'flash-error' }))">Cerrar</x-secondary-button>
            </div>
        </div>
    </x-modal>
@endif
