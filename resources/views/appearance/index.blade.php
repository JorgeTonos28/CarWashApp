<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apariencia') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow sm:rounded-lg">
            <form method="POST" action="{{ route('appearance.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block font-medium text-sm text-gray-700">Nombre del negocio</label>
                    <input type="text" name="business_name" class="form-input mt-1 w-full" value="{{ old('business_name', $appearance->business_name ?? 'CarWash App') }}" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <x-input-label for="business_address" value="Dirección del Negocio" />
                        <textarea id="business_address" name="business_address" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" rows="2">{{ old('business_address', $appearance->business_address ?? '') }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="tax_id" value="RNC / ID Fiscal" />
                        <x-text-input id="tax_id" name="tax_id" type="text" class="mt-1 block w-full" :value="old('tax_id', $appearance->tax_id ?? '')" />
                    </div>
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Logo general</label>
                    <input type="file" name="logo" class="form-input mt-1">
                    <p class="text-xs text-gray-500 mt-1">Tamaño recomendado: 200x50 px.</p>
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Logo de inicio de sesión</label>
                    <input type="file" name="login_logo" class="form-input mt-1">
                    <p class="text-xs text-gray-500 mt-1">Dimensiones recomendadas: 500x500px.</p>
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">Favicon</label>
                    <input type="file" name="favicon" class="form-input mt-1">
                    <p class="text-xs text-gray-500 mt-1">Tamaño recomendado: 32x32 px.</p>
                    <div class="mt-2">
                        <p class="text-xs text-gray-500">Favicon actual:</p>
                        @php
                            $faviconPath = public_path('favicon.ico');
                            $faviconExists = file_exists($faviconPath);
                            $faviconVersion = $faviconExists ? filemtime($faviconPath) : null;
                        @endphp
                        @if($faviconExists)
                            <img src="{{ asset('favicon.ico') }}?v={{ $faviconVersion }}" class="w-8 h-8 object-contain border" alt="Favicon actual">
                        @else
                            <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='32' height='32'><rect width='32' height='32' rx='6' fill='%23e5e7eb'/><path d='M9 21c0-6 5-11 7-11s7 5 7 11-5 7-7 7-7-1-7-7Z' fill='%239ca3af'/></svg>" class="w-8 h-8 object-contain border" alt="Favicon de muestra">
                        @endif
                    </div>
                </div>
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-lg font-medium text-gray-900">QR en Factura</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div>
                            <x-input-label for="qr_code" value="Imagen Código QR (Recomendado: 300x300px)" />
                            <input type="file" name="qr_code" id="qr_code" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            @if(isset($appearance) && $appearance->qr_code_updated_at)
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500">QR Actual:</p>
                                    <img src="{{ asset('images/qr_code.png') }}?v={{ $appearance->qr_code_updated_at->timestamp }}" class="w-24 h-24 object-contain border" alt="QR Actual">
                                </div>
                            @endif
                        </div>
                        <div>
                            <x-input-label for="qr_description" value="Descripción QR (Texto debajo)" />
                            <x-text-input id="qr_description" name="qr_description" type="text" class="mt-1 block w-full" :value="old('qr_description', $appearance->qr_description ?? 'Escanéame')" placeholder="Ej: Síguenos en IG" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
