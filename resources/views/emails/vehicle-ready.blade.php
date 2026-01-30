@component('mail::message')
# ¡Tu vehículo está listo!

{{ $replaceTokens($settings?->vehicle_ready_greeting ?? 'Hola {cliente},') }}

{{ $replaceTokens($settings?->vehicle_ready_body ?? 'Tu vehículo {placa} - {modelo} ya está listo para retirar.') }}

@component('mail::panel')
- **Placa:** {{ $vehicle?->plate ?? 'N/D' }}
- **Marca:** {{ $vehicle?->brand ?? 'N/D' }}
- **Modelo:** {{ $vehicle?->model ?? 'N/D' }}
- **Ticket:** #{{ $ticket?->id ?? 'N/D' }}
@endcomponent

{{ $replaceTokens($settings?->vehicle_ready_footer ?? 'Gracias por confiar en nosotros.') }}

{{ config('app.name') }}
@endcomponent
