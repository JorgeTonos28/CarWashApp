<?php

namespace App\Mail;

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
        $plate = $vehicle?->plate ?? 'Vehículo';

        return $this->subject("Tu vehículo {$plate} está listo para retirar")
            ->markdown('emails.vehicle-ready', [
                'ticketWash' => $this->ticketWash,
                'vehicle' => $vehicle,
                'ticket' => $this->ticketWash->ticket,
                'customer' => $this->ticketWash->ticket?->customer,
            ]);
    }
}
