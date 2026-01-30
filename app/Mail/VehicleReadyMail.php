<?php

namespace App\Mail;

use App\Models\AppSetting;
use App\Models\TicketWash;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VehicleReadyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public TicketWash $ticketWash;

    public function __construct(TicketWash $ticketWash)
    {
        $this->ticketWash = $ticketWash;
    }

    public function build()
    {
        $vehicle = $this->ticketWash->vehicle;
        $ticket = $this->ticketWash->ticket;
        $customer = $ticket?->customer;

        $settings = AppSetting::first();
        $subject = $settings?->vehicle_ready_subject ?? 'Tu vehículo está listo para retirar';

        return $this->subject($this->replaceTokens($subject, $vehicle, $ticket, $customer))
            ->markdown('emails.vehicle-ready', [
                'vehicle' => $vehicle,
                'ticket' => $ticket,
                'customer' => $customer,
                'settings' => $settings,
                'replaceTokens' => fn ($value) => $this->replaceTokens($value, $vehicle, $ticket, $customer),
            ]);
    }

    private function replaceTokens(?string $value, ?\App\Models\Vehicle $vehicle, ?\App\Models\Ticket $ticket, ?\App\Models\Customer $customer): string
    {
        $text = $value ?? '';
        $replacements = [
            '{cliente}' => $customer?->name ?? 'Cliente',
            '{placa}' => $vehicle?->plate ?? 'N/D',
            '{modelo}' => $vehicle?->model ?? 'N/D',
            '{marca}' => $vehicle?->brand ?? 'N/D',
            '{ticket}' => $ticket?->id ? '#'.$ticket->id : 'N/D',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }
}
