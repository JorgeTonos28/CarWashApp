@component('mail::message')
# ¡Tu vehículo está listo!

Hola {{ $customer?->name ?? 'Cliente' }},

Tu vehículo **{{ $vehicle?->plate ?? 'Sin placa' }} - {{ $vehicle?->model ?? 'Modelo' }}** ya está listo para retirar.

@component('mail::panel')
- **Placa:** {{ $vehicle?->plate ?? 'N/D' }}
- **Marca:** {{ $vehicle?->brand ?? 'N/D' }}
- **Modelo:** {{ $vehicle?->model ?? 'N/D' }}
- **Ticket:** #{{ $ticket?->id ?? 'N/D' }}
@endcomponent

Gracias por confiar en nosotros.

{{ config('app.name') }}
@endcomponent
