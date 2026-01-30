<?php

namespace App\Http\Controllers;

use App\Mail\VehicleReadyMail;
use App\Models\TicketWash;
use Illuminate\Support\Facades\Mail;

class TicketWashController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,cajero']);
    }

    public function markAsReady(TicketWash $ticketWash)
    {
        if ($ticketWash->status === TicketWash::STATUS_READY) {
            return back()->with('success', 'El vehículo ya está marcado como listo.');
        }

        $ticketWash->update([
            'status' => TicketWash::STATUS_READY,
            'ready_at' => now(),
        ]);

        $ticketWash->loadMissing(['ticket.customer', 'vehicle']);

        $customer = $ticketWash->ticket?->customer;
        if ($customer && $customer->email && filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($customer->email)->queue(new VehicleReadyMail($ticketWash));
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return back()->with('success', 'Vehículo marcado como listo.');
    }
}
